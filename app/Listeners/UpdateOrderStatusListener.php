<?php

namespace App\Listeners;

use Exception;
use App\Helpers\Helpers;
use App\Events\UpdateOrderStatusEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\UpdateOrderStatusNotification;

class UpdateOrderStatusListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(UpdateOrderStatusEvent $event)
    {
        try {

            if (is_null($event->order->parent_id)) {
                if ($event->order->consumer_id) {
                    $consumer = Helpers::getConsumerById($event->order->consumer_id);
                    if ($consumer) {
                        $consumer->notify(new UpdateOrderStatusNotification($event->order, $consumer));
                    }
                } else if ($event->order->is_guest && isset($event->order->consumer['email'])) {
                    // Send notification to guest checkout email
                    \Illuminate\Support\Facades\Notification::route('mail', $event->order->consumer['email'])
                        ->notify(new UpdateOrderStatusNotification($event->order, null));
                }
            }

        } catch (Exception $e) {

            //
        }
    }
}
