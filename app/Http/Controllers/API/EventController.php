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
            ->join('event_site', 'event_site.event_id', '=', 'events.id')
            // On y join la table sites
            ->join('sites', 'sites.id', '=', 'event_site.site_id')
            // On sélectionne les colonnes du site et on les renommes
            ->select('events.*', 'sites.nameSite as nameSite', 'sites.emailSite as emailSite', 'sites.websiteSite as websiteSite', 'sites.phoneSite as phoneSite')

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

        // // table pivot event_site // //

        // Cette ligne de code ajoute l'ID du site envoyé dans la requête dans un tableau $eventSitesIds.
        // Cela vous permettra de récupérer tous les ID des sites sélectionnés pour le producteur       
        $eventSitesIds[] = $request->site_id;
        // Cette condition vérifie si le tableau $eventSitesIds n'est pas vide avant de poursuivre le traitement.
        //  Cela permet d'éviter d'exécuter le code inutilement si aucun site n'a été envoyé dans la requête.        
        if (!empty($eventSitesIds)) {
            // Cette boucle foreach itère sur chaque ID de site présent dans le tableau $eventSitesIds.
            foreach ($eventSitesIds as $siteId) {
                // Cette ligne de code récupère le modèle site correspondant à l'ID du site de la boucle en utilisant la méthode find().
                //  Cette méthode recherche un enregistrement dans la table sites qui a l'ID spécifié et renvoie un objet site correspondant.
                $site = Event::find($siteId);
                // Cette ligne de code utilise la méthode attach() sur la relation ManyToMany entre Event et Site pour ajouter le site
                //  récupéré précédemment à l'event que vous voulez mettre à jour.
                $event->site()->attach($site);
            }
        }




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
        // On récupère tous les éléments de la table évenement
        $event = DB::table('events')
            // On y join la table event_site
            ->leftjoin('event_site', 'event_site.event_id', '=', 'events.id')
            // On y join la table sites
            ->leftjoin('sites', 'sites.id', '=', 'event_site.site_id')
            // On sélectionne les colonnes du site et on les renommes
            ->select('events.*', 'sites.nameSite as nameSite', 'sites.emailSite as emailSite', 'sites.websiteSite as websiteSite', 'sites.phoneSite as phoneSite')
            ->where('events.id', $event->id)
            ->get();
        // ->toArray()

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
        }
        // else {
        //     $filename = Null;
        // }


        // On modifie l'évenement
        $event->update([
            'titleEvent' => $request->titleEvent,
            'subtitleEvent' => $request->subtitleEvent,
            'pictureEvent' => $filename,
            'contentEvent' => $request->contentEvent,
        ]);


        // création d'un tableau vide $updateSiteId pour stocker les identifiants des modèles site
        // qui seront utilisés pour la mise à jour des relations.
        $updateSiteId = array();

        // // table pivot event_site // //

        // récupèration des identifiants des modèles Site à partir de la requête HTTP : $eventSitesIds= $request->site_id;.
        $eventSitesIds = $request->site_id;
        // on vérifie que le tableau $eventSitesIds n'est pas vide,
        if (!empty($eventSitesIds)) {
            // puis pour chaque identifiant dans le tableau, on récupère le modèle Site correspondant en utilisant la méthode find() 
            for ($i = 0; $i < count($eventSitesIds); $i++) {
                $site = Site::find($eventSitesIds[$i]);
                // on ajoute son identifiant au tableau $updateProdId en utilisant la fonction array_push().
                array_push($updateSiteId, $site->id);
            }
            // on appelle la méthode sync() sur la relation site du modèle event en passant le tableau $updateSiteId comme argument,
            //  ce qui mettra à jour les relations en supprimant toutes les entrées pivot existantes et
            //  en insérant de nouvelles entrées pour les identifiants de modèles site fournis.
            $event->site()->sync($updateSiteId);
        }


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
