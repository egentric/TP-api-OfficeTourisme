<?php

namespace App\Http\Controllers\API;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère tous les éléments de la table commentaire
        $comments = DB::table('comments')
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->join('items', 'items.id', '=', 'comments.item_id')
            ->select('comments.id', 'comments.titleComment', 'comments.contentComment', 'comments.created_at', 'comments.updated_at', 'users.firstName as firstName', 'users.lastName as lastName', 'items.titleItem as titleItem')
            // ->distinct()
            ->get()
            ->toArray();
        // On retourne les informations de la table commentaire en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $comments,
        ]);
    }
    public function indexComUser($id)
    {
        // On récupère tous les éléments de la table commentaire
        $comments = DB::table('comments')
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->join('items', 'items.id', '=', 'comments.item_id')
            ->select('comments.id', 'comments.titleComment', 'comments.contentComment', 'comments.created_at', 'comments.updated_at', 'users.firstName as firstName', 'users.lastName as lastName', 'items.titleItem as titleItem')
            ->where('users.id', '=', $id)
            // ->distinct()
            ->get()
            ->toArray();
        // On retourne les informations de la table commentaire en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $comments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titleComment' => 'required|max:100',
            'contentComment' => 'required',
        ]);
        // On crée un nouveau commentaire
        $comment = Comment::create([
            'titleComment' => $request->titleComment,
            'contentComment' => $request->contentComment,
            'user_id' => $request->user_id,
            'item_id' => $request->item_id,

        ]);
        // On retourne les informations du nouveau commentaire en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $comment,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        $comment = DB::table('comments')
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->join('items', 'items.id', '=', 'comments.item_id')
            ->select('comments.id', 'comments.titleComment', 'comments.contentComment', 'comments.created_at', 'comments.updated_at', 'users.firstName as firstName', 'users.lastName as lastName', 'items.titleItem as titleItem')
            ->where('comments.id', $comment->id)
            // ->distinct()
            ->get();

        // On retourne les informations du commentaire en JSON
        return response()->json($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->validate($request, [
            'titleComment' => 'required|max:100',
            'contentComment' => 'required|max:100',
        ]);
        // On modifie le Commentaire
        $comment->update([
            'titleComment' => $request->titleComment,
            'contentComment' => $request->contentComment,
            'user_id' => $request->user_id,
            'item_id' => $request->item_id,

        ]);

        // On retourne les informations du Commentaire en JSON
        return response()->json([
            'status' => 'Mise à jour avec succèss'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // On supprime le commentaire
        $comment->delete();
        // On retourne la réponse JSON
        return response()->json([
            'status' => 'Supprimer avec succès'
        ]);
    }
}
