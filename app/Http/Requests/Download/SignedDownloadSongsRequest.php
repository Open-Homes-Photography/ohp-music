<?php

namespace App\Http\Requests\Download;

/**
 * @property array $songs
 */
class SignedDownloadSongsRequest extends Request
{
    /** @return array<mixed> */
    public function rules(): array
    {
        return [
            // 'songs' => 'required|array',
        ];
    }
}
