<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Kreait\Firebase\Auth;
use Firebase\Auth\Token\Exception\InvalidToken;

class FirebaseController extends Controller
{
	public function index(){

		$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/yello-d136a-85673659caee.json');
		$firebase = (new Factory)
		->withServiceAccount($serviceAccount)
		->withDatabaseUri('https://yello-d136a.firebaseio.com/')
		->create();

		$database = $firebase->getDatabase();
		$newPost = $database
		->getReference('blog/posts')
		->push([
			'title' => 'Post title',
			'body' => 'This should probably be longer.'
		]);

		//$newPost->getKey(); // => -KVr5eu8gcTv7_AHb-3-
		//$newPost->getUri(); // => https://my-project.firebaseio.com/blog/posts/-KVr5eu8gcTv7_AHb-3-
		//$newPost->getChild('title')->set('Changed post title');
		//$newPost->getValue(); // Fetches the data from the realtime database
		//$newPost->remove();

		echo"<pre>";

		print_r($newPost->getvalue());
	}
	
	public function login(Request $request){
		$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/yello-d136a-85673659caee.json');

		$firebase = (new Factory)
			->withServiceAccount($serviceAccount)
			->create();
		
		$idTokenString = '..';
		
		try {
			$verifiedIdToken = $firebase->getAuth()->verifyIdToken($idTokenString);
		} catch (InvalidToken $e) {
			echo $e->getMessage();
		}

		$uid = $verifiedIdToken->getClaim('sub');
		$user = $firebase->getAuth()->getUser($uid);

			
		
		return response()->view('firebase.index');
	}
}	
?>