<?php
return [
    // Path to service account JSON
    'credentials' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase-service-account.json')),

    // Your Firebase project ID
    'project_id' => env('FIREBASE_PROJECT_ID'),
];