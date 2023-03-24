<?php

namespace App\Http\Controllers\API;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère tous les éléments de la table contact
        $contacts = DB::table('contacts')
            ->get()
            ->toArray();
        // On retourne les informations de la table contact en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $contacts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|max:100',
            'topic' => 'required|max:100',
            'content' => 'required',
        ]);
        // On crée un nouveau contact
        $contact = Contact::create([
            'email' => $request->email,
            'topic' => $request->topic,
            'content' => $request->content,
        ]);
        // On retourne les informations du nouveau contact en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $contact,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        // On retourne les informations du contact en JSON
        return response()->json($contact);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        // On supprime le contact
        $contact->delete();
        // On retourne la réponse JSON
        return response()->json([
            'status' => 'Supprimer avec succès'
        ]);
    }
}
