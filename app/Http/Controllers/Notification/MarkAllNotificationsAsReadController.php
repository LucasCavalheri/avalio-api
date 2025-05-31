<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Notification')]
class MarkAllNotificationsAsReadController extends Controller
{
    /**
     * Rota para marcar todas as notificações como lidas
     */
    public function __invoke(Request $request)
    {
        $request->user()
            ->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return $this->success('Todas as notificações foram marcadas como lidas', Response::HTTP_OK);
    }
}
