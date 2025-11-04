<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Firebase credentials and settings.
    |
    */

    'credentials' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase-credentials.json')),
    
    'project_id' => env('FIREBASE_PROJECT_ID'),
    
    'database_url' => env('FIREBASE_DATABASE_URL'),
];