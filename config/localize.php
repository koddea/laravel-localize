<?php

return[

    'locale' => [
        'model_name' => false,
        'fallback_locale' => null,
        'column_mappings' => [
            'code' => false,
            'name' => false,
            'order' => false,
            'is_default' => false,
        ]
    ],

    'translation' => [
        'model_name' => false,
        'column_mappings' => [
            'key' => false,
            'value' => false
        ]
    ],

    'url' => [
        'locale_segment_index' => 1,
        'hide_locale_segment' => false
    ],

    'cache_time' => 30,

];
