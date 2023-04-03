<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dashboard = DB::table('comments', 'contact', 'events', 'items', 'sites', 'types', 'users')
            // ->join('users', 'users.id', '=', 'comments.user_id')
            // ->join('items', 'items.id', '=', 'comments.item_id')
            // ->join('users', 'users.id', '=', 'sites.user_id')
            // ->join('types', 'types.id', '=', 'sites.type_id')

            // ->select('comments.id', 'comments.titleComment', 'comments.contentComment', 'comments.created_at', 'comments.updated_at', 'users.firstName as firstName', 'users.lastName as lastName', 'items.titleItem as titleItem',  'types.nameType as nameType')

            // ->distinct()
            ->get()
            ->toArray();



        // On retourne les informations de la table commentaire en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $dashboard,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
