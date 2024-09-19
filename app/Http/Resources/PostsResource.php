<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'criador'           => Str::headline($this->user->name),
            'titulo'            => $this->titulo,
            'conteudo'          => $this->conteudo,
            'data_publicacao'   => $this->created_at->format('d/m/Y'),
        ];
    }
}
