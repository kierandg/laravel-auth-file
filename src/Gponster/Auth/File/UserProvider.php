<?php

/**
 * @author     Gponster <anhvudg@gmail.com>
 * @copyright  Copyright (c) 2014
 */
namespace Gponster\Auth\File;

use Illuminate\Auth\UserProviderInterface;
use Illuminate\Auth\UserInterface;

class UserProvider implements UserProviderInterface {

	/**
	 * The Eloquent user model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Create a new API user provider.
	 *
	 * @return void
	 */
	public function __construct($model) {
		$this->model = $model;
	}

	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param mixed $identifier
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveById($identifier) {
		$user = $this->find($identifier);
		if(! $user) {
			return null;
		}

		$model = $this->createModel($user);
		return $model;
	}

	private function verify($credentials) {
		$users = \Config::get('laravel-auth-file::users', array());
		$usernameField = \Config::get('auth.username', 'username');
		$passwordField = \Config::get('auth.password', 'password');

		// Get the first matching user from the list
		$user = array_first($users,
			function ($k, $v) use($credentials, $usernameField, $passwordField) {
				if(array_key_exists($usernameField, $v) && array_key_exists($passwordField, $v) &&
					 array_key_exists($usernameField, $credentials) && array_key_exists($passwordField, $credentials) &&
					 $credentials[$usernameField] === $v[$usernameField] &&
					 \Hash::check($credentials[$passwordField], $v[$passwordField])) {
					return true;
				}

				return false;
			});

		return $user;
	}

	private function find($identifier) {
		$users = \Config::get('laravel-auth-file::users', array());
		$usernameField = \Config::get('auth.username', 'username');

		// Get the first matching user from the list
		$user = array_first($users,
			function ($k, $v) use($identifier, $usernameField) {
				if(array_key_exists($usernameField, $v) && $identifier === $v[$usernameField]) {
					return true;
				}

				return false;
			});

		return $user;
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param array $credentials
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByCredentials(array $credentials) {
		$user = $this->verify($credentials);
		if($user) {
			$model = $this->createModel($user);
			return $model;
		}

		return null;
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param \Illuminate\Auth\UserInterface $user
	 * @param array $credentials
	 * @return bool
	 */
	public function validateCredentials(UserInterface $user, array $credentials) {
		$user = $this->find($user->getAuthIdentifier());
		if($user) {
			$passwordField = \Config::get('auth.password', 'password');
			if(array_key_exists($passwordField, $user) && array_key_exists($passwordField, $credentials) &&
				 \Hash::check($credentials[$passwordField], $user[$passwordField])) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Create a new instance of the model.
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function createModel($attrs) {
		$class = '\\' . ltrim($this->model, '\\');
		return new $class($attrs);
	}
}