<?php

use App\Facades\ITunes;
use App\Http\Controllers\AuthorizeDropboxController;
use App\Http\Controllers\Download\DownloadAlbumController;
use App\Http\Controllers\Download\DownloadArtistController;
use App\Http\Controllers\Download\DownloadFavoritesController;
use App\Http\Controllers\Download\DownloadPlaylistController;
use App\Http\Controllers\Download\DownloadSongsController;
use App\Http\Controllers\Download\SignedDownloadSongsController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LastfmController;
use App\Http\Controllers\LoginOhpController;
use App\Http\Controllers\PlayController;
use App\Http\Controllers\RegisterOhpController;
use App\Http\Controllers\SSO\GoogleCallbackController;
use App\Http\Controllers\ViewSongOnITunesController;
use App\Models\Song;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/login', static function () {
    return redirect(config('auth.redirect_to'));
})->name('login');

Route::get(
    'songs/{songId}/signed-download',
    SignedDownloadSongsController::class
)->where(['songId' => Song::ID_REGEX])->name('signed-song-download')->middleware('signed');

Route::middleware('web')->group(static function (): void {
    Route::get('/', IndexController::class)->name('home');

    Route::get('remote', static fn () => view('remote'));

    Route::get('/register-ohp', RegisterOhpController::class);

    Route::get('/login-ohp', LoginOhpController::class);

    Route::middleware('auth')->group(static function (): void {
        Route::prefix('lastfm')->group(static function (): void {
            Route::get('connect', [LastfmController::class, 'connect'])->name('lastfm.connect');
            Route::get('callback', [LastfmController::class, 'callback'])->name('lastfm.callback');
        });

        if (ITunes::used()) {
            Route::get('itunes/song/{album}', ViewSongOnITunesController::class)->name('iTunes.viewSong');
        }
    });

    Route::get('auth/google/redirect', static fn () => Socialite::driver('google')->redirect());
    Route::get('auth/google/callback', GoogleCallbackController::class);

    Route::get('dropbox/authorize', AuthorizeDropboxController::class)->name('dropbox.authorize');

    Route::middleware('audio.auth')->group(static function (): void {
        Route::get('play/{song}/{transcode?}', PlayController::class)->name('song.play');

        if (config('koel.download.allow')) {
            Route::prefix('download')->group(static function (): void {
                Route::get('songs', DownloadSongsController::class);
                Route::get('album/{album}', DownloadAlbumController::class);
                Route::get('artist/{artist}', DownloadArtistController::class);
                Route::get('playlist/{playlist}', DownloadPlaylistController::class);
                Route::get('favorites', DownloadFavoritesController::class);
            });
        }
    });
});
