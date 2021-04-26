<?php

namespace Vursion\Vursion\Tests;

use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase as Orchestra;
use Vursion\Vursion\VursionCommand;
use Vursion\Vursion\VursionServiceProvider;

abstract class TestCase extends Orchestra
{
    protected $mock;

    protected $stub_composer;

    protected $stub_composer_lock;

    protected $stub_env;

    protected $stub_package;

    protected $stub_package_lock_v1;

    protected $stub_package_lock_v2;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadStubs();

        $this->mock = $this->createPartialMock(VursionCommand::class, ['readFileContents']);

        foreach (get_object_vars(new VursionCommand()) as $key => $value) {
            $this->mock->{$key} = $value;
        }
    }

    protected function getPackageProviders($app)
    {
        return [VursionServiceProvider::class];
    }

    protected function loadStubs()
    {
        $this->stub_composer        = json_decode(file_get_contents(__DIR__ . '/Stubs/composer.json'), true);
        $this->stub_composer_lock   = json_decode(file_get_contents(__DIR__ . '/Stubs/composer.lock'), true);
        $this->stub_env             = file_get_contents(__DIR__ . '/Stubs/.env.test');
        $this->stub_package         = json_decode(file_get_contents(__DIR__ . '/Stubs/package.json'), true);
        $this->stub_package_lock_v1 = json_decode(file_get_contents(__DIR__ . '/Stubs/package-lock-v1.json'), true);
        $this->stub_package_lock_v2 = json_decode(file_get_contents(__DIR__ . '/Stubs/package-lock-v2.json'), true);
    }

    protected function mockComposer()
    {
    	$this->mock->method('readFileContents')
                ->with('composer.json')
                ->willReturn($this->stub_composer);
    }

    protected function mockComposerLock()
    {
        $this->mock->method('readFileContents')
                ->with('composer.lock')
                ->willReturn($this->stub_composer_lock);
    }

    protected function mockEnv()
    {
    	file_put_contents(base_path('.env.test'), $this->stub_env);
    }

    protected function mockPackage()
    {
        $this->mock->method('readFileContents')
                ->with('package.json')
                ->willReturn($this->stub_package);
    }

    protected function mockPackageLock($version)
    {
        $this->mock->method('readFileContents')
                ->with('package-lock.json')
                ->willReturn($this->{'stub_package_lock_v' . $version});
    }
}
