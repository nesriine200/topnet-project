<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use Illuminate\Support\Facades\DB;

class DataExportController extends Controller
{
    public function exportData()
    {
        // Récupérer les données des apporteurs d'affaires
        $data = Opportunity::all();
         
        // DB::table('opportunities')
        //     ->join('users', 'opportunities.user_id', '=', 'users.id')
        //     ->select(
        //         'users.id as user_id', 
        //         'users.name',
        //         DB::raw('SUM(opportunities.commissions) as total_commission'), // Adjusted column name
        //         DB::raw('COUNT(opportunities.id) as opportunities_validated'),
        //         DB::raw('AVG(DATEDIFF(opportunities.date_validation, opportunities.created_at)) as avg_opportunity_duration') // Adjusted for 'date_validation'
        //     )
        //     ->where('opportunities.etat', '=', 'valide') // Only include valid opportunities
        //     ->groupBy('users.id', 'users.name')
        //     ->get();

        $csvFileName = 'apporteurs_data.csv';
        $filePath = storage_path('app/' . $csvFileName);

        // Open file for writing
        $handle = fopen($filePath, 'w');

        // Add CSV headers
        fputcsv($handle, ['user_id', 'name', 'total_commission', 'opportunities_validated', 'avg_opportunity_duration']);

        // Write data rows to CSV
        foreach ($data as $row) {
            fputcsv($handle, (array) $row);
        }

        // Close the file
        fclose($handle);

        // Return the CSV file for download
        return response()->download($filePath);
    }
}