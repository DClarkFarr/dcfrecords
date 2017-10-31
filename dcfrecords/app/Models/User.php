<?php
namespace App\Models;

class User Extends Model {
	public $timestamps = true;
	protected $primaryKey = 'id_user';
	protected $table = 'user';

	public $fillable = ['username', 'password', 'first_name', 'last_name', 'email', 'permission', 'deleted', 'date_login'];

	//custom functions
	public static function validateLogin($username, $password){
		$errors = [];
		if(!self::isValidUsername($username)){
			$errors[] = "Username Invalid";
		}
		if(!self::isValidPassword($password)){
			$errors[] = "Password Invalid";
		}

		if(!empty($errors)){
			return [false, $errors];
		}

		$user = self::where([
			'username' => $username,
			'password' => $password,
		])->first();

		if(empty($user)){
			$errors[] = 'Username/Password not found';
			return [false, $errors];
		}

		if($user->deleted){
			$errors[] = 'Account Inactive';
			return [false, $errors];
		}

		return [true, $user];
	}
	public static function isValidUsername($username){
		return preg_match('@[A-Za-z0-9]{5,}@', $username);
	}
	public static function isValidPassword($password){
		return preg_match('@.{5,}@', trim($password));
	}
}