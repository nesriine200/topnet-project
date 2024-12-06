<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use Illuminate\Support\Facades\DB;

class DataExportController extends Controller
{
    public function exportData()
    {
        // Retrieve all Opportunity records
        $data = Opportunity::all();

        $csvFileName = 'opportunities_data.csv';
        $filePath = storage_path('app/' . $csvFileName);

        // Open file for writing
        $handle = fopen($filePath, 'w');

        // If you know the columns you want, set them as headers:
        fputcsv($handle, ['id', 'user_id', 'commissions', 'etat', 'created_at', 'updated_at', 'date_validation']);

        // Write each row to the CSV
        foreach ($data as $row) {
            // Convert the Eloquent model instance to an array
            $arrayRow = $row->toArray();
            // Select only the fields you want to export:
            $csvRow = [
                $arrayRow['id'],
                $arrayRow['user_id'],
                $arrayRow['commissions'],
                $arrayRow['etat'],
                $arrayRow['created_at'],
                $arrayRow['updated_at'],
                $arrayRow['date_validation'],
            ];
            fputcsv($handle, $csvRow);
        }

        // Close the file
        fclose($handle);

        // Return the CSV file for download
        return response()->download($filePath);

    }
}