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

    public function setUp(): void
    {
        parent::setUp();

        $this->loadStubs();

        $this->mock = $this->createPartialMock(VursionCommand::class, ['readFileContents']);
    }

    protected function getPackageProviders($app)
    {
        return [VursionServiceProvider::class];
    }

    protected function loadStubs()
    {
		$this->stub_composer      = json_decode(file_get_contents(dirname(__FILE__) . '/Stubs/composer.json'), true);
		$this->stub_composer_lock = json_decode(file_get_contents(dirname(__FILE__) . '/Stubs/composer.lock'), true);
		$this->stub_env           = file_get_contents(dirname(__FILE__) . '/Stubs/.env.test');
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
}
