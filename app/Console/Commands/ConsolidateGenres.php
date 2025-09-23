<?php

namespace App\Console\Commands;

use App\Models\Song;
use Illuminate\Console\Command;

// use Illuminate\Support\Facades\Config;
// use Illuminate\Support\Facades\DB;

class ConsolidateGenres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:consolidate-genres';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consolidate list of genres into single one + set old genres as keywords';

    protected $sourceFile = 'genres.csv';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Config::set('database.connections.mysql.database', 'music-rehearsal'); // test database
        // DB::purge('mysql');
        // DB::reconnect('mysql');

        $handle = fopen($this->sourceFile, "r");

        if ($handle !== false) {
            fgetcsv($handle); // skip headers

            while (($data = fgetcsv($handle)) !== false) {
                $song = Song::find($data[0]);

                if (in_array($song->genre, ['Unknown', '', null], true)) {
                    continue;
                }

                $song->keywords = $this->getKeywords($data[3]);
                $song->genre = $data[4];

                // dump($song->keywords, $song->genre, $data[3]);
                $song->save();
            }

            fclose($handle);
        }
    }

    /**
     *
     * @return array<string>
     */
    protected function getKeywords($oldGenre): array
    {
        return collect(explode(',', $oldGenre))->map(static fn ($word) => strtolower(trim($word)))->filter()->all();
    }
}
