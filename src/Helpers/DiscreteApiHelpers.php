<?php

/** @noinspection PhpParamsInspection */

/** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace MakeIT\DiscreteApi\Base\Helpers;

use Exception;
use Illuminate\Console\Command;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use Nette\PhpGenerator\TraitType;

class DiscreteApiHelpers
{
    /**
     * Return Package directory Structure as a paths
     *
     * @param string $start
     * @return array
     */
    public static function dirs(string $start = __DIR__ . '/../'): array
    {
        return [
            'actions' => realpath($start . 'Actions'),
            'controllers' => realpath($start . 'Http/Controllers'),
            'middleware' => realpath($start . 'Http/Middleware'),
            'models' => realpath($start . 'Models'),
            'notifications' => realpath($start . 'Notifications'),
            'observers' => realpath($start . 'Observers'),
            'policies' => realpath($start . 'Policies'),
            'rules' => realpath($start . 'Rules'),
            'traits' => realpath($start . 'Traits'),
        ];
    }

    /**
     * Return Package directory Structure as a Namespaces
     *
     * @param string|null $namespace
     * @return string[]
     */
    public static function namespaces(string $namespace = null): array
    {
        return [
            'actions' => $namespace . 'Actions',
            'controllers' => $namespace . 'Http\Controllers',
            'middleware' => $namespace . 'Http\Middleware',
            'models' => $namespace . 'Models',
            'notifications' => $namespace . 'Notifications',
            'observers' => $namespace . 'Observers',
            'policies' => $namespace . 'Policies',
            'rules' => $namespace . 'Rules',
            'traits' => $namespace . 'Traits',
        ];
    }

    /**
     * @param array $config
     * @return string
     */
    public static function compute_namespace(array $config = []): string
    {
        if ($config['route_namespace'] === 'app') {
            return $config['namespaces']['app'];
        }
        return $config['namespaces']['package'];
    }

    /**
     * Scan directory for .php files and returns array of class names with their namespaces
     *
     * @param string $type
     * @param string $dir
     * @param string $namespace
     * @param array $namespaces
     * @param array $config
     * @param string $package
     * @return array
     */
    public static function scanDirs(string $type, string $dir, string $namespace, array $namespaces = [], array $config = [], string $package = 'Base'): array
    {
        if (!is_dir($dir)) {
            return [];
        }
        $return = [];
        $h = opendir($dir);
        while (false !== ($entry = readdir($h))) {
            if (is_file($dir . '/' . $entry)) {
                $path = $dir . '/' . $entry;
                $temp = [
                    'classname' => str_replace('.php', null, basename($path)),
                    'trait' => null,
                    'model' => null,
                    'model_namespace' => $config['namespaces']['app'] . 'Models\\DiscreteApi\\' . $package . '\\',
                    'use' => $namespaces[$type] . '\\' . str_replace(
                        '.php',
                        null,
                        basename($path)
                    ),
                    'as' => 'DiscreteApi' . $package . str_replace(
                        '.php',
                        null,
                        basename($path)
                    ),
                    'ns' => preg_replace(
                        '/^\\\/',
                        null,
                        $config['namespaces']['app'] . str_replace(
                            $namespace,
                            null,
                            $namespaces[$type]
                        ) . '\\DiscreteApi\\' . $package
                    ),
                    'app_model' => null,
                    'app_path' => app_path(str_replace([$namespace, '\\'], [null, '/'], $namespaces[$type]) . '/DiscreteApi/' . $package),
                    'app_filename' => app_path(str_replace([$namespace, '\\'], [null, '/'], $namespaces[$type]) . '/DiscreteApi/' . $package . '/' . basename($path)),
                    'package_path' => $path,
                ];
                switch ($type) {
                    case 'traits':
                        $temp['trait'] = str_replace('.php', null, basename($path));
                        break;
                    case 'observers':
                        unset($temp['trait']);
                        // For User Model different static conditions
                        if ($temp['classname'] == 'UserObserver') {
                            $temp['classname'] = $package . $temp['classname'];
                            $temp['model'] = '\\App\\Models\\User';
                            $temp['app_model'] = $config['namespaces']['app'] . 'Models\\User';
                            $temp['app_filename'] = app_path(str_replace([$namespace, '\\'], [null, '/'], $namespaces[$type]) . '/DiscreteApi/' . $package . '/' . $package . basename($path));
                        } else {
                            $temp['classname'] = $package . $temp['classname'];
                            $temp['model'] = preg_replace('/^\\\/', null, str_replace('\\Observers\\', '\\Models\\', $namespaces[$type] . '\\' . str_replace('Observer.php', null, basename($path))));
                            $temp['app_model'] = $config['namespaces']['app'] . 'Models\\DiscreteApi\\' . $package . '\\' . str_replace('Observer.php', null, basename($path));
                            $temp['app_filename'] = app_path(str_replace([$namespace, '\\'], [null, '/'], $namespaces[$type]) . '/DiscreteApi/' . $package . '/' . $package . basename($path));
                        }
                        break;
                    case 'policies':
                        unset($temp['trait']);
                        // For User Model different static conditions
                        if ($temp['classname'] == 'UserPolicy') {
                        } else {
                        }
                        $temp['model'] = preg_replace('/^\\\/', null, str_replace('\\Policies\\', '\\Models\\', ($namespaces[$type] . '\\' . str_replace('Policy.php', null, basename($path)))));
                        $temp['app_model'] = $config['namespaces']['app'] . 'Models\\DiscreteApi\\' . $package . '\\' . str_replace('Policy.php', null, basename($path));
                        break;
                    default:
                        unset($temp['trait']);
                        break;
                }
                $return[] = $temp;
            }
        }
        closedir($h);
        return $return;
    }

    /**
     * Generate trait for descendant and store it in app filesystem
     */
    public static function generateTrait(Command $Command, array $class, PsrPrinter $printer, string $type = null, string $package = 'Base', array $config = []): array
    {
        $ns = new PhpNamespace($class['ns']);
        $target = TraitType::fromCode(file_get_contents($class['package_path']));
        /** @noinspection PhpParamsInspection */
        $ns->add($target);
        $trait = $printer->setTypeResolving(false)->printNamespace($ns);
        $trait = str_replace($config['namespaces']['package'] . 'Models\\', $config['namespaces']['app'] . 'Models\\DiscreteApi\\' . $package . '\\', $trait);
        if (!is_dir($class['app_path']) && !is_file($class['app_path']) && !is_link($class['app_path'])) {
            try {
                mkdir($class['app_path'], 0755, true);
            } catch (Exception $e) {
                $Command->error($class['app_path']);
                $Command->error('Is not writeable!');
                $Command->error('Please check path!');
                $Command->error($e->getMessage());
                return $config;
            }
        }
        $f = fopen($class['app_filename'], 'w');
        fwrite($f, "<?php\n\n" . $trait);
        fclose($f);
        return $config;
    }

    /**
     * Generate class for descendant and store it in app filesystem
     *
     * @param Command $Command
     * @param array $class
     * @param PsrPrinter $printer
     * @param string|null $type
     * @param string $package
     * @param array $config
     * @return void
     */
    public static function generate(Command $Command, array $class, PsrPrinter $printer, string $type = null, string $package = 'Base', array $config = []): array
    {
        $ns = new PhpNamespace($class['ns']);
        $target = ClassType::fromCode(file_get_contents($class['package_path']));
        if (in_array($type, ['observers', 'policies'])) {
            $target->setName($package.$target->getName());
        }
        $target->setFinal()->setExtends($class['as']);
        if (in_array($type, ['models', 'observers', 'policies'])) {
            $tmp_traits = $target->getTraits();
            if (!empty($tmp_traits)) {
                $traits = [];
                $target->setTraits([]);
                foreach ($tmp_traits as $tmp_trait) {
                    $_bn = class_basename($tmp_trait->getName());
                    $_fn = str_replace('\\' . $_bn, null, $tmp_trait->getName());
                    $_fn = (preg_match("/^\\\/", $tmp_trait->getName()) ? null : '\\') . $_fn;
                    $new_trait = [
                        'name' => $_bn,
                        'path' => str_replace(
                            $config['namespaces']['package'],
                            $config['namespaces']['app'],
                            $_fn
                        ) . '\\'
                    ];
                    if (preg_match("/^\\\App/iu", $new_trait['path'])) {
                        $new_trait['path'] .= 'DiscreteApi\\' . $package . '\\';
                    }
                    $traits[] = $new_trait;
                }
                unset($tmp_traits, $tmp_trait, $_bn, $_fn);
                if (!empty($traits)) {
                    foreach ($traits as $trait) {
                        $target->addTrait($trait['name']);
                    }
                    unset($trait);
                }
            }
        }
        $ns->add($target);
        if (!empty($traits)) {
            foreach ($traits as $trait) {
                $ns->addUse($trait['path'] . $trait['name']);
            }
        }
        if (!empty($class['use']) && !empty($class['as'])) {
            $ns->addUse($class['use'], $class['as']);
        }
        if (!is_dir($class['app_path']) && !is_file($class['app_path']) && !is_link($class['app_path'])) {
            try {
                mkdir($class['app_path'], 0755, true);
            } catch (Exception $e) {
                $Command->error($class['app_path']);
                $Command->error('Is not writeable!');
                $Command->error('Please check path!');
                $Command->error($e->getMessage());
                return $config;
            }
        }
        if (in_array($type, ['observers', 'policies'])) {
            $f = fopen($class['app_filename'], 'w');
        } else {
            $f = fopen($class['app_filename'], 'w');
        }
        fwrite($f, "<?php\n\n" . $printer->setTypeResolving(false)->printNamespace($ns));
        fclose($f);
        $fqcn = $class['app_model'];
        switch ($type) {
            case 'observers':
                if (preg_match("/^App\\\\Models\\\\DiscreteApi\\\\'.$package.'\\\\User$/", $fqcn)) {
                    $fqcn = $config['namespaces']['app'] . 'Models\\User';
                }
                $config['observersToRegister'][$fqcn] = $class['ns'] . '\\' . $class['classname'];
                break;
            case 'policies':
                $config['policiesToRegister'][$fqcn] = $class['ns'] . '\\' . $class['classname'];
                break;
        }
        return $config;
    }

}
