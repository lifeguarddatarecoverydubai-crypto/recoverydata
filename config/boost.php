<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel Boost Enabled
    |--------------------------------------------------------------------------
    |
    | Only enable Boost in local development environments. In production or
    | testing environments, Boost is strictly disabled to prevent injection
    | of dev scripts (such as the browser logger) into responses.
    |
    */
    'enabled' => env('BOOST_ENABLED', env('APP_ENV') === 'local'),

    /*
    |--------------------------------------------------------------------------
    | Browser Logs Watcher
    |--------------------------------------------------------------------------
    |
    | When enabled, Boost injects a script into web responses to capture
    | browser console errors and send them to the dev server.
    |
    */
    'browser_logs_watcher' => env('BOOST_BROWSER_LOGS', env('APP_ENV') === 'local' && (bool) env('APP_DEBUG', false)),
];
