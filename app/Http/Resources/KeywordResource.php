<?php

namespace App\Http\Resources;

use App\Values\Keyword;
use Illuminate\Http\Resources\Json\JsonResource;

class KeywordResource extends JsonResource
{
    public const JSON_STRUCTURE = [
        'type',
        'name',
        'song_count',
        'length',
    ];

    public function __construct(private readonly Keyword $keyword)
    {
        parent::__construct($keyword);
    }

    /** @return array<mixed> */
    public function toArray($request): array
    {
        return [
            'type' => 'keywords',
            'name' => $this->keyword->name,
            'song_count' => $this->keyword->songCount,
            'length' => $this->keyword->length,
        ];
    }
}
