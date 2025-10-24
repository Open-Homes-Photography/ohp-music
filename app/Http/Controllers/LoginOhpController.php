<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LoginOhpController extends Controller
{
    public function __invoke(Request $request)
    {
        $intendedHash = $request->input('intended');
        $intendedUrl = config('app.url') . "/#" . $intendedHash;

        // if logged in then  redirect to intended hash
        if ($request->user()) {
            Session::forget('url.intended'); // clear intended url
            return redirect()->to($intendedUrl);
        }

        // if not logged in then redirect to Atrium's login page
        Redirect::setIntendedUrl($intendedUrl);

        return redirect()->guest(config('app.atrium_url'));
    }
}
