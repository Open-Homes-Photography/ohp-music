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
        $idKey = (auth('ohp-auth')->getName()); // @phpcs:ignore
        $userId = $request->session()->get($idKey ?? '');

        if (!$userId) {
            abort(404);
        }

        $user = User::find($userId);

        // if it doesnt exists then create it with same role
        if (!$user) {
            // first, get data from Atrium database (name, email and role)
            $userData = DB::connection('atrium')
                ->table('user')
                ->where('id', $userId)
                ->select(['firstname', 'lastname', 'email', 'role'])
                ->first();

            // create user with retrieved data
            $user = User::create([
                'name' => trim(join(' ', [$userData->firstname, $userData->lastname])),
                'email' => $userData->email,
                'preferences' => $this->getDefaultPreferences(),
                'password' => Hash::make(bin2hex(random_bytes(8))), // random password since won't be used in this app
                'is_admin' => $userData->role === 40 || $userData->role === 60,
            ]);

            // ensure id matches the one from Atrium
            $user->id = $userId;

            $user->save();
        }

        // then redirect to dashboard
        return redirect('/');
    }

    private function getDefaultPreferences()
    {
        return [
            "theme" => "ohp",
            "show_now_playing_notification" => true,
            "confirm_before_closing" => false,
            "show_album_art_overlay" => true,
            "transcode_on_mobile" => true,
            "transcode_quality" => 128,
            "make_uploads_public" => false,
            "lastfm_session_key" => null,
            "support_bar_no_bugging" => false,
            "artists_view_mode" => "thumbnails",
            "albums_view_mode" => "thumbnails",
            "repeat_mode" => "NO_REPEAT",
            "volume" => 7,
            "equalizer" => [
                "name" => "Default",
                "preamp" => 0,
                "gains" => [
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                ],
            ],
            "lyrics_zoom_level" => 1,
            "visualizer" => "default",
            "active_extra_panel_tab" => null,
            "continuous_playback" => false,
        ];
    }
}
