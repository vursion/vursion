<?php

namespace Vursion\Vursion\Tests;

use Vursion\Vursion\VursionServiceProvider;

class VursionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_be_instantiated()
    {
        static::assertInstanceOf('Vursion\Vursion\VursionServiceProvider', $this->app->getProvider(VursionServiceProvider::class));
    }

    public function test_it_can_get_the_laravel_version()
    {
        $this->assertTrue(is_string(app()->version()));
    }

    public function test_it_can_get_the_php_cli_version()
    {
        $this->assertTrue(is_string(phpversion()));
    }

    public function test_it_can_collect_env_keys()
    {
        $this->mockEnv();

        $data = $this->mock->getEnvironmentVariableNames('.env.test');

        $this->assertTrue(is_array($data));

        $data = array_filter($data);

        $this->assertNotEmpty($data);

        $this->assertEquals(['VURSION_KEY'], $data);
    }

    public function test_it_can_collect_composer_json()
    {
        $this->mockComposer();

        $data = $this->mock->getComposer();

        $this->assertTrue(is_array($data));
        $this->assertNotEmpty($data);

        $this->assertArrayHasKey('require', $data);
        $this->assertArrayHasKey('require-dev', $data);
        $this->assertArrayHasKey('repositories', $data);

        $this->assertEquals($this->stub_composer['require'], $data['require']);
        $this->assertEquals($this->stub_composer['require-dev'], $data['require-dev']);
        $this->assertEquals($this->stub_composer['repositories'], $data['repositories']);
    }

    public function test_it_can_collect_composer_lock()
    {
        $this->mockComposerLock();

        $data = $this->mock->getComposerLock();

        $this->assertTrue(is_array($data));
        $this->assertNotEmpty($data);

        $this->assertArrayHasKey('packages', $data);
        $this->assertArrayHasKey('packages-dev', $data);

        $this->assertNotEmpty($data['packages']);
        $this->assertNotEmpty($data['packages-dev']);
    }
}
