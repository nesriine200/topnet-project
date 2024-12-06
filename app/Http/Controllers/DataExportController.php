<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DataExportController extends Controller
{
    public function exportData()
    {
        // Récupérer les données des apporteurs d'affaires
        $data = DB::table('opportunities')
            ->join('users', 'opportunities.user_id', '=', 'users.id')
            ->select('users.id as user_id', 'users.name',
                DB::raw('SUM(opportunities.commission) as total_commission'),
                DB::raw('COUNT(opportunities.id) as opportunities_validated'),
                DB::raw('AVG(DATEDIFF(opportunities.validation_date, opportunities.creation_date)) as avg_opportunity_duration'))
            ->where('opportunities.etat', '=', 'valide')
            ->groupBy('users.id', 'users.name')
            ->get();

        // Exporter les données dans un fichier CSV
        $csvFileName = 'apporteurs_data.csv';
        $handle = fopen(storage_path('app/' . $csvFileName), 'w');
        fputcsv($handle, ['user_id', 'name', 'total_commission', 'opportunities_validated', 'avg_opportunity_duration']);

        foreach ($data as $row) {
            fputcsv($handle, (array) $row);
        }

        fclose($handle);

        // Retourner le fichier CSV pour le téléchargement
        return response()->download(storage_path('app/' . $csvFileName));
    }
}
