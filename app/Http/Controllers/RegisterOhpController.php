<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterOhpController extends Controller
{
    public function __invoke(Request $request)
    {
        // check if incoming user's id exists in DB
        $session = $request->session()->all();
        $idKey = (auth('ohp-auth')->getName()); // @phpcs:ignore

        if (!$session || !array_key_exists($idKey, $session)) {
            abort(404);
        }

        $userId = $session[$idKey];

        $user = User::where('id', $userId)->first();

        // if it doesnt exists then create it with same role
        if (!$user) {
            // first, get data from atrium database (name, email and role)
            $userData = DB::connection('atrium')
                ->table('user')
                ->where('id', $userId)
                ->select(['firstname', 'lastname', 'email', 'role'])
                ->firstOrFail();
                //->first();

            // create user with retrieved data. TODO: see how best to set ohp default theme
            $user = User::create([
                'id' => $userId,
                'name' => trim(join(' ', [$userData->firstname, $userData->lastname])),
                'email' => $userData->email,
                'password' => Hash::make(bin2hex(random_bytes(8))),
                'is_admin' => $userData->role === 40 || $userData->role === 60,
            ]);
            $user->id = $userId;
            $user->save();
        }

        // then redirect to dashboard
        return redirect('/');
    }
}
