<?php

namespace Modules\Core\Listeners\Frontend;

use Modules\Core\Events\Frontend\UserLoggedIn;


/**
 * Class UserLoggedInListener.
 */
class UserLoggedInListener
{
    public function handle(UserLoggedIn $event)
    {
        $ip_address = request()->getClientIp();

        // Update the logging in users time & IP
        $event->user->fill([
            'last_login_at' => now()->toDateTimeString(),
            'last_login_ip' => $ip_address,
        ]);

//        // Update the timezone via IP address
//        $geoip = geoip($ip_address);
//
//        if ($event->user->timezone !== $geoip['timezone']) {
//            // Update the users timezone
//            $event->user->fill([
//                'timezone' => $geoip['timezone'],
//            ]);
//        }

        $event->user->save();
    }

}
