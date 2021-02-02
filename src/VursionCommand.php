<?php

namespace Vursion\Vursion;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class VursionCommand extends Command
{
	protected $hidden = true;

	protected $signature = 'vursion:heartbeat';

	protected $description = 'Send heartbeat to vursion.io';

	protected $guzzle;

	protected $key;

	protected $enabled;

	protected $env_file;

	public function __construct()
	{
		parent::__construct();

		$this->key = config('vursion.key');

		$this->enabled = config('vursion.enabled');

		$this->guzzle = new Client([
			'base_uri'    => 'https://www.vursion.io/api/v1/',
			'http_errors' => false,
			'verify'      => false,
			'headers' 	  => [
				'vursion-key' => $this->key,
			],
		]);
	}

	public function handle()
	{
		if ($this->enabled && $this->key && $this->key !== '') {
			$this->guzzle->post('heartbeat', [
				'json' => [
					'env'             => $this->getEnvironmentVariableNames('.env'),
					'env.example'     => $this->getEnvironmentVariableNames('.env.example'),
					'composer.json'   => $this->getComposer(),
					'composer.lock'   => $this->getComposerLock(),
					'laravel_version' => app()->version(),
					'php_version' 	  => $this->getPhpVersion(),
					'php_version_cli' => phpversion(),
				],
			]);
		}
	}

	public function getPhpVersion()
	{
		$url = (version_compare(app()->version(), '5.6.12') >= 0) ? \Illuminate\Support\Facades\URL::signedRoute('vursion') : route('vursion');

		$response = $this->guzzle->get($url);

		if ($response->getStatusCode() === 200) {
			return json_decode($response->getBody()->getContents());
		}
	}

	public function getEnvironmentVariableNames($file)
	{
		if (! is_file(base_path($file))) {
			return;
		}

		$this->env_file = $file;

		set_error_handler(function ($errno, $errstr) {
		});

		foreach ([5, 4, 3, 2] as $function) {
			$dotenv = $this->{'dotenv_' . $function}();

			if ($dotenv) {
				restore_error_handler();

				return array_keys($dotenv->load());
			}
		}
	}

	public function getComposer()
	{
		$data = $this->readFileContents('composer.json');

		return [
			'require' 	   => ($data['require'] ?? null),
			'require-dev'  => ($data['require-dev'] ?? null),
			'repositories' => ($data['repositories'] ?? null),
		];
	}

	public function getComposerLock()
	{
		$data = $this->readFileContents('composer.lock');

		$packages = collect(($data['packages'] ?? []))->mapWithKeys(function ($package) {
			return [$package['name'] => $package['version']];
		})->toArray();

		$packages_dev = collect(($data['packages-dev'] ?? []))->mapWithKeys(function ($package) {
			return [$package['name'] => $package['version']];
		})->toArray();

		return [
			'packages' 	   => $packages,
			'packages-dev' => $packages_dev,
		];
	}

	public function readFileContents($file)
	{
		if (! is_file(base_path($file))) {
			return;
		}

		return json_decode(file_get_contents(base_path($file)), true);
	}

	protected function dotenv_2()
	{
		return new Dotenv(base_path(), $this->env_file);
	}

	protected function dotenv_3()
	{
		return Dotenv::create(base_path(), $this->env_file);
	}

	protected function dotenv_4()
	{
		$repository = \Dotenv\Repository\RepositoryBuilder::create()->withReaders([
			new \Dotenv\Repository\Adapter\EnvConstAdapter(),
			new \Dotenv\Repository\Adapter\ServerConstAdapter(),
		])->make();

		return Dotenv::create($repository, base_path(), $this->env_file);
	}

	protected function dotenv_5()
	{
		$repository = \Dotenv\Repository\RepositoryBuilder::createWithDefaultAdapters()->make();
		return Dotenv::create($repository, base_path(), $this->env_file);
	}
}
