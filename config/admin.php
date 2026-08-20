<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Path
    |--------------------------------------------------------------------------
    |
    | The base path (no leading slash) under which the entire admin panel is
    | served. Change this in your .env with ADMIN_PATH to move the panel
    | (e.g. ADMIN_PATH=vanniyan-control). The old /admin URL always returns
    | a 404 and is never redirected, so it never reveals the new location.
    |
    */

    'path' => env('ADMIN_PATH', 'vanniyan-control'),

    /*
    |--------------------------------------------------------------------------
    | Legacy /admin URL
    |--------------------------------------------------------------------------
    |
    | When the admin path is anything other than "admin", requests to /admin
    | and /admin/* will return 404 without redirecting. Disable only if you
    | intentionally keep the panel at /admin.
    |
    */

    'legacy_path_404' => env('ADMIN_LEGACY_404', true),
];