<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Platform-specific configuration for omarhesham.school
    |--------------------------------------------------------------------------
    */

    // Maximum file upload size in megabytes
    'upload_max_mb' => env('PLATFORM_UPLOAD_MAX_MB', 20),

    // Whether ads are shown to minors (MUST remain false for COPPA)
    'minor_ads_enabled' => env('PLATFORM_MINOR_ADS_ENABLED', false),

    // Whether children require parent/guardian consent before accessing content
    'require_parent_consent' => env('PLATFORM_REQUIRE_PARENT_CONSENT', true),

    // Consent token expiry in days
    'consent_expiry_days' => 7,

    // Supported locales
    'locales' => ['en', 'ar'],

];
