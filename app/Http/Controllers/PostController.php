<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostsResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(10);

        $posts->getCollection()->transform(function ($post) {
            return PostsResource::make($post);
        });

        return response()->json([
            'posts' => $posts,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'conteudo' => 'required|max:2500',
        ]);

        $post = $request->user()->posts()->create($request->only('titulo', 'conteudo'));

        return response()->json([
            'message' => 'Post criado com sucesso',
            'post' => $post,
        ]);
    }

    public function show(Post $post)
    {
        return response()->json([
            'post' => PostsResource::make($post),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        if ($request->user()->id !== $post->user_id) {
            return response()->json([
                'message' => 'Você não tem permissão para editar este post!',
            ], Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'titulo' => 'required|max:255',
            'conteudo' => 'required|max:2500',
        ]);

        $post->update($request->only('titulo', 'conteudo'));

        return response()->json([
            'message' => 'Post atualizado com sucesso!',
            'post' => PostsResource::make($post),
        ]);
    }

    public function destroy(Request $request, Post $post)
    {
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
