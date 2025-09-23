<?php

namespace App\Repositories;

use App\Enums\PlayableType;
use App\Models\Song;
use App\Models\User;
use App\Values\Keyword;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class KeywordRepository
{
    /** @return Collection|array<array-key, Keyword> */
    public function getAll(?User $scopedUser = null): Collection
    {
        return Song::query(type: PlayableType::SONG, user: $scopedUser ?? auth()->user())
            ->accessible()
            ->selectRaw(
                "COALESCE(jt.keyword, '" . Keyword::NO_KEYWORDS . "') AS keyword,
                 COUNT(DISTINCT songs.id) AS song_count,
                 SUM(songs.length) AS length"
            )->leftJoin(
                DB::raw("JSON_TABLE(keywords, '$[*]' COLUMNS (keyword VARCHAR(255) PATH '$')) AS jt"),
                static function ($join): void {
                    $join->on(DB::raw('1'), '=', DB::raw('1'));
                }
            )
            ->groupBy(DB::raw("COALESCE(jt.keyword, '" . Keyword::NO_KEYWORDS . "')"))
            ->orderByDesc('song_count')
            ->get()
            ->transform(static fn (object $record): Keyword => Keyword::make( // @phpstan-ignore-line
                name: $record->keyword ?: Keyword::NO_KEYWORDS, // @phpstan-ignore-line
                songCount: $record->song_count, // @phpstan-ignore-line
                length: $record->length // @phpstan-ignore-line
            ));
    }

    public function getOne(string $name, ?User $scopedUser = null): ?Keyword
    {
        $query = Song::query(type: PlayableType::SONG, user: $scopedUser ?? auth()->user())
            ->accessible()
            ->select(DB::raw('COUNT(songs.id) AS song_count'), DB::raw('SUM(songs.length) AS length'));

        if ($name === Keyword::NO_KEYWORDS) {
            $query->where(static function ($q): void {
                $q->where('keywords', '[]')->orWhereNull('keywords');
            });
        } else {
            $query->whereJsonContains('keywords', $name);
        }

        /** @var object|null $record */
        $record = $query->first();

        return $record
            ? Keyword::make(
                name: $name ?? Keyword::NO_KEYWORDS,
                songCount: $record->song_count,
                length: $record->length
            )
            : null;
    }
}
