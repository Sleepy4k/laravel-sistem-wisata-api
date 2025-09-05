<?php

//Default values for the Cacheable trait - Can be overridden per model
return [
    //How long should cache last in general?
    'ttl' => 13140000, // 5 months in seconds
    //By what should cache entries be prefixed?
    'prefix' => 'cacheable',
    //What is the identifying, unique column name?
    'identifier' => 'id',
    //Do you need logging?
    'logging' => [
        'channel' => 'cacheable',
        'enabled' => config('app.debug'),
        'level' => 'debug',
    ],
];
