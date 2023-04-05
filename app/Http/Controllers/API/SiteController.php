<?php

namespace App\Http\Controllers\API;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Type;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère tous les éléments de la table site
        // $sites = DB::table('sites')
        //     ->join('users', 'user_id', '=', 'sites.user_id')
        //     ->join('types', 'type_id', '=', 'sites.type_id')
        //     ->get()
        //     ->toArray();

        $sites = Site::with("type", "event")
            ->get();

        // On retourne les informations de la table site en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $sites,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nameSite' => 'required|max:100',
            'descriptionSite' => 'required',
            'emailSite' => 'required|max:100',
            'websiteSite' => 'required|max:100',
            'phoneSite' => 'required|max:50',
            'addressSite' => 'required|max:255',
            'zipSite' => 'required|max:10',
            'citySite' => 'required|max:100',
            'longitudeDegSite' => 'required|max:100',
            'latitudeDegSite' => 'required|max:100',
            'pictureSite' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $filename = "";
        if ($request->hasFile('pictureSite')) {

            // On récupère le nom du fichier avec son extension, résultat $filenameWithExt : "jeanmiche.jpg"
            $filenameWithExt = $request->file('pictureSite')->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // On récupère l'extension du fichier, résultat $extension : ".jpg"
            $extension = $request->file('pictureSite')->getClientOriginalExtension();

            // On créer un nouveau fichier avec le nom + une date + l'extension, résultat $fileNameToStore : "jeanmiche_20220422.jpg"
            $filename = $filenameWithoutExt . '_' . time() . '.' . $extension;

            // On enregistre le fichier à la racine /storage/app/public/uploads, ici la méthode storeAs défini déjà le chemin /storage/app
            $request->file('pictureSite')->storeAs('public/uploads/sites', $filename);
        } else {
            $filename = Null;
        }


        // On crée un nouveau site
        $site = Site::create([
            'nameSite' => $request->nameSite,
            'descriptionSite' => $request->descriptionSite,
            'emailSite' => $request->emailSite,
            'websiteSite' => $request->websiteSite,
            'phoneSite' => $request->phoneSite,
            'addressSite' => $request->addressSite,
            'zipSite' => $request->zipSite,
            'citySite' => $request->citySite,
            'longitudeDegSite' => $request->longitudeDegSite,
            'latitudeDegSite' => $request->latitudeDegSite,
            'pictureSite' => $filename,
            'user_id' => $request->user_id,
            'type_id' => $request->type_id,

        ]);
        // On retourne les informations du nouveau site en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $site,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {
        // On récupère tous les éléments de la table article
        $site = DB::table('sites')
            ->join('users', 'users.id', '=', 'sites.user_id')
            ->join('types', 'types.id', '=', 'sites.type_id')
            ->select('sites.*', 'users.firstName as firstName', 'users.lastName as lastName', 'types.nameType as nameType')
            ->where('sites.id', $site->id)
            // ->distinct()
            ->get();
        // ->toArray()

        // On retourne les informations du site en JSON
        return response()->json($site);
    }

    public function showSiteType(Type $type)
    {
        // On récupère tous les éléments de la table article
        $sites = DB::table('sites')
            ->join('types', 'types.id', '=', 'sites.type_id')
            ->select('sites.*', 'types.nameType as nameType')
            ->where('types.id', $type->id)
            // ->distinct()
            ->get();
        // ->toArray()

        // On retourne les informations du site en JSON
        return response()->json($sites);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site)
    {
        $this->validate($request, [
            'nameSite' => 'required|max:100',
            'descriptionSite' => 'required',
            'emailSite' => 'required|max:100',
            'websiteSite' => 'required|max:100',
            'phoneSite' => 'required|max:50',
            'addressSite' => 'required|max:255',
            'zipSite' => 'required|max:10',
            'citySite' => 'required|max:100',
            'longitudeDegSite' => 'required|max:100',
            'latitudeDegSite' => 'required|max:100',


        ]);

        $filename = "";
        if ($request->hasFile('pictureSite')) {

            // On récupère le nom du fichier avec son extension, résultat $filenameWithExt : "jeanmiche.jpg"
            $filenameWithExt = $request->file('pictureSite')->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // On récupère l'extension du fichier, résultat $extension : ".jpg"
            $extension = $request->file('pictureSite')->getClientOriginalExtension();

            // On créer un nouveau fichier avec le nom + une date + l'extension, résultat $fileNameToStore : "jeanmiche_20220422.jpg"
            $filename = $filenameWithoutExt . '_' . time() . '.' . $extension;

            // On enregistre le fichier à la racine /storage/app/public/uploads, ici la méthode storeAs défini déjà le chemin /storage/app
            $request->file('pictureSite')->storeAs('public/uploads/sites', $filename);
        } else {
            $filename = $site->pictureSite;
        }


        // On modifie le site
        $site->update([
            'nameSite' => $request->nameSite,
            'descriptionSite' => $request->descriptionSite,
            'emailSite' => $request->emailSite,
            'websiteSite' => $request->websiteSite,
            'phoneSite' => $request->phoneSite,
            'addressSite' => $request->addressSite,
            'zipSite' => $request->zipSite,
            'citySite' => $request->citySite,
            'longitudeDegSite' => $request->longitudeDegSite,
            'latitudeDegSite' => $request->latitudeDegSite,
            'pictureSite' => $filename,
            'user_id' => $request->user_id,
            'type_id' => $request->type_id,

        ]);
        // On retourne les informations du site en JSON
        return response()->json([
            'status' => 'Mise à jour avec succèss'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        // On supprime le site
        $site->delete();
        // On retourne la réponse JSON
        return response()->json([
            'status' => 'Supprimer avec succès'
        ]);
    }
}
