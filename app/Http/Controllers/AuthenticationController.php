<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;

/**
* 
*/
class AuthenticationController extends Controller
{
	
	function __construct()
	{

	}

	/**
     * Try loggin in user with 
     * 
     * @return void
     */
	public function login(Request $request) {
		$this->validate($request, [
			'email' => 'required',
			'password' => 'required',
		]);

		$user = User::where('email', $request->input('email'))->first();

		if(Hash::check($request->input('password'),$user->password)) {
			$session_token = base64_encode(bin2hex(openssl_random_pseudo_bytes(48)));

			User::where('email', $request->input('email'))->update(['api_token' => $session_token]);

			return response()->json([
				'data'	=>	[
					'id'	=>	$user->id,
					'email' => $user->email, 
					'token' => $session_token,
				],
			]);
		} else {
			return response()->json(['status' => 'fail'], 401);
		}
	}

}