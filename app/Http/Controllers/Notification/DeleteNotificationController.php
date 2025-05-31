<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Notification')]
class DeleteNotificationController extends Controller
{
    /**
     * Rota para deletar uma notificação
     */
    public function __invoke(Request $request, string $notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return $this->error('Notificação não encontrada', Response::HTTP_NOT_FOUND);
        }

        $notification->delete();

        return $this->success('Notificação excluída com sucesso', Response::HTTP_OK);
    }
}
