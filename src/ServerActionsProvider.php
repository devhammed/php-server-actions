<?php

namespace DevHammed\ServerActions;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;

class ServerActionsProvider extends PackageServiceProvider
{
	public function configurePackage(Package $package): void
	{
		$package->name('server-actions')
		        ->hasConfigFile()
		        ->hasRoute('web')
		        ->hasInstallCommand(function (InstallCommand $command) {
					$command
						->publishConfigFile()
						->askToStarRepoOnGitHub('devhammed/php-server-actions')
						->endWith(function ($command) {
							$command->info('Have a great day!');
						});
				});
	}

	public function packageBooted(): void
	{
		parent::packageBooted();

		$config = $this->app['config']['server-actions'];

		$serverActionsEntryProvider = $config['server_entry'];

		$provider = new $serverActionsEntryProvider['provider'](
			...$serverActionsEntryProvider['parameters'],
		);

		useServer()->withServerEntry($provider)
		           ->withServerActionsUrl($config['route']);
	}
}