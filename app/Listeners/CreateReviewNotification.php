<?php

namespace App\Listeners;

use App\Events\ReviewCreated;
use App\Models\Notification;
use App\Services\WhatsappService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CreateReviewNotification implements ShouldQueue
{
    public function handle(ReviewCreated $event): void
    {
        $review = $event->review;
        $business = $review->business;

        if (!$business || !$business->user) {
            Log::error('Não foi possível criar a notificação: business ou user não encontrado', [
                'review_id' => $review->id,
                'business_id' => $review->business_id,
            ]);
            return;
        }

        $reviewerName = $review->name ?: 'Anônimo';

        Notification::create([
            'user_id' => $business->user->id,
            'type' => 'review',
            'title' => 'Nova avaliação recebida',
            'message' => "{$reviewerName} deixou uma avaliação de {$review->rating} estrelas para {$business->name}",
            'business_id' => $business->id,
            'customer_name' => $reviewerName,
            'rating' => $review->rating,
            'action_url' => '/dashboard/reviews',
            'is_read' => false,
        ]);

        try {
            $phone = $business->user->phone;

            if ($phone) {
                $message = "{$reviewerName} deixou uma avaliação de {$review->rating} estrelas para {$business->name}";

                WhatsappService::sendMessage($phone, $message);
                Log::info('Notificação por WhatsApp enviada com sucesso', [
                    'phone' => $phone,
                    'message' => $message,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação por WhatsApp', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
