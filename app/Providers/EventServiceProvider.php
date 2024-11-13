<?php

namespace App\Providers;

use App\Events\PostEngagementEvent;
use App\Listeners\CreatePostEngagementNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {
    protected $listen = [
        PostEngagementEvent::class => [
            CreatePostEngagementNotification::class,
        ],
    ];
}
