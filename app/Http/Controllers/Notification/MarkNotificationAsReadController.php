<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Notification')]
class MarkNotificationAsReadController extends Controller
{
    /**
     * Rota para marcar uma notificação como lida
     */
    public function __invoke(Request $request, string $notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return $this->error('Notificação não encontrada', Response::HTTP_NOT_FOUND);
        }

        $notification->update(['is_read' => true]);

        return $this->success('Notificação marcada como lida', Response::HTTP_OK);
    }
}
