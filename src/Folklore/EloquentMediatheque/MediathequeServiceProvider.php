<?php namespace Folklore\EloquentMediatheque;

use Illuminate\Support\ServiceProvider;
use Folklore\EloquentMediatheque\Models\Observers\FileableObserver;
use Folklore\EloquentMediatheque\Interfaces\FileableInterface;

class MediathequeServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$configPath = __DIR__.'/../../resources/config/config.php';
		$migrationsPath = __DIR__.'/../../resources/migrations/';
		
		$this->mergeConfigFrom($configPath, 'mediatheque');
		
		$this->publishes([
	        $configPath => config_path('mediatheque.php'),
	    ], 'config');
		
		$this->publishes([
	        $migrationsPath => database_path('/migrations')
	    ], 'migrations');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerObservers();
		$this->registerEvents();
	}
	
	/**
	 * Register model observers
	 *
	 * @return void
	 */
	public function registerObservers()
	{
		$this->app->bind('mediatheque.observers.fileable', function($app)
		{
			return new FileableObserver($app);
		});
	}

	/**
	 * Register the listener events
	 *
	 * @return void
	 */
	public function registerEvents()
	{
		
		//Fileable events
		$fileableObserver = $this->app->make('mediatheque.observers.fileable');
		$this->app['events']->listen('eloquent.updating*', function($model) use ($fileableObserver) {
			if($model instanceof FileableInterface)
			{
				$fileableObserver->updating($model);
			}
		});
		
		$this->app['events']->listen('eloquent.deleting*', function($model) use ($fileableObserver) {
			if($fileable instanceof FileableInterface)
			{
				$fileableObserver->deleting($model);
			}
		});
		
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
