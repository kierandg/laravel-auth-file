<?php

/**
 * @author     Gponster <anhvudg@gmail.com>
 * @copyright  Copyright (c) 2014
 */
namespace Gponster\Auth\File;

use Gponster\Auth\File\Guard;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {

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
	public function boot() {
		// ---------------------------------------------------------------------
		// Notes
		// ---------------------------------------------------------------------
		// The register method is called immediately when the service provider is registered,
		// while the boot command is only called right before a request is routed.
		// So, if actions in your service provider rely on another service provider
		// already being registered, or you are overriding services bound by another provider,
		// you should use the boot method.

		// Gonster 2014/06/01 fixed configuration file not loaded on Laravel 4.0
		// @see https://coderwall.com/p/svocrg
		// $this->package('gponster/laravel-auth-file', null, __DIR__.'/../../..');
		$this->package('gponster/laravel-auth-file');

		\Auth::extend('file',
			function ($app) {
				$model = $this->app['config']['auth.model'];
				$provider = new UserProvider($model);
				return new Guard($provider, $this->app['session.store']);
			});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return [];
	}
}