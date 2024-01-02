<?php

return [
    'name' => 'Passport',
    'passport_types' => ['P', 'O', 'D'], // [personal, official, diplomatic]
    'passport_statuses' => ['1', '2', '3', '4'], // [approved, expired, to be renewed <= 30 days, to be renewed <= 90 days]
];
