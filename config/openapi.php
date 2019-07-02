<?php

return [

    'info' => [
        'title' => config('app.name'),
        'version' => '1.0.0',
    ],

    'servers' => [
        [
            'url' => url()->to('/'),
        ],
    ],

    'responses' => app_path('OpenApi/Responses'),
    'schemas' => app_path('OpenApi/Schemas'),

];
