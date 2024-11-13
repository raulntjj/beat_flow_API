<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PostEngagementEvent;
use App\Models\Notification;

class CreatePostEngagementNotification {
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
public function handle(PostEngagementEvent $event) {
        $postOwnerId = $event->post->user_id;

        // Evita notificar se o próprio autor do post interagir
        if ($postOwnerId === $event->user->id) {
            return;
        }

        Notification::create([
            'user_id' => $postOwnerId,
            'type' => $event->engagementType,
            'content' => $this->generateContent($event->engagementType, $event->user, $event->post),
            'is_read' => false,
        ]);
    }


    private function generateContent($type, $user, $post) {
        if ($type === 'like') {
            return "{$user->name} curtiu sua publicação.";
        }

        if ($type === 'share') {
            return "{$user->name} compartilhou sua publicação.";
        }

        return "Teste engajamento.";
    }
}
