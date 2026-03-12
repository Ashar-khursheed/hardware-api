<?php

namespace App\Listeners;

use Throwable;
use App\Models\User;
use App\Enums\RoleEnum;
use App\Helpers\Helpers;
use App\Events\PlaceOrderEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\PlaceOrderNotification;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class PlaceOrderListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(PlaceOrderEvent $event)
    {
        try {
            // Consumer Notification
            if (is_null($event->order->parent_id)) {
                if ($event->order->consumer_id) {
                    $consumer = Helpers::getConsumerById($event->order->consumer_id);
                    if ($consumer) {
                        $consumer->notify(new PlaceOrderNotification($event->order, RoleEnum::CONSUMER));
                    }
                } else if ($event->order->is_guest && isset($event->order->consumer['email'])) {
                    Notification::route('mail', $event->order->consumer['email'])
                        ->notify(new PlaceOrderNotification($event->order, RoleEnum::CONSUMER));
                }
            }

            // Vendor Notification
            foreach ($event->order->sub_orders as $sub_order) {
                if (isset($sub_order->store_id)) {
                    $vendor = Helpers::getStoreById($sub_order->store_id)?->vendor;
                    if ($vendor) {
                        $vendor->notify(new PlaceOrderNotification($sub_order, RoleEnum::VENDOR));
                    }
                }
            }

            // Admin Notification
            $admins = User::role(RoleEnum::ADMIN)->get();
            if ($admins->isNotEmpty()) {
                foreach ($admins as $admin) {
                   $admin->notify(new PlaceOrderNotification($event->order, RoleEnum::ADMIN));
                }
            } else {
                // Fallback to settings email or a hardcoded one if no admin role found
                $settings = Helpers::getSettings();
                $adminEmail = $settings['general']['admin_email'] ?? null;
                if ($adminEmail) {
                    Notification::route('mail', $adminEmail)
                        ->notify(new PlaceOrderNotification($event->order, RoleEnum::ADMIN));
                }
            }

        } catch (Exception $e) {
            \Log::error('PlaceOrderListener error: ' . $e->getMessage());
        }
    }
}
