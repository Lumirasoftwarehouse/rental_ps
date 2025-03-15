<?php

namespace App\Helpers;

use App\Events\AdminIntelud;
use App\Events\AdminLitpers;
use App\Events\AdminPamsut;
use App\Events\MitraIntelud;
use App\Events\MitraLitpers;
use App\Events\PicIntelud;
use Illuminate\Support\Facades\Log;

class NotificationHelper
{
    // A mapping of event names to event class names
    protected static $events = [
        'admin_intelud' => AdminIntelud::class,
        'admin_litpers' => AdminLitpers::class,
        'admin_pamsut' => AdminPamsut::class,
        'mitra_intelud' => MitraIntelud::class,
        'mitra_litpers' => MitraLitpers::class,
        'pic_intelud' => PicIntelud::class,
    ];

    /**
     * Broadcast the notification to the specified target event.
     * 
     * @param string $target The key of the event class to broadcast.
     * @param string $message The message to send.
     * 
     * @return void
     */
    public static function broadcastNotification($target, $message, $userId = null)
    {
        if (array_key_exists($target, self::$events)) {
            $eventClass = self::$events[$target];

            if ($userId) {
                event(new $eventClass($message, $userId));
            } else {
                event(new $eventClass($message));
            }
        } else {
            Log::error("NotificationHelper: Event '$target' not found.");
        }
    }
}
