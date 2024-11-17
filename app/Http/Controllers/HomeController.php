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

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    { {
            // Get the business referrers (Apporteur d\'affaire)
            $apporteurs = User::role('apporteur')->get();
            $names = [];
            $validatedOpportunities = [];

            foreach ($apporteurs as $apporteur) {
                $names[] = $apporteur->name;
                $validatedOpportunities[] = Opportunity::where('user_id', $apporteur->id)
                    ->where('etat', 'valide')
                    ->count();
            }

            // Get commissions for the logged-in user
            $user = Auth::user();
            $opportunities = Opportunity::where('user_id', $user->id)
                ->where('etat', 'valide')
                ->get();

            $totalCommission = $opportunities->sum('commissions');
            $monthlyCommissions = [];
            $months = [];
            $currentYear = Carbon::now()->year;

            // Calculate monthly commissions
            for ($i = 1; $i <= 12; $i++) {
                $monthStart = Carbon::create($currentYear, $i, 1)->startOfMonth();
                $monthEnd = Carbon::create($currentYear, $i, 1)->endOfMonth();

                // Calculate commissions for the month
                $monthlyCommission = Opportunity::where('user_id', $user->id)
                    ->where('etat', 'valide')
                    ->whereBetween('date_validation', [$monthStart, $monthEnd])
                    ->sum('commissions');

                $monthlyCommissions[] = $monthlyCommission;
                $months[] = $monthStart->format('F');
            }

            // Calculate the percentage change in commissions
            $currentMonthCommission = $monthlyCommissions[Carbon::now()->month - 1];
            $previousMonthCommission = Carbon::now()->month > 1
                ? $monthlyCommissions[Carbon::now()->month - 2]
                : 0;

            // Calculate the percentage change
            $percentageChange = $previousMonthCommission > 0
                ? (($currentMonthCommission - $previousMonthCommission) / $previousMonthCommission) * 100
                : 100;

            return view('home.acceuil', compact(
                'names',
                'validatedOpportunities',
                'totalCommission',
                'monthlyCommissions',
                'months',
                'percentageChange'
            ));
        }
    }
}
