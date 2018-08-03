/*--------------
//Firebase Functions
--------------*/
//Require Dependancies. Placed here or undefined variable errors otherwise.
var firebase = require('firebase/app');
require('firebase/auth');

function login (){
	var userEmail = $('input[name=email]').val();
	var userPassword = $('input[name=password]').val();
	
	firebase.auth().signInWithEmailAndPassword(userEmail, userPassword).catch(function(error) {
	  // Handle Errors here.
	  	var errorCode = error.code;
	  	var errorMessage = error.message;
	  	// ...
		console.log('noboi');
	});
	
	firebase.auth().onAuthStateChanged(function(user) {
		if (user) {
			// User is signed in.
			firebase.auth().currentUser.getIdToken().then(function (response){
				$('input[name="tokenID"]').val(response);
				$('.login-form').submit();
				//console.log(response);
			});
			
	  } else {
		// No user is signed in.
		console.log('noboii');
	  }
	});
}

function logout(){
	firebase.auth().signOut().then(function (response){
		$('input[name="tokenID"]').val('logout');
		$('.logout-form').submit();
		//console.log($(response);
	});
}

$(document).ready(function(){//Initialize Event Listeners
	
	/*--------------
	//Custom Bootstrap Event Listeners
	--------------*/
	//$('.custom-dropdown').dropdown();
	
	$('.custom-dropdown').on('click', function(e) {
		e.stopPropagation();
	});
	
	$('#modalCarousel').carousel('pause');
	
	$('.nav-addNumber').on('click', function(){
		$('#modalCarousel').carousel(0);
		$('#modalCarousel').carousel('pause');
	});
	
	$('.ajax-edit-number').on('click', function(){
		$('#modalCarousel').carousel(2);
		$('#modalCarousel').carousel('pause');
	});
	
	/*--------------
	//Firebase Client-side authentication
	--------------*/
	// Initialize Firebase
	var config = {
		apiKey: process.env.MIX_FIREBASE_API,
		authDomain: process.env.MIX_FIREBASE_AUTH_DOMAIN,
		databaseURL: process.env.MIX_FIREBASE_DATABASE_URI,
		projectId: process.env.MIX_FIREBASE_PROJECT_ID,
		storageBucket: process.env.MIX_FIREBASE_STORAGE_BUCKET,
		messagingSenderId: process.env.MIX_FIREBASE_MESSAGING_SENDER_ID
	};
	firebase.initializeApp(config);
	
	$('.login').click(function(){
		login();
	});
	
	$('.logout').click(function(){
		logout();
	});
});