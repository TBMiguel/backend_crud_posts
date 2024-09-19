<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentsResource;
use App\Models\Comments;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CommentsController extends Controller
{
    public function index($post)
    {
        $this->validatePost($post);

        $post   = Post::query()->find($post);

        if (! $post) {
            return response()->json([
                'message' => 'Post não encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        $comments = $post->comments()->paginate(5);

        if ($comments->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum comentário encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        $comments->getCollection()->transform(function ($comment) {
            return CommentsResource::make($comment);
        });

        return response()->json($comments);
    }

    public function store(Request $request, $post)
    {
        $request->validate([
            'conteudo' => 'required|max:500',
        ]);

        $this->validatePost($post);

        $post   = Post::query()->find($post);

        if (! $post) {
            return response()->json([
                'message' => 'Post não encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        $comment = $post->comments()->create([
            'conteudo' => $request->conteudo,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Comentário criado com sucesso',
            'commentário' => CommentsResource::make($comment),
        ]);
    }

    public function show($post, $comment)
    {
        $this->validatePost($post);
        $this->validateComment($comment);

        $post   = Post::query()->find($post);

        if (! $post) {
            return response()->json([
                'message' => 'Post não encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        $comment       = $post->comments()->find($comment);

        if (! $comment) {
            return response()->json([
                'message' => 'Comentário foi apagado ou não existe',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'commentário' => CommentsResource::make($comment),
        ]);

    }

    public function update(Request $request, $post, $comment)
    {
        $this->validatePost($post);
        $this->validateComment($comment);

        $post   = Post::query()->find($post);

        if (! $post) {
            return response()->json([
                'message' => 'Post não encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        $comment    = $post->comments()->find($comment);

        if (! $comment) {
            return response()->json([
                'message' => 'Comentário foi apagado ou não existe',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($request->user()->id !== $comment->user_id) {
            return response()->json([
                'message' => 'Você não tem permissão para editar este comentário!',
            ], Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'conteudo' => 'required|max:500',
        ]);

        $comment->update($request->only('conteudo'));

        return response()->json([
            'message' => 'Comentário atualizado com sucesso',
            'commentário' => CommentsResource::make($comment),
        ]);
    }

    public function destroy(Request $request, $post, $comment)
    {
        $this->validatePost($post);
        $this->validateComment($comment);

        $post         = Post::query()->find($post);

        if (! $post) {
            return response()->json([
                'message' => 'Post não encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        $comment      = $post->comments()->find($comment);

        if (! $comment) {
            return response()->json([
                'message' => 'Comentário foi apagado ou não existe',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($request->user()->id !== $comment->user_id) {
            return response()->json([
                'message' => 'Você não tem permissão para deletar este comentário!',
            ], Response::HTTP_FORBIDDEN);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comentário apagado com sucesso',
        ]);
    }

    public function validatePost($post)
    {
        $rules = [
            'post' => 'required|int|exists:posts,id',
        ];

        if (Validator::make(['post' => $post], [$rules])->fails()) {
            return response()->json([
                'message' => 'Post não encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        return true;
    }

    public function validateComment($comment)
    {
        $rules = [
            'comment' => 'required|int|exists:comments,id',
        ];

        if (Validator::make(['comment' => $comment], [$rules])->fails()) {
            return response()->json([
                'message' => 'Comentário já foi apagado ou não existe',
            ], Response::HTTP_NOT_FOUND);
        }

        return true;
    }
}
