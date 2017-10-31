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
			$results['user'] = $user;
		}else{
			$results['errors'] = $user;
		}
		return $results;
	}
	public function getUserAction(){

	}
}