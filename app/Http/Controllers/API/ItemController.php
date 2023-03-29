<?php

namespace App\Http\Controllers\API;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère tous les éléments de la table article
        $items = DB::table('items')
            ->join('users', 'users.id', '=', 'items.user_id')
            ->select('items.*', 'users.firstName as firstName', 'users.lastName as lastName')
            // ->distinct()
            ->get()
            ->toArray();
        // On retourne les informations de la table article en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $items,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titleItem' => 'required|max:100',
            'subtitleItem' => 'required|max:255',
            'contentItem' => 'required',
            'pictureItem' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $filename = "";
        if ($request->hasFile('pictureItem')) {

            // On récupère le nom du fichier avec son extension, résultat $filenameWithExt : "jeanmiche.jpg"
            $filenameWithExt = $request->file('pictureItem')->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // On récupère l'extension du fichier, résultat $extension : ".jpg"
            $extension = $request->file('pictureItem')->getClientOriginalExtension();

            // On créer un nouveau fichier avec le nom + une date + l'extension, résultat $fileNameToStore : "jeanmiche_20220422.jpg"
            $filename = $filenameWithoutExt . '_' . time() . '.' . $extension;

            // On enregistre le fichier à la racine /storage/app/public/uploads, ici la méthode storeAs défini déjà le chemin /storage/app
            $path = $request->file('pictureItem')->storeAs('public/storage/uploads/items', $filename);
        } else {
            $filename = Null;
        }


        // On crée un nouvel article
        $item = Item::create([
            'titleItem' => $request->titleItem,
            'subtitleItem' => $request->subtitleItem,
            'pictureItem' => $filename,
            'contentItem' => $request->contentItem,
            'user_id' => $request->user_id
        ]);
        // On retourne les informations du nouvel article en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $item,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        // On récupère tous les éléments de la table article
        $item = DB::table('items')
            ->join('users', 'users.id', '=', 'items.user_id')
            ->select('items.*', 'users.firstName as firstName', 'users.lastName as lastName')
            ->where('items.id', $item->id)
            // ->distinct()
            ->get();
        // ->toArray()

        // On retourne les informations de l'article en JSON
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $this->validate($request, [
            'titleItem' => 'required|max:100',
            'subtitleItem' => 'required|max:255',
            'contentItem' => 'required',
            'pictureItem' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg'

        ]);

        $filename = "";
        if ($request->hasFile('pictureItem')) {

            // On récupère le nom du fichier avec son extension, résultat $filenameWithExt : "jeanmiche.jpg"
            $filenameWithExt = $request->file('pictureItem')->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // On récupère l'extension du fichier, résultat $extension : ".jpg"
            $extension = $request->file('pictureItem')->getClientOriginalExtension();

            // On créer un nouveau fichier avec le nom + une date + l'extension, résultat $fileNameToStore : "jeanmiche_20220422.jpg"
            $filename = $filenameWithoutExt . '_' . time() . '.' . $extension;

            // On enregistre le fichier à la racine /storage/app/public/uploads, ici la méthode storeAs défini déjà le chemin /storage/app
            $path = $request->file('pictureItem')->storeAs('public/storage/uploads/items', $filename);
        }
        // else {
        //     $request('pictureItem') = $filename;
        // }


        // On modifie l'article
        $item->update([
            'titleItem' => $request->titleItem,
            'subtitleItem' => $request->subtitleItem,
            'pictureItem' => $filename,
            'contentItem' => $request->contentItem,
            'user_id' => $request->user_id
        ]);
        // On retourne les informations de l'articlet en JSON
        return response()->json([
            'status' => 'Mise à jour avec succèss'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        // On supprime l'article
        $item->delete();
        // On retourne la réponse JSON
        return response()->json([
            'status' => 'Supprimer avec succès'
        ]);
    }
}
