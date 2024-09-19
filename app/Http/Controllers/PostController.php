<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostsResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    protected $rules = [
        'titulo' => 'required|max:255',
        'conteudo' => 'required|max:2500',
    ];

    public function index(Request $request)
    {
        if (request()->has('data_inicio') && request()->has('data_fim')) {
            $dataInicio = $request->query('data_inicio');
            $dataFim    = $request->query('data_fim');

            $posts = Post::query()->whereBetween('created_at', [$dataInicio, $dataFim])->paginate(5);

            if ($posts->isEmpty()) {
                return response()->json(['message' => 'Nenhum post encontrado'], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'posts' => $posts->getCollection()->transform(function ($post) {
                    return PostsResource::make($post);
                }),
            ]);
        }

        $posts = Post::paginate(5);

        if ($posts->isEmpty()) {
            return response()->json(['message' => 'Nenhum post encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'posts' => $posts->getCollection()->transform(function ($post) {
                return PostsResource::make($post);
            }),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules);

        $post = $request->user()->posts()->create($request->only('titulo', 'conteudo'));

        return response()->json([
            'message' => 'Post criado com sucesso',
            'post' => PostsResource::make($post),
        ]);
    }

    public function show($post)
    {
        $post = Post::find($post);

        if (! $post) {
            return response()->json([
                'message' => 'Post não encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'post' => PostsResource::make($post),
        ]);
    }

    public function update(Request $request, $post)
    {
        $post = Post::find($post);

        if (! $post) {
            return response()->json([
                'message' => 'Post não encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($request->user()->id !== $post->user_id) {
            return response()->json([
                'message' => 'Você não tem permissão para editar este post!',
            ], Response::HTTP_FORBIDDEN);
        }

        $request->validate($this->rules);

        $post->update($request->only('titulo', 'conteudo'));

        return response()->json([
            'message' => 'Post atualizado com sucesso!',
            'post' => PostsResource::make($post),
        ]);
    }

    public function destroy(Request $request, $post)
    {
        $post = Post::find($post);

        if (! $post) {
            return response()->json([
                'message' => 'Post não encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($request->user()->id !== $post->user_id) {
            return response()->json([
                'message' => 'Você não tem permissão para deletar este post!',
            ], Response::HTTP_FORBIDDEN);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deletado com sucesso!',
        ]);
    }
}
