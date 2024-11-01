<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function show($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        // Marquer la notification comme lue
        $notification->markAsRead();

        // Vérifier si 'opportunity_id' existe dans les données de la notification
        if (!isset($notification->data['opportunity_id'])) {
            return redirect()->route('opportunities.index')->with('error', 'Opportunité non trouvée dans la notification.');
        }

        // Rediriger vers les détails de l'opportunité
        return redirect()->route('opportunities.show', $notification->data['opportunity_id']);

    }

   public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
}
