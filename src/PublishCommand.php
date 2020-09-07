<?php

namespace Vursion\Vursion;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
	protected $signature = 'vursion:publish {--force : Overwrite any existing files}';

	protected $description = 'Publish vursion configuration';

	public function handle()
	{
		$this->call('vendor:publish', [
			'--provider' => 'Vursion\Vursion\VursionServiceProvider',
			'--force'    => $this->option('force'),
		]);
	}
}
