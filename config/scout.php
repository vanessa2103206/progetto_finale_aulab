<?php

return [

    'driver' => env('SCOUT_DRIVER', 'tntsearch'),

    'prefix' => env('SCOUT_PREFIX', ''),

    'queue' => env('SCOUT_QUEUE', false),

    'chunk' => [
        'searchable' => 500,
        'unsearchable' => 500,
    ],

    'soft_delete' => false,

    'after_commit' => false,

    // SPOSTATO NELLA RADICE: Ecco la chiave esatta che TNTSearch sta cercando disperatamente!
    'tntsearch' => [
        'storage' => storage_path(),
        'fuzziness' => env('TNTSEARCH_FUZZINESS', false),
        'fuzzy' => [
            'prefix_length' => 2,
            'max_expansions' => 50,
            'distance' => 2,
        ],
        'asYouType' => false,
        'searchBoolean' => false,
        'maxDocs' => 500,
    ],

];
