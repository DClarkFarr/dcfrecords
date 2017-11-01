<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller, App\Models\User;

class UserController extends Controller {

	public function userLoginAction(){
		$username = \Request::input('username');
		$password = \Request::input('password');
		
		$errors = [];
		list($status, $user) = User::validateLogin($username, $password);
		$results = ['status' => $status ? 'success': 'failed'];
		if($status){
			$user->last_login = date('Y-m-d H:i:s');
			$user->save();
			$results['user'] = array_intersect_key($user->toArray(), array_flip(['first_name', 'last_name', 'email', 'user_guid', 'username', 'permission', 'last_login']));
		}else{
			$results['errors'] = $user;
		}
		return $results;
	}
	public function getUserAction(){
		$user_guid = \Request::input('user_guid');

		$user = User::where('user_guid', $user_guid)->first();

		$results = ['status' => !empty($user) ? 'success': 'failed'];
		if(!empty($user)){
			$results['user'] = array_intersect_key($user->toArray(), array_flip(['first_name', 'last_name', 'email', 'user_guid', 'username', 'permission', 'last_login']));
		}else{
			$results['message'] = 'User Not Found';
		}

		return $results;
	}
	public function usernameAction(){
		$first_name = \Request::input('first_name');
		$last_name = \Request::input('last_name');

		list($status, $username) = User::makeUsername($first_name, $last_name);

		$res = ['status' => $status ? 'success' : 'failed'];
		if($status){
			$res['username'] = $username;
		}else{
			$res['message'] = $username;
		}
		return $res;
	}
	public function usernameEmailAction(){
		$first_name = \Request::input('first_name');
		$last_name = \Request::input('last_name');
		$email = \Request::input('email');

		list($u_status, $username) = User::makeUsername($first_name, $last_name);

		$e_status = User::isValidEmail($email);

		$errors = [];
		if(!$u_status){
			$errors[] = $username;
			$username = "";
		}
		if(!$e_status){
			$errors[] = 'Email Address Invalid';
		}

		return ['status' => empty($errors) ? 'success' : 'failed', 'username' => $username, 'errors' => $errors];

	}
	public function usernameAvailableAction(){
		$username = \Request::input('username');

		if(!$username || strlen($username) < 5){
			return ['status' => 'failed', 'message' => 'Username Must be at least 5 Characters'];
		}

		if(!User::isValidUsername($username)){
			return ['status' => 'failed', 'message' => 'Username must contain only letters and numbers'];
		}

		$user = User::where('username', $username)->first();

		if(!empty($user)){
			return ['status' => 'failed', 'message' => 'Username Already Exists'];
		}

		return ['status' => 'success'];
	}

	public function createUserAction(){
		$username = \Request::input('username');
		$first_name = \Request::input('first_name');
		$last_name = \Request::input('last_name');
		$email = \Request::input('email');
		$password = \Request::input('password');
		$confirm_password = \Request::input('confirm_password');

		$errors = [];

		if(!( $first_name && $last_name )){
			$errors[] = 'First and Last names required';
		}
		if( !User::isValidEmail($email) ){
			$errors[] = "Email Invalid";
		}
		if(!$password){
			$errors[] = 'Password is required';
		}else if(!User::isValidPassword($password)){
			$errors[] = 'Password must be at least 5 Characters';
		}

		if($password != $confirm_password){
			$errors[] = "Password and Confirm Password did not Match";
		}

		$user_guid = false;
		$user_data = [];
		if(empty($errors)){
			$password = \Hash::make($password);
			$user_guid = \Hash::make(microtime(1) . rand());

			$user = new User;
			$user->fill([
				'first_name' => $first_name,
				'last_name' => $last_name,
				'email' => $email,
				'username' => $username,
				'password' => $password,
				'user_guid' => $user_guid,
			]);
			$user->save();
			$user_data = array_intersect_key($user->toArray(), array_flip(['first_name', 'last_name', 'email', 'user_guid', 'username', 'permission', 'last_login']));
		}
		return ['status' => empty($errors) ? 'success' : 'failed', 'user' => $user_data, 'errors' => $errors];
	}
}