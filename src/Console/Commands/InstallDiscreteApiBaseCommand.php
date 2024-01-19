<?php

namespace MakeIT\DiscreteApi\Base\Console\Commands;

use Illuminate\Console\Command;
use MakeIT\DiscreteApi\Base\Helpers\DiscreteApiHelpers;
use Nette\PhpGenerator\PsrPrinter;
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
        $this->error(' ATTENTION please !                                                   ');
        $this->error(' We strongly recommend to deploy this package on to CLEAN Laravel 10! ');
        $this->newLine();
        //
        if (is_file(base_path('config/discreteapibase.php'))) {
            if (!$this->confirm(
                question: "Before begin, we need to force delete existing config file to avoid mistakes in the future configuration.\n"
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
                    $this->newLine();
                    if (is_bool($v)) {
                        if ($v) {
                            $this->generateDescendantss();
                            //
                            $this->info(
                                'You need to add a Middleare to the Kernel'
                            );
                            $this->newLine();
                            $this->warn('     \'api\' => .... // to the end of list');
                            $this->comment('        '.(
                                ($quiz['modify_source_code'])
                                    ? '\App\Http\Middleware\DiscreteApi\Base\PreloadUserProfileData::class,'
                                    : '\MakeIT\DiscreteApi\Base\Http\Middleware\PreloadUserProfileData::class,'
                            ));
                            $this->newLine(2);
                            //
                            $this->info(
                                'You need to change trait paths in Your App\Models\User Model.'
                            );
                            $this->newLine();
                            $this->warn('     FROM:');
                            $this->comment('         use \MakeIT\DiscreteApi\Base\Traits\HasRoles;');
                            $this->comment('         use \MakeIT\DiscreteApi\Base\Traits\HasProfile;');
                            $this->newLine();
                            $this->warn('     TO:');
                            $this->comment('        use \App\Traits\DiscreteApi\Base\HasRoles;');
                            $this->comment('        use \App\Traits\DiscreteApi\Base\HasProfile;');
                            $this->newLine();
                        }
                    }
                    $this->_config['route_namespace'] = 'app';
                    break;
                case 'feature_email_verification':
                    if (is_bool($v)) {
                        $this->_config['features']['email_verification'] = $v;
                        if ($v) {
                            $this->info(
                                'You need to add MustVerifyEmail implementation to Your App\Models\User Model.'
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
                        $this->_config['features']['user_delete'] = $v;
                        if ($v) {
                            $this->info('INFORMATION: User Deletion routes are activated.');
                        } else {
                            $this->info('INFORMATION: User Deletion routes are DEactivated.');
                        }
                        $this->newLine();
                    }
                    break;
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
        $return = [];
        $dirs = DiscreteApiHelpers::dirs(__DIR__ . '/../../');
        $namespace = DiscreteApiHelpers::compute_namespace($this->_config);
        $namespaces = DiscreteApiHelpers::namespaces($namespace);
        foreach ($dirs as $type => $dir) {
            $return[$type] = DiscreteApiHelpers::scanDirs($type, $dir, $namespace, $namespaces, $this->_config, 'Base');
        }
        return $return;
    }

    /**
     * Generates source code files in to App namespace
     */
    public function generate(string $type, array $generated_classes): void
    {
        if ($type == 'observers' && !empty($generated_classes)) {
            $this->info('New Observers will be generated.');
            $this->_config['observersToRegister'] = [];
        }
        if ($type == 'policies' && !empty($generated_classes)) {
            $this->info('New Policies will be generated.');
            $this->_config['policiesToRegister'] = [];
        }
        $printer = new PsrPrinter();
        foreach ($generated_classes as $class) {
            if ($type == 'traits') {
                $this->_config = DiscreteApiHelpers::generateTrait($this, $class, $printer, $type, 'Base', $this->_config);
            } else {
                $this->_config = DiscreteApiHelpers::generate($this, $class, $printer, $type, 'Base', $this->_config);
            }
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
