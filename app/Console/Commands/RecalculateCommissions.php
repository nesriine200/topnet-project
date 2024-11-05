<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Opportunity;
use Carbon\Carbon;

class RecalculateCommissions extends Command
{
    protected $signature = 'commissions:recalculate';
    protected $description = 'Recalcule les commissions pour toutes les opportunités validées';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {logger("nesrine");
        // Récupérer toutes les opportunités validées
        $opportunities = Opportunity::where('etat', 'valide')->get();

        foreach ($opportunities as $opportunity) {
            $dateSpecifique = Carbon::create(2024,10 , 11);
            $mois_payes = Carbon::now()->diffInMonths($dateSpecifique);
//            // Calcul des mois payés depuis la validation
//
//            $mois_payes = Carbon::now()->diffInMonths($opportunity->date_validation);

            // Calcul de la commission
            $prix_mensuel = $opportunity->offer->price;
            $opportunity->commissions = $mois_payes * $prix_mensuel * 0.10; // 10%

            // Sauvegarder l'opportunité mise à jour
            $opportunity->save();
        }

        $this->info('Les commissions ont été recalculées.');
    }
}
