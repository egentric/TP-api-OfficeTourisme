<?php

namespace App\Http\Controllers\API;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère tous les éléments de la table type
        $types = DB::table('types')
            ->get()
            ->toArray();
        // On retourne les informations de la table type en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $types,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nameType' => 'required|max:100',
        ]);
        // On crée un nouveau type
        $type = Type::create([
            'nameType' => $request->nameType,
        ]);
        // On retourne les informations du nouveau type en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $type,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(type $type)
    {
        // On retourne les informations du type en JSON
        return response()->json($type);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, type $type)
    {
        $this->validate($request, [
            'nameType' => 'required|max:100',
        ]);
        // On modifie le type
        $type->update([
            'nameType' => $request->nameType,
        ]);

        // On retourne les informations du type en JSON
        return response()->json([
            'status' => 'Mise à jour avec succèss'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(type $type)
    {
        // On supprime le commentaire
        $type->delete();
        // On retourne la réponse JSON
        return response()->json([
            'status' => 'Supprimer avec succès'
        ]);
    }
}
