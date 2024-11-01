<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use Illuminate\Http\Request;
use App\Models\Offer;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\OpportunityValidated;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogService;

class OpportunityController extends Controller
{
//recherche
    public function index(Request $request)
    {
        $query = Opportunity::with('user');

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('client', 'like', '%' . $search . '%');
        }

        $opportunities = $query->get();

        // Vérifie si la requête est AJAX et retourne les données en JSON
        if ($request->ajax()) {
            return response()->json($opportunities);
        }

        return view('opportunities.index', compact('opportunities'));
    }
//affichage cotrat
    public function contract(Request $request)
    {
        $query = Opportunity::with('user');

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('client', 'like', '%' . $search . '%');
        }

        $opportunities = $query->get();

        // Vérifie si la requête est AJAX et retourne les données en JSON
        if ($request->ajax()) {
            return response()->json($opportunities);
        }

        return view('opportunities.contract', compact('opportunities'));
    }


    public function create()
    { $offers = Offer::all(); // Récupérer toutes les offres disponibles
        return view('opportunities.create', compact('offers'));}

    public function store(Request $request)
    {
        $request->validate([
            'client' => 'required|string|max:255',
            'etat' => 'string|in:valide,non valide,en cours',
            'matricule_client' => 'required|string|max:255',
            'description' => 'required|string',
            'commissions' => 'nullable|numeric',
            'offer_id' => 'required|exists:offers,id',

        ]);

        $opportunity = Opportunity::create([
            'user_id' => auth()->id(),
            'client' => $request->client,
            'etat' => $request->etat,
            'matricule_client' => $request->matricule_client,
            'description' => $request->description,
            'commissions' => $request->commissions,
            'offer_id' => $request->offer_id,
        ]);

        // Enregistrement de l'action dans l'Audit Log
        \App\Services\AuditLogService::logAction(
            'Création', // Action
            'opportunities', // Nom de la table
            null, // Anciennes valeurs (null car c'est une création)
            $opportunity->toJson() // Nouvelles valeurs (en JSON)
        );
        return redirect()->route('opportunities.index')->with('success', 'Opportunity created successfully.');
    }

    public function show(Opportunity $opportunity)
    {
        return view('opportunities.show', compact('opportunity'));
    }

    public function edit(Opportunity $opportunity)
    {
        return view('opportunities.edit', compact('opportunity'));
    }

    public function update(Request $request, Opportunity $opportunity)
    {
        $request->validate([
            'client' => 'required|string|max:255',
            'etat' => 'string|in:valide,non valide,en cours',
            'matricule_client' => 'required|string|max:255',
            'description' => 'required|string',
            'commissions' => 'nullable|numeric',
        ]);

        $opportunity->update($request->all());

        return redirect()->route('opportunities.index')->with('success', 'Opportunity updated successfully.');
    }

    public function destroy(Opportunity $opportunity)
    {
        $opportunity->delete();

        return redirect()->route('opportunities.index')->with('success', 'Opportunity deleted successfully.');
    }

//   public function validateOpportunity(Request $request, $id)
//{
//    // Trouver l'opportunité par son ID
//    $opportunity = Opportunity::findOrFail($id);
//
//    // Vérifier l'action (valider ou invalider)
//    if ($request->input('action') === 'valide') {
//        $opportunity->etat = 'valide';
//        $opportunity->date_validation = Carbon::now(); // Ajouter la date de validation
//
//        // Calcul des mois payés depuis la validation
//        $mois_payes = Carbon::now()->diffInMonths($opportunity->date_validation);
//
//        // Calcul de la commission en fonction des mois payés et du prix de l'offre
//        $prix_mensuel = $opportunity->offer->prix; // On suppose que l'offre est liée à l'opportunité
//        $opportunity->commission = $mois_payes * $prix_mensuel * 0.10; // 10% du total payé
//
//    } elseif ($request->input('action') === 'non valide') {
//        // Si l'opportunité est invalidée, réinitialiser les champs pertinents
//        $opportunity->etat = 'non valide';
//        $opportunity->commission = 0; // Pas de commission si l'opportunité n'est pas validée
//        $opportunity->date_validation = null; // Pas de date de validation si non valide
//    }
//
//    // Enregistrer les modifications dans la base de données
//    $opportunity->save();
//
//    // Rediriger avec un message de succès
//    return redirect()->route('opportunities.index', $opportunity->id)
//        ->with('success', 'L\'opportunité a été mise à jour avec succès.');
//}
    public function validateOpportunity(Request $request, $id)
    {
        $opportunity = Opportunity::findOrFail($id);

        // Vérifier l'action (valider ou invalider)
        if ($request->input('action') === 'valide') {
            $opportunity->etat = 'valide';
            $opportunity->date_validation = Carbon::now(); // Ajoute la date de validation


            // Calcul des mois payés
      $mois_payes = Carbon::now()->diffInMonths($opportunity->date_validation);

            // Calcul de la commission en fonction du nombre de mois payés et du prix de l'offre
            $prix_mensuel = $opportunity->offer->price;
            $opportunity->commissions = $mois_payes * $prix_mensuel * 0.10; // 10% du total payé

        } elseif ($request->input('action') === 'non valide') {
            $opportunity->etat = 'non valide';
            $opportunity->commissions = 0; // Remet la commission à 0 si non validé
        }

        $opportunity->save();
        // Envoyer une notification à l'apporteur d'affaires
        $user = $opportunity->user; // Supposons que l'opportunité est liée à un utilisateur
        $user->notify(new OpportunityValidated($opportunity));


        return redirect()->route('opportunities.index', $opportunity->id)
            ->with('success', 'L\'opportunité a été mise à jour avec succès.');
    }
//    pdf
    public function print($id)
    {
        // Récupérer les détails de l'opportunité
        $opportunity = Opportunity::findOrFail($id);

        // Charger la vue pour le PDF
        $pdf = Pdf::loadView('pdf.opportunity', compact('opportunity'));

        // Télécharger le PDF
        return $pdf->download('contrat_'.$opportunity->id.'.pdf');
    }

    public function statComissions()
    {
        // Statistiques des opportunités validées
        $apporteurs = User::role('apporteur')->get();
        $names = [];
        $validatedOpportunities = [];

        foreach ($apporteurs as $apporteur) {
            $names[] = $apporteur->name;
            $validatedOpportunities[] = Opportunity::where('user_id', $apporteur->id)
                ->where('etat', 'valide')
                ->count();
        }

        // Statistiques des commissions
        $user = Auth::user();

        // Récupérer uniquement les opportunités validées pour l'utilisateur connecté
        $opportunities = Opportunity::where('user_id', $user->id)
            ->where('etat', 'valide')
            ->get();

        // Assurez-vous que vous utilisez le bon nom de champ `commissions`
        $totalCommission = $opportunities->sum('commissions');

        $monthlyCommissions = [];
        $months = [];
        $currentYear = Carbon::now()->year;

        // Calcul des commissions pour chaque mois de l'année courante
        for ($i = 1; $i <= 12; $i++) {
            $monthStart = Carbon::create($currentYear, $i, 1)->startOfMonth();
            $monthEnd = Carbon::create($currentYear, $i, 1)->endOfMonth();

            // Récupérer les commissions pour chaque mois directement depuis la base de données
            $monthlyCommission = Opportunity::where('user_id', $user->id)
                ->where('etat', 'valide')
                ->whereBetween('date_validation', [$monthStart, $monthEnd])
                ->sum('commissions');  // Utiliser 'commissions' au lieu de 'commission'

            $monthlyCommissions[] = $monthlyCommission;
            $months[] = $monthStart->format('F');
        }

        // Calcul de la commission pour le mois en cours et le mois précédent
        $currentMonthCommission = $monthlyCommissions[Carbon::now()->month - 1];
        $previousMonthCommission = Carbon::now()->month > 1
            ? $monthlyCommissions[Carbon::now()->month - 2]
            : 0;

        // Calcul du pourcentage de changement
        if ($previousMonthCommission > 0) {
            $percentageChange = (($currentMonthCommission - $previousMonthCommission) / $previousMonthCommission) * 100;
        } else {
            $percentageChange = 100;
        }

        // Retourner les données à la vue
        return view('home.acceuil', compact(
            'names', 'validatedOpportunities',
            'totalCommission', 'monthlyCommissions', 'months', 'percentageChange'
        ));
    }


////  statistiques
//    public function showDashboard()
//    {
//        $apporteurs = User::role('apporteur')->get();
//        $names = [];
//        $validatedOpportunities = [];
//
//        foreach ($apporteurs as $apporteur) {
//            $names[] = $apporteur->name;
//            $validatedOpportunities[] = Opportunity::where('user_id', $apporteur->id)
//                ->where('etat', 'valide')
//                ->count();
//        }
//
//        return view('home.acceuil', compact('names', 'validatedOpportunities'));
//    }
/////STATISTIQUE COMISSIONS PAR MOIS
//    public function statistiquecom()
//    {
//        // Récupérer l'utilisateur connecté
//        $user = Auth::user();
//
//        // Récupérer les opportunités validées de cet utilisateur
//        $opportunities = Opportunity::where('user_id', $user->id)
//            ->where('etat', 'valide')
//            ->get();
//
//        // Calculer la commission totale
//        $totalCommission = $opportunities->sum('commission');
//
//        // Calcul des commissions par mois
//        $monthlyCommissions = [];
//        $months = [];
//        $currentYear = Carbon::now()->year;
//
//        for ($i = 1; $i <= 12; $i++) {
//            $monthStart = Carbon::create($currentYear, $i, 1)->startOfMonth();
//            $monthEnd = Carbon::create($currentYear, $i, 1)->endOfMonth();
//
//            // Commission pour chaque mois
//            $monthlyCommission = $opportunities->whereBetween('date', [$monthStart, $monthEnd])
//                ->sum('commission');
//
//            $monthlyCommissions[] = $monthlyCommission;
//            $months[] = $monthStart->format('F');
//        }
//
//        // Commission du mois en cours
//        $currentMonthCommission = $monthlyCommissions[Carbon::now()->month - 1];
//
//        // Commission du mois précédent
//        $previousMonthCommission = Carbon::now()->month > 1
//            ? $monthlyCommissions[Carbon::now()->month - 2]
//            : 0;
//
//        // Calculer la variation en pourcentage par rapport au mois précédent
//        if ($previousMonthCommission > 0) {
//            $percentageChange = (($currentMonthCommission - $previousMonthCommission) / $previousMonthCommission) * 100;
//        } else {
//            $percentageChange = 100; // Si le mois précédent est 0, on considère une augmentation totale
//        }
//
//        // Passer les données à la vue
//        return view('home.acceuil', [
//            'currentMonthCommission' => $currentMonthCommission,
//            'totalCommission' => $totalCommission,
//            'monthlyCommissions' => $monthlyCommissions,
//            'months' => $months,
//            'percentageChange' => $percentageChange
//        ]);
//    }
}
