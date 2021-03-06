<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;

/**
* 
*/
class UserController extends Controller
{

	function __construct()
	{
		
	}

	/**
     * Try loggin in user with 
     * 
     * @return void
     */
	public function authenticate(Request $request) {
		$this->validate($request, [
			'email' => 'required',
			'password' => 'required',
		]);

		$user = User::where('email', $request->input('email'))->first();

		if(Hash::check($request->input('password'),$user->password)) {
			$session_token = base64_encode(bin2hex(openssl_random_pseudo_bytes(48)));

			User::where('email', $request->input('email'))->update(['api_token' => $session_token]);

			return response()->json(['status' => 'success', 'api_token' => $session_token]);
		} else {
			return response()->json(['status' => 'fail'], 401);
		}
	}

	/**
     * Create a new user record on database based on the user details passed.
     * 
     * @return void
     */
	public function create(Request $request) {

		$email = $request->input('email');
		$password = $request->input('password');

		$firstName = $request->input('first-name');
		$lastName = $request->input('last-name');

		$lastUser = new User();
		$lastUser->first_name = $firstName;
		$lastUser->last_name = $lastName;
		$lastUser->email = $email;
		$lastUser->password = $this->generateHashedPassword($password);

		try {		// SQL will throw exceptions in some cases
			
			if( $lastUser->save() ) {	//	Tries to insert the object values in database
				// Success
				return response()->json([
					'message' 	=> 'success',
					'user-detail'	=> [
						'first-name'	=>	$firstName,
						'last-name'		=> 	$lastName,
						'email'			=>	$email,
					],
				]);
			}

		} catch(\Illuminate\Database\QueryException $e) {

			$errorCode = $e->errorInfo[1];
			if($errorCode == '1062') { // Duplicate unique field, Possible [ email , phone numbder ]
				return response()->json([
					'errors'	=>	[
						'status'	=>	6512,
						'title'		=>	'Duplicate Email',
						'detail'	=>	'Email is already registered. Please try logging in or try resetting password.',					
					],
				]);
			}

			// If exception is thrown but doesn't match any of the listed SQL exceptions then
			// there is problem in database connection. Check lumen log for more details.
			return response()->json([
				'errors'	=>	[
					'status'	=>	5511,
					'title'	=>	'SQL Timeout',
					'summary'	=> 	'Connection to database could not be made.',
				],
			]);

		}
	}

	/**
     * Generates bcrypt hash utilizing the <code>Hash</code> class of Laravel.
     * 
     * @return string 	-		raw password's hash
     */
	private function generateHashedPassword( $rawPassword ) {
		return Hash::make( $rawPassword );
	}


}