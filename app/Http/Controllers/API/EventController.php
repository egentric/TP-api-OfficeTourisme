<?php

namespace App\Http\Controllers\API;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Site;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère tous les éléments de la table évenement
        $events = DB::table('events')
            // On y join la table event_site
            // ->leftjoin('event_site', 'event_site.event_id', '=', 'events.id')
            // On y join la table sites
            // ->leftjoin('sites', 'sites.id', '=', 'event_site.site_id')
            // On sélectionne les colonnes du site et on les renommes
            // ->select('events.*', 'sites.nameSite as nameSite', 'sites.emailSite as emailSite', 'sites.websiteSite as websiteSite', 'sites.phoneSite as phoneSite')

            ->get()
            ->toArray();
        // On retourne les informations de la table évenement en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $events,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titleEvent' => 'required|max:100',
            'subtitleEvent' => 'required|max:255',
            'contentEvent' => 'required',
            'pictureEvent' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $filename = "";
        if ($request->hasFile('pictureEvent')) {

            // On récupère le nom du fichier avec son extension, résultat $filenameWithExt : "jeanmiche.jpg"
            $filenameWithExt = $request->file('pictureEvent')->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // On récupère l'extension du fichier, résultat $extension : ".jpg"
            $extension = $request->file('pictureEvent')->getClientOriginalExtension();

            // On créer un nouveau fichier avec le nom + une date + l'extension, résultat $fileNameToStore : "jeanmiche_20220422.jpg"
            $filename = $filenameWithoutExt . '_' . time() . '.' . $extension;

            // On enregistre le fichier à la racine /storage/app/public/uploads, ici la méthode storeAs défini déjà le chemin /storage/app
            $request->file('pictureEvent')->storeAs('public/uploads/events', $filename);
        } else {
            $filename = Null;
        }


        // On crée un nouveau évenement
        $event = Event::create([
            'titleEvent' => $request->titleEvent,
            'subtitleEvent' => $request->subtitleEvent,
            'pictureEvent' => $filename,
            'contentEvent' => $request->contentEvent,
        ]);

        // // ====================table pivot event_site====================== // //

        // récupèration des identifiants des modèles Site à partir de la requête HTTP : $eventSitesIds= $request->site_id;.
        $eventSitesIds[] = $request->site_id;
        // on vérifie que le tableau $eventSitesIds n'est pas vide,
        if (!empty($eventSitesIds)) {
            // puis pour chaque identifiant dans le tableau, on récupère le modèle Site correspondant en utilisant la méthode find() 
            for ($i = 0; $i < count($eventSitesIds); $i++) {
                $site = Site::find($eventSitesIds[$i]);
                //  et on attache à l'événement en utilisant la méthode attach().
                $event->site()->attach($site);
            }
            // En résumé, ce code attache un ou plusieurs sites à un événement en utilisant leurs identifiants respectifs.
        }
        // // ====================Fin table pivot event_site====================== // //

        // On retourne les informations du nouveau évenement en JSON
        return response()->json([
            'status' => 'Success',
            'data' => $event,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // On récupère tous les éléments de la table évenement et de la table sites

        $event = Event::with("site")->where('events.id', $event->id)->first();

        // On retourne les informations de l'évenement en JSON
        return response()->json($event);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->validate($request, [
            'titleEvent' => 'required|max:100',
            'subtitleEvent' => 'required|max:255',
            'contentEvent' => 'required',

        ]);

        $filename = "";
        if ($request->hasFile('pictureEvent')) {

            // On récupère le nom du fichier avec son extension, résultat $filenameWithExt : "jeanmiche.jpg"
            $filenameWithExt = $request->file('pictureEvent')->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // On récupère l'extension du fichier, résultat $extension : ".jpg"
            $extension = $request->file('pictureEvent')->getClientOriginalExtension();

            // On créer un nouveau fichier avec le nom + une date + l'extension, résultat $fileNameToStore : "jeanmiche_20220422.jpg"
            $filename = $filenameWithoutExt . '_' . time() . '.' . $extension;

            // On enregistre le fichier à la racine /storage/app/public/uploads, ici la méthode storeAs défini déjà le chemin /storage/app
            $request->file('pictureEvent')->storeAs('public/uploads/events', $filename);
        } else {
            $filename = $event->pictureEvent;
        }
        // dd($filename);

        DB::table('event_site')->where('event_id', '=', $event->id)->delete();
        // On modifie l'évenement
        $event->update([
            'titleEvent' => $request->titleEvent,
            'subtitleEvent' => $request->subtitleEvent,
            'pictureEvent' => $filename,
            'contentEvent' => $request->contentEvent,
        ]);

        // // ====================table pivot event_site====================== // //

        // ajoute l'ID d'un site, récupéré à partir d'une requête HTTP ($request), à un tableau $sites.
        $sites[] = $request->site_id;

        // on vérifie que le tableau $sites n'est pas vide,
        if (!empty($sites)) {
            // Cela initialise une boucle for pour parcourir tous les éléments du tableau $sites.
            for ($i = 0; $i < count($sites); $i++) {
                // Cela récupère un site spécifique à partir de la base de données en utilisant son ID, qui est stocké dans le tableau $sites à l'indice $i.
                $site = Site::find($sites[$i]);
                // Cela attache le site à l'événement en utilisant la méthode attach() fournie par la relation site() définie sur l'objet $event.
                //  Cette méthode prend un objet Site en paramètre et gère la création de l'entrée de la relation dans la base de données.
                $event->site()->attach($site);
            }
            // ce code récupère les ID des sites liés à un événement, les parcourt un par un pour récupérer les objets Site correspondants à partir de la base de données et les attache ensuite à l'événement.
        }
        // // ====================Fin table pivot event_site====================== // //


        // On retourne les informations de l'évenement en JSON
        return response()->json([
            'status' => 'Mise à jour avec succèss'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // On supprime tous les enregistrements associés dans la table pivot
        $event->site()->detach();

        // On supprime l'évenement
        $event->delete();
        // On retourne la réponse JSON
        return response()->json([
            'status' => 'Supprimer avec succès'
        ]);
    }
}
