<?php
namespace App\Models;

class User Extends Model {
	public $timestamps = true;
	protected $primaryKey = 'id_user';
	protected $table = 'user';

	public $fillable = ['username', 'password', 'user_guid', 'first_name', 'last_name', 'email', 'permission', 'deleted', 'date_login'];

	protected $hidden = ['id_user', 'password'];
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
		])->first();

		if(empty($user)){
			$errors[] = 'Username/Password not found';
			return [false, $errors];
		}

		if(!\Hash::check($password, $user->password)){
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
		return preg_match('@^[A-Za-z0-9]{5,}$@', $username);
	}
	public static function isValidPassword($password){
		return preg_match('@.{5,}@', trim($password));
	}
	public static function isValidEmail($email){
		return trim($email) != '' && filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public static function makeUsername($first_name, $last_name){
		if(!($first_name && $last_name)){
			return [false, 'First name and Last Name required'];
		}
		$methods = [
			function($first_name, $last_name){
				return ucfirst(strtolower($first_name));
			},
			function($first_name, $last_name){
				return ucfirst(strtolower($first_name)) . ucfirst(strtolower($last_name[0]));
			},
			function($first_name, $last_name){
				return ucfirst(strtolower($first_name)) . ucfirst(strtolower($last_name));
			},
		];
		$incUsername = function($username){
			$current_num = '';

			$arr = array_reverse(str_split($username));
			$found = 0;
			foreach($arr as $char){
				if(is_numeric($char)){
					$current_num = $char . $current_num;
					$found++;
				}
			}
			if(!$current_num){
				$current_num = 1;
			}else{
				$current_num = intval($current_num);
				$current_num += 1;
			}

			if($current_num < 10){
				$current_num = '0' . ( (string) $current_num);
			}

			return ($found ? substr($username, 0, - $found) : $username) . $current_num;
		};


		do {
			if( !empty($methods) ){
				$func = array_shift($methods);
				$username = $func($first_name, $last_name);	
			}else{
				$username = $incUsername($username);
			}
			$user = self::where('username', $username)->first();
		} while ( !empty($user) );

		return [true, $username];
	}

	public function resetPassword(){
		$password = substr(md5( time() ), 10, 10);

		$this->password = \Hash::make($password);
		$this->save();

		$user = $this;
		$res = \Mail::send('emails.reset-password', ['user' => $user, 'password' => $password], function ($m) use ($user) {
            $m->from('no-reply@dcfrecords.com', 'DCF Records');

            $m->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Temporary Password');
        });
        return $res;
	}

	public static function authViaGuid(){
		$user_guid = \Request::input('user_guid');
		if(!$user_guid){
			return false;
		}

		$user = self::where('user_guid', $user_guid)->where('deleted', 0)->first();

		return !empty($user) ? $user : false;
	}
}