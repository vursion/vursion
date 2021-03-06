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
        $version = app()->version();

        $this->assertTrue(is_string($version));
        $this->assertFalse(empty(trim($version)));
    }

    public function test_it_can_get_the_php_cli_version()
    {
        $version = phpversion();

        $this->assertTrue(is_string($version));
        $this->assertFalse(empty(trim($version)));
    }

    public function test_route_has_a_signed_url()
    {
        $shouldBeSigned = (version_compare(app()->version(), '5.6.12') >= 0);
        $hasSignature   = strpos($this->mock->route, '?signature=');

        if ($shouldBeSigned) {
            $this->assertTrue($hasSignature !== false);
        } else {
            $this->assertTrue($hasSignature === false);
        }
    }

    public function test_it_can_get_the_php_version()
    {
        $response = $this->get($this->mock->route);
        $response->assertStatus(200);

        $version = $response->getData();

        $this->assertTrue(is_string($version));
        $this->assertFalse(empty(trim($version)));
    }

    public function test_it_can_collect_env_keys()
    {
        $this->mockEnv();

        $data = $this->mock->getEnvironmentVariableNames('.env.test');

        $this->assertTrue(is_array($data));
        $this->assertArrayHasKey('VURSION_KEY', array_flip($data));
    }

    public function test_it_can_read_nonsensitive_env_values()
    {
        $this->mockEnv();

        $this->mock->getEnvironmentVariableNames('.env.test');

        $this->assertEquals('testing', $_ENV['APP_ENV']);
        $this->assertEquals('true', $_ENV['APP_DEBUG']);
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

    public function test_it_can_collect_package_json()
    {
        $this->mockPackage();

        $data = $this->mock->getPackage();

        $this->assertTrue(is_array($data));
        $this->assertNotEmpty($data);

        $this->assertArrayHasKey('require', $data);
        $this->assertArrayHasKey('require-dev', $data);

        $this->assertEquals($this->stub_package['dependencies'], $data['require']);
        $this->assertEquals($this->stub_package['devDependencies'], $data['require-dev']);
    }

    public function test_it_can_collect_package_lock_v1()
    {
        $this->mockPackageLock(1);

        $data = $this->mock->getPackageLock();

        $this->assertTrue(is_array($data));
        $this->assertNotEmpty($data);

        $this->assertArrayHasKey('packages', $data);
        $this->assertNotEmpty($data['packages']);
        $this->assertArrayHasKey('gulp', $data['packages']);
    }

    public function test_it_can_collect_package_lock_v2()
    {
        $this->mockPackageLock(2);

        $data = $this->mock->getPackageLock();

        $this->assertTrue(is_array($data));
        $this->assertNotEmpty($data);

        $this->assertArrayHasKey('packages', $data);
        $this->assertNotEmpty($data['packages']);
        $this->assertArrayHasKey('gulp', $data['packages']);
    }
}
