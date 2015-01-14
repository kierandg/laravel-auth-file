# Laravel File-Based DB Auth #

- **Laravel**: 4.2
- **Author**: Gponster

This package is not a replacement for laravels default Auth library, it's a simple auth driver 
using configuration file-based DB for username/password.

## Installation ##

Firstly you want to include this package in your composer.json file.

    "require": {
    		"gponster/laravel-auth-file": "dev-master"
    }
    
Now you'll want to update or install via composer.

    composer update

Next you open up app/config/app.php and replace the AuthServiceProvider with

    'Gonster\Auth\File\AuthServiceProvider',

**NOTE** It is very important that you replace the default service providers. 
If you do not wish to use Reminders, then remove the original Reminder server provider as it will cause errors.

Configuration is pretty easy too, take app/config/auth.php with its default values:

    return array(

		'driver' => 'eloquent',

		'model' => 'User',

		'table' => 'users',

		'reminder' => array(

			'email' => 'emails.auth.reminder',

			'table' => 'password_reminders',

			'expire' => 60,

		),

	);
and replace the auth driver to 'file'

	'driver' => 'file',
	'model' => 'AuthUser',
	'username' => 'email',
	'password' => 'password',
	
The simple AuthUser class

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\GenericUser;

class AuthUser extends GenericUser implements UserInterface, RemindableInterface {

	public static $rules = array(
	   'email'=>'required|email',
	   'password'=>'required|alpha_num|between:6,12|confirmed',
   ); 

	/**
	 * Dynamically access the user's attributes.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function getAttribute($key)
	{
		return $this->__get($key);
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->attributes['email'];
	}

	/**
	 * Get the password for the user, needs to return the hashed password
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->attributes['password'];
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->attributes['email'];
	}
}