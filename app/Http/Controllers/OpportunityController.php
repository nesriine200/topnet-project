<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Offer;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\OpportunityValidated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogService;


class OpportunityController extends Controller
{
    // Display Opportunities List (with search support)
    public function index(Request $request)
    {
        $query = Opportunity::with('user');

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('client', 'like', '%' . $search . '%');
        }

        $opportunities = $query->get();

        // Return response as JSON if AJAX request
        if ($request->ajax()) {
            return response()->json($opportunities);
        }

        return view('opportunities.index', compact('opportunities'));
    }

    // Display Contracts List (with search support)
    public function contract(Request $request)
    {
        $query = Opportunity::with('user');

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('client', 'like', '%' . $search . '%');
        }

        $opportunities = $query->get();

        // Return response as JSON if AJAX request
        if ($request->ajax()) {
            return response()->json($opportunities);
        }

        return view('opportunities.contract', compact('opportunities'));
    }

    // Show the form to create a new opportunity
    public function create()
    {
        $offers = Offer::all(); // Fetch all available offers
        return view('opportunities.create', compact('offers'));
    }

    // Store a new opportunity
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

        // Log the creation action
        AuditLogService::logAction(
            'Création',
            'opportunities',
            null,
            $opportunity->toJson()
        );

        return redirect()->route('opportunities.index')->with('success', 'Opportunity created successfully.');
    }

    // Show a specific opportunity
    public function show(Opportunity $opportunity)
    {
        return view('opportunities.show', compact('opportunity'));
    }

    // Show the form to edit an existing opportunity
    public function edit(Opportunity $opportunity)
    {
        return view('opportunities.edit', compact('opportunity'));
    }

    // Update an existing opportunity
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

    // Delete an opportunity
    public function destroy(Opportunity $opportunity)
    {
        $opportunity->delete();

        return redirect()->route('opportunities.index')->with('success', 'Opportunity deleted successfully.');
    }

    // Validate or invalidate an opportunity and send notification
    public function validateOpportunity(Request $request, $id)
    {
        $opportunity = Opportunity::findOrFail($id);

        // Validate the opportunity
        if ($request->input('action') === 'valide') {
            $opportunity->etat = 'valide';
            $opportunity->date_validation = Carbon::now();

            // Calculate the number of months paid and commission
            $mois_payes = Carbon::now()->diffInMonths($opportunity->date_validation);
            $prix_mensuel = $opportunity->offer->price;
            $opportunity->commissions = $mois_payes * $prix_mensuel * 0.10;
        } elseif ($request->input('action') === 'non valide') {
            // Invalidate the opportunity
            $opportunity->etat = 'non valide';
            $opportunity->commissions = 0;
        }

        $opportunity->save();

        // Send notification to the business referrer (user)
        $user = $opportunity->user;
        $user->notify(new OpportunityValidated($opportunity));

        return redirect()->route('opportunities.index', $opportunity->id)->with('success', 'L\'opportunité a été mise à jour avec succès.');
    }

    // Generate PDF contract for an opportunity
    public function print($id)
    {
        $opportunity = Opportunity::findOrFail($id);

        // Load the view for the PDF
        $pdf = Pdf::loadView('pdf.opportunity', compact('opportunity'));

        // Download the PDF
        return $pdf->download('contrat_' . $opportunity->id . '.pdf');
    }
    public function recentlyValidatedOpportunities()
    {
        $opportunities = Opportunity::where('etat', 'valide') // Remplacez "status" par "etat" si nécessaire
            ->orderBy('created_at', 'desc') // Trier par la dernière modification
            ->take(5) // Récupérer les 5 plus récentes
            ->get();

        return $opportunities;
    }
}
