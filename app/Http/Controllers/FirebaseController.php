<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\User;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Auth;

class FirebaseController extends Controller
{	
	public function login(Request $request){
		if($request->input('tokenID') != null){
			if($request->input('tokenID') == 'logout'){
				$request->session()->forget('token');
				return response()->view('firebase.index',['uid' => null, 'token' => null]);
			} else {
				$token = $request->input('tokenID');
			}
		} else {
			$token = session('token');
		}
		
		if(!$token){
			return response()->view('firebase.index',['uid' => null, 'token' => null]);
		} else {
			$uid = FirebaseController::verifyToken($token);
			session(['token' => $token]);
			
			if(User::where('firebase_id', '=', $uid)->exists()){
				return redirect()->action('LeadController@dashboard');
			} else {
				$user = new User();
				$user->firebase_id = $uid;
				
				$user->save();
				
				return redirect()->action('LeadController@dashboard');
			}
		}
	}
	
	public static function verifyToken($token){
		$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/'.env("FIREBASE_JSON"));

		$firebase = (new Factory)
			->withServiceAccount($serviceAccount)
			->create();
		$auth = $firebase->getAuth();
		
		$idTokenString = $token;

		try {
			$verifiedIdToken = $firebase->getAuth()->verifyIdToken($idTokenString);
		} catch (InvalidToken $e) {
			return response()->view('firebase.index');
		}

		$uid = $verifiedIdToken->getClaim('sub');
		
		return $uid;
	}
}	
?>