<?php namespace App\Http\Controllers;

use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Http\Request;

class AuthController extends Controller {

	public function loginWithGoogle(Request $request)
	{
	    // get data from request
	    $code = $request->get('code');

	    // get google service
	    //$googleService = \OAuth::consumer('Google', 'http://localhost:8000/login');
	    $googleService = \OAuth::consumer('Google', 'http://mobiledev.rmacrao.org/login');

	    // check if code is valid

	    // if code is provided get user data and sign in
	    if ( ! is_null($code))
	    {
	        // This was a callback request from google, get the token
	        $token = $googleService->requestAccessToken($code);

	        // Send a request with it
	        $result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);

	        $user = User::where('email','=',$result['email'])->first();

	        if ($user) {
		        Auth::login($user);
		    } else {
		    	//not a valid user
		    	return redirect('/');
		    }

	        // Login User
	        if (Auth::check()){
	        	return redirect('/app');
	        } else {
	        	//not a valid user
	        	return redirect('/');
	        }

	        
	    }
	    // if not ask for permission first
	    else
	    {
	        // get googleService authorization
	        $url = $googleService->getAuthorizationUri();

	        // return to google login url
	        return redirect((string)$url);
	    }
	}

}
