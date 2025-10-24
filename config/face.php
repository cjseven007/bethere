<?php

return [
    'mode' => env('FACE_API_MODE', 'direct'), // direct or proxy
    'base_url' => rtrim(env('FACE_API_BASE_URL', 'http://127.0.0.1:9000'), '/'),
    'identify_path' => env('FACE_API_IDENTIFY_PATH', '/identify'),
    'threshold' => (float) env('FACE_API_THRESHOLD', 0.45),
];