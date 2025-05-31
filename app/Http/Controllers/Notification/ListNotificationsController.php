<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Notification')]
class ListNotificationsController extends Controller
{
    /**
     * Rota para listar as notificações
     */
    public function __invoke(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->with('business')
            ->latest()
            ->get();

        return $this->success('Notificações obtidas com sucesso', Response::HTTP_OK, [
            'notifications' => NotificationResource::collection($notifications),
            'unread_count' => $notifications->where('is_read', false)->count(),
        ]);
    }
}
