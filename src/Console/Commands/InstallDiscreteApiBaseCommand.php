<?php

namespace MakeIT\DiscreteApi\Base\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use Nette\PhpGenerator\TraitType;
use Symfony\Component\VarExporter\Exception\ExceptionInterface;
use Symfony\Component\VarExporter\VarExporter;

class InstallDiscreteApiBaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'makeit:discreteapi:base:install';

    /**
     * Pachage configuration
     */
    protected array $_config;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installation master. Small Quiz and actions based on answers.';

    /**
     * Execute the console command.
     *
     * @throws ExceptionInterface
     */
    public function handle(): void
    {
        $this->_config = require realpath(__DIR__ . '/../../config.php');
        $this->newLine();
        $this->info('This is MakeIT\'s Discrete API (Base) Installer.');
        $this->newLine();
        $this->error('ATTENTION please !');
        $this->warn('We strongly recommend to deploy this package on to CLEAN Laravel 10!');
        $this->newLine();
        //
        $this->info(base_path('config/discreteapibase.php'));
        if (is_file(base_path('config/discreteapibase.php'))) {
            if (!$this->confirm(
                question: "Before begin, we need to force delete existing config file to avoid mistakes in the future confuration?\n"
            )) {
                $this->error('Cant continue with existing config file:');
                $this->error('    config/discreteapibase.php          ');
                $this->newLine();
                return;
            }
        }
        $quiz['modify_source_code'] = $this->confirm(
            question: "Are you planning to modify the Source Code of this package?\n",
            default: true
        );
        $quiz['feature_email_verification'] = $this->confirm(
            question: "Do you planning to use email verification while registration?\n",
            default: true
        );
        $quiz['feature_user_deletion'] = $this->confirm(
            question: "Turn on the User self-delete (soft deletes)?\n",
            default: true
        );
        //
        $this->comment('INTEGRATION INSTRUCTIONS:');
        $this->newLine();
        foreach ($quiz as $k => $v) {
            switch ($k) {
                case 'modify_source_code':
                    $this->info('You need to add HasProfile Trait to the User Model.');
                    $this->newLine();
                    if (is_bool($v)) {
                        if ($v) {
                            $this->comment("     use App\Traits\DiscreteApi\Base\HasProfile;");
                            $this->generateDescendantss();
                        } else {
                            $this->comment("     use MakeIT\DiscreteApi\Base\Traits\HasProfile;");
                        }
                    }
                    $this->comment('     class User....');
                    $this->comment('     {');
                    $this->comment('         use HasProfile;');
                    $this->comment('         ....');
                    $this->newLine();
                    $this->_config['route_namespace'] = 'app';
                    break;
                case 'feature_email_verification':
                    if (is_bool($v)) {
                        $this->_config['email_verification'] = $v;
                        if ($v) {
                            $this->info(
                                "You need to add MustVerifyEmail implementation to Your App\Models\User Model."
                            );
                            $this->newLine();
                            $this->comment("     use Illuminate\Contracts\Auth\MustVerifyEmail;");
                            $this->comment('     class User extends Authenticatable implements MustVerifyEmail');
                            $this->newLine();
                        }
                    }
                    break;
                case 'feature_user_deletion':
                    if (is_bool($v)) {
                        $this->_config['user_delete'] = $v;
                        if ($v) {
                            $this->info('INFORMATION: User Deletion routes are activated.');
                        } else {
                            $this->info('INFORMATION: User Deletion routes are DEactivated.');
                        }
                        $this->newLine();
                    }
                    break;
                default:
                    $this->error('Quiz failed. Please, start over!');
                    return;
            }
        }
        $this->newLine();
        $this->writeNewConfig();
        $this->info('Done.');
        $this->newLine();
    }

    /**
     * Wrapper for generators
     */
    protected function generateDescendantss(): void
    {
        foreach ($this->getClasses() as $type => $generated_classes) {
            $this->generate($type, $generated_classes);
        }
    }

    /**
     * Returns an array of classes of the package compatible twith futute modifications as parent classes
     */
    protected function getClasses(): array
    {
        $dirs = [
            'actions' => realpath(__DIR__ . '/../../Actions'),
            'controllers' => realpath(__DIR__ . '/../../Http/Controllers'),
            'middleware' => realpath(__DIR__ . '/../../Http/Middleware'),
            'models' => realpath(__DIR__ . '/../../Models'),
            'notifications' => realpath(__DIR__ . '/../../Notifications'),
            'observers' => realpath(__DIR__ . '/../../Observers'),
            'rules' => realpath(__DIR__ . '/../../Rules'),
            'traits' => realpath(__DIR__ . '/../../Traits'),
        ];
        $namespace = compute_namespace();
        $namespaces = [
            'actions' => $namespace . 'Actions',
            'controllers' => $namespace . 'Http\Controllers',
            'middleware' => $namespace . 'Http\Middleware',
            'models' => $namespace . 'Models',
            'notifications' => $namespace . 'Notifications',
            'observers' => $namespace . 'Observers',
            'rules' => $namespace . 'Rules',
            'traits' => $namespace . 'Traits',
        ];
        $return = [];
        /**
         * Scan directory for .php files and returns array of class names with their namespaces
         *
         * @param string $type
         * @param string $dir
         * @return array
         */
        $scanDir = function (string $type, string $dir) use ($namespaces) {
            $return = [];
            $h = opendir($dir);
            while (false !== ($entry = readdir($h))) {
                if (is_file($dir . '/' . $entry)) {
                    $path = $dir . '/' . $entry;
                    $temp = [
                        'trait' => str_replace('.php', null, basename($path)),
                        'classname' => str_replace('.php', null, basename($path)),
                        'model' => null,
                        'model_namespace' => null,
                        'use' => preg_replace(
                            '/^\\\/',
                            null,
                            $namespaces[$type] . '\\' . str_replace('.php', null, basename($path))
                        ),
                        'as' => 'DiscreteApiBase' . str_replace('.php', null, basename($path)),
                        'ns' => preg_replace(
                            '/^\\\/',
                            null,
                            $this->_config['namespaces']['app'] . str_replace(
                                compute_namespace(),
                                null,
                                $namespaces[$type]
                            ) . '\\DiscreteApi\\Base'
                        ),
                        'app_model' => null,
                        'app_path' => app_path(
                            str_replace(
                                [compute_namespace(), '\\'],
                                [null, '/'],
                                $namespaces[$type]
                            ) . '/DiscreteApi/Base'
                        ),
                        'app_filename' => app_path(
                            str_replace(
                                [compute_namespace(), '\\'],
                                [null, '/'],
                                $namespaces[$type]
                            ) . '/DiscreteApiBase/' . basename($path)
                        ),
                        'package_path' => $path,
                    ];
                    switch ($type) {
                        case 'traits':
                            break;
                        case 'observers':
                            unset($temp['trait']);
                            $temp['model_namespace'] = str_replace(
                                '\\Observers\\',
                                '\\Models\\',
                                'App\\Models\\DiscreteApi\\Base\\'
                            );
                            $temp['model'] = preg_replace(
                                '/^\\\/',
                                null,
                                str_replace(
                                    '\\Observers\\',
                                    '\\Models\\',
                                    ($namespaces[$type] . '\\' . str_replace('Observer.php', null, basename($path)))
                                )
                            );
                            $temp['app_model'] = str_replace(
                                '\\Observers\\',
                                '\\Models\\',
                                'App\\Models\\DiscreteApi\\Base\\'
                            ) . str_replace(
                                'Observer.php',
                                null,
                                basename($path)
                            );
                            break;
                        case 'policies':
                            unset($temp['trait']);
                            $temp['model_namespace'] = str_replace(
                                '\\Policies\\',
                                '\\Models\\',
                                'App\\Models\\DiscreteApi\\Base\\'
                            );
                            $temp['model'] = preg_replace(
                                '/^\\\/',
                                null,
                                str_replace(
                                    '\\Observers\\',
                                    '\\Models\\',
                                    ($namespaces[$type] . '\\' . str_replace('Observer.php', null, basename($path)))
                                )
                            );
                            $temp['app_model'] = str_replace(
                                '\\Observers\\',
                                '\\Models\\',
                                'App\\Models\\DiscreteApi\\Base\\'
                            ) . str_replace(
                                'Observer.php',
                                null,
                                basename($path)
                            );
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
        };
        foreach ($dirs as $type => $dir) {
            $return[$type] = $scanDir($type, $dir);
        }
        return $return;
    }

    /**
     * Generates source code files in to App namespace
     */
    protected function generate(string $type, array $generated_classes): void
    {
        if (!empty($generated_classes['observers'])) {
            $this->_config['observersToRegister'] = [];
        }
        if (!empty($generated_classes['policies'])) {
            $this->_config['policiesToRegister'] = [];
        }
        $printer = new PsrPrinter();
        foreach ($generated_classes as $class) {
            if ($type == 'traits') {
                $this->_generateTrait($class, $printer, $type);
            } else {
                $this->_generate($class, $printer, $type);
            }
        }
    }

    /**
     * Generate trait for descendant and store it in app filesystem
     */
    protected function _generateTrait(array $class, PsrPrinter $printer, string $type = null): void
    {
        $ns = new PhpNamespace($class['ns']);
        $target = TraitType::fromCode(file_get_contents($class['package_path']));
        /** @noinspection PhpParamsInspection */
        $ns->add($target);
        $trait = $printer->setTypeResolving(false)->printNamespace($ns);
        $trait = str_replace(
            [
                config('discreteapibase.namespaces.package') . 'Models\\',
            ],
            [
                config('discreteapibase.namespaces.app') . 'Models\\DiscreteApi\\Base\\',
            ],
            $trait
        );
        if (!is_dir($class['app_path']) && !is_file($class['app_path']) && !is_link($class['app_path'])) {
            try {
                mkdir($class['app_path'], 0755, true);
            } catch (Exception $e) {
                $this->error($class['app_path']);
                $this->error('Is not writeable!');
                $this->error('Please check path!');
                $this->error($e->getMessage());
                return;
            }
        }
        $f = fopen($class['app_filename'], 'w');
        fwrite($f, "<?php\n\n" . $trait);
        fclose($f);
        switch ($type) {
            case 'observers':
                $fqcn = $class['app_model'];
                $this->_config['observersToRegister'][$fqcn] = $class['ns'] . '\\' . $class['classname'];
                break;
            case 'policies':
                $fqcn = $class['app_model'];
                $this->_config['policiesToRegister'][$fqcn] = $class['ns'] . '\\' . $class['classname'];
                break;
        }
    }

    /**
     * Generate class for descendant and store it in app filesystem
     */
    protected function _generate(array $class, PsrPrinter $printer, string $type = null): void
    {
        $ns = new PhpNamespace($class['ns']);
        $target = ClassType::fromCode(file_get_contents($class['package_path']));
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $target->setFinal()->setExtends($class['as']);
        if ($type == 'models') {
            /** @noinspection PhpUndefinedMethodInspection */
            $tmp_traits = $target->getTraits();
            if (!empty($tmp_traits)) {
                $traits = [];
                /** @noinspection PhpUndefinedMethodInspection */
                $target->setTraits([]);
                foreach ($tmp_traits as $tr) {
                    $_bn = class_basename($tr->getName());
                    $_fn = str_replace('\\' . $_bn, null, $tr->getName());
                    $_fn = (preg_match("/^\\\/", $tr->getName()) ? null : '\\') . $_fn;
                    $traits[] = [
                        'name' => $_bn,
                        'path' => str_replace(
                            config('discreteapibase.namespaces.package'),
                            config('discreteapibase.namespaces.app'),
                            ($_fn)
                        ) . '\\DiscreteApi\\Base\\'
                    ];
                }
                unset($tmp_traits, $tr);
                if (!empty($traits)) {
                    foreach ($traits as $tr) {
                        /** @noinspection PhpUndefinedMethodInspection */
                        $target->addTrait($tr['name']);
                    }
                    unset($tr);
                }
            }
        }
        /** @noinspection PhpParamsInspection */
        $ns->add($target);
        if (!empty($traits)) {
            foreach ($traits as $tr) {
                $ns->addUse($tr['path'] . $tr['name']);
            }
        }
        if (!empty($class['use']) && !empty($class['as'])) {
            $ns->addUse($class['use'], $class['as']);
        }
        if (!is_dir($class['app_path']) && !is_file($class['app_path']) && !is_link($class['app_path'])) {
            try {
                mkdir($class['app_path'], 0755, true);
            } catch (Exception $e) {
                $this->error($class['app_path']);
                $this->error('Is not writeable!');
                $this->error('Please check path!');
                $this->error($e->getMessage());
                return;
            }
        }
        $f = fopen($class['app_filename'], 'w');
        fwrite($f, "<?php\n\n" . $printer->setTypeResolving(false)->printNamespace($ns));
        fclose($f);
        switch ($type) {
            case 'observers':
                $fqcn = $class['app_model'];
                $this->_config['observersToRegister'][$fqcn] = $class['ns'] . '\\' . $class['classname'];
                break;
            case 'policies':
                $fqcn = $class['app_model'];
                $this->_config['policiesToRegister'][$fqcn] = $class['ns'] . '\\' . $class['classname'];
                break;
        }
    }

    /**
     * @throws ExceptionInterface
     */
    protected function writeNewConfig(): void
    {
        $content = VarExporter::export($this->_config);
        file_put_contents(
            config_path('discreteapibase.php'),
            "<?php\n\nreturn " . $content . ";\n"
        );
    }
}
