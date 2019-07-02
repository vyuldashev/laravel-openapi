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

    'schemas' => app_path('OpenApi/Schemas'),

];
