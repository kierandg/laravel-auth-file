<?php

/**
 * @author     Gponster <anhvudg@gmail.com>
 * @copyright  Copyright (c) 2014
 */
namespace Gponster\Auth\File;

use \Illuminate\Auth\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Session\Store as SessionStore;

class Guard extends \Illuminate\Auth\Guard {

	/**
	 * Attempt to authenticate a user using the given credentials.
	 *
	 * @param array $credentials
	 * @param bool $remember
	 * @param bool $login
	 * @return bool
	 */
	public function attempt(array $credentials = [], $remember = false, $login = true) {
		$this->fireAttemptEvent($credentials, $remember, $login);
		$this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

		// If an implementation of UserInterface was returned, we'll ask the provider
		// to validate the user against the given credentials, and if they are in
		// fact valid we'll log the users into the application and return true.
		if($user instanceof UserInterface) {
			/**
			 * No necessary to validate credentials
			 * <code>
			 * if ($this->provider->validateCredentials($user, $credentials)) {
			 * if ($login) {
			 * $this->login($user, $remember);
			 * }
			 * return true;
			 * }
			 * </code>
			 */
			if($login) {
				$this->login($user, $remember);
			}

			return true;
		}

		return false;
	}

	/**
	 * Log the user out of the application.
	 *
	 * @return void
	 */
	public function logout() {
		parent::logout();
	}
}
