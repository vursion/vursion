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

	public function __construct()
	{
		parent::__construct();

		$this->guzzle = new Client([
			'base_uri'    => 'https://www.vursion.io/api/v1/',
			'http_errors' => false,
			'verify'      => false,
			'headers' 	  => [
				'vursion-key' => config('vursion.key'),
			],
		]);
	}

	public function handle()
	{
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

	protected function getPhpVersion()
	{
		$url = route('vursion');

		if (version_compare(app()->version(), '5.6.12') >= 0) {
			$url .= '?signature=' . hash_hmac('sha256', $url, env('APP_KEY'));
		}

		$response = $this->guzzle->get($url);

		if ($response->getStatusCode() === 200) {
			return json_decode($response->getBody()->getContents());
		}
	}

	protected function getEnvironmentVariableNames($file)
	{
		if (! is_file(base_path($file))) {
			return;
		}

		try {
			$dotenv = Dotenv::create(base_path(), $file);
		} catch (Exception $e) {
			$repository = \Dotenv\Repository\RepositoryBuilder::create()
			    ->withReaders([
					new \Dotenv\Repository\Adapter\EnvConstAdapter(),
					new \Dotenv\Repository\Adapter\ServerConstAdapter(),
				])
			    ->make();

			$dotenv = Dotenv::create($repository, base_path(), $file);
		}

		return array_keys($dotenv->load());
	}

	protected function getComposer()
	{
		$data = $this->readFileContents('composer.json');

		if (! $data) {
			return;
		}

		return [
			'require' 	   => ($data['require'] ?? null),
			'require-dev'  => ($data['require-dev'] ?? null),
			'repositories' => ($data['repositories'] ?? null),
		];
	}

	protected function getComposerLock()
	{
		$data = $this->readFileContents('composer.lock');

		if (! $data) {
			return;
		}

		$packages = collect($data['packages'])->mapWithKeys(function ($package) {
			return [$package['name'] => $package['version']];
		});

		$packages_dev = collect($data['packages-dev'])->mapWithKeys(function ($package) {
			return [$package['name'] => $package['version']];
		});

		return [
			'packages' 	   => $packages,
			'packages-dev' => $packages_dev,
		];
	}

	protected function readFileContents($file)
	{
		if (! is_file(base_path($file))) {
			return;
		}

		return json_decode(file_get_contents(base_path($file)), true);
	}
}
