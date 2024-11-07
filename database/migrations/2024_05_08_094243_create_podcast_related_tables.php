<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('podcasts', static function (Blueprint $table): void {
            $table->string('id', 36)->primary();
            $table->string('url')->unique()->comment('The URL to the podcast feed')->unique();
            $table->string('link')->comment('The link to the podcast website');
            $table->text('title');
            $table->text('image');
            $table->string('author')->nullable();
            $table->text('description');
            $table->json('categories');
            $table->boolean('explicit');
            $table->string('language');
            $table->json('metadata');
            $table->uuid('added_by')->nullable();
            $table->timestamp('last_synced_at');
            $table->timestamps();
        });

        Schema::table('podcasts', static function (Blueprint $table): void {
            $table->foreign('added_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('songs', static function (Blueprint $table): void {
            $table->unsignedInteger('artist_id')->nullable()->change();
            $table->unsignedInteger('album_id')->nullable()->change();
            $table->uuid('owner_id')->nullable()->change();
            $table->string('podcast_id', 36)->nullable();
            $table->string('episode_guid')->nullable()->unique();
            $table->json('episode_metadata')->nullable();

            $table->foreign('podcast_id')->references('id')->on('podcasts')->cascadeOnDelete();
        });

        Schema::create('podcast_user', static function (Blueprint $table): void {
            $table->id();
            $table->uuid('user_id');
            $table->string('podcast_id', 36);
            $table->json('state')->nullable();
            $table->timestamps();
        });

        Schema::table('podcast_user', static function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('podcast_id')->references('id')->on('podcasts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('songs', static function (Blueprint $table): void {
            $table->dropForeign('songs_podcast_id_foreign');
        });
        Schema::drop('podcast_user');
        Schema::drop('podcasts');
    }
};
