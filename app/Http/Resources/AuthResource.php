<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * The expiration time of the token in hours
     */
    private const TOKEN_EXPIRATION_TIME = 2;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $token = $request->user()->createToken('authToken');

        return [
            'token'             => $token->plainTextToken,
            'token_type'        => 'Bearer',
            'token_abilities'   => $token->accessToken->abilities,
        ];
    }
}
