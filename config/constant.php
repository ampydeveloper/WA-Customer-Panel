<?php

return [
    'roles' => [
        'Admin' => 1,
        'Admin_Manager' => 2,
        'Driver' => 3,
        'Customer' => 4,
        'Customer_Manager' => 5,
        'Haulers' => 6,
    ],
    'vehicle_type' => [
        'truck' => 1,
        'skidsteer' => 2
    ],
    'vehicle_status' => [
        'available' => 1,
        'unavailable' => 2
    ],
    'driver_status' => [
        'available' => 1,
        'unavailable' => 2
    ],
    'payment_methods' => [
        'stripe' => 1,
        'authorizenet' => 2
    ],
    'payment_status_reverse' => [
        'succeeded' => 1
    ],
    'repeating_job' => [
        'no' => 1,
        'yes' => 2
    ],
    'payment_history' => [
        'pending' => 0,
        'complete' => 1
    ],
    'login_providers' => [
        'google' => 'google',
        'facebook' => 'facebook'
    ],
    'hubspot' => [
        'api_url' => 'https://api.hubapi.com/contacts/v1/contact?hapikey=',
        'api_key' => 'c6fd7eb1-da62-4717-9ceb-8c6516b1831f'
    ],
    'service_type' => [
        'by_weight' => 1,
        'by_round' => 2,
    ],
    'service_for' => [
        'customer' => 4,
        'haulers' => 6,
    ],
    'payment_mode' => [
        'online' => 0,
        'cheque' => 1,
        'cash' => 2
    ],
    'job_status' => [
        'open' => 0,
        'assigned' => 1,
        'completed' => 2,
        'close' => 3,
        'cancelled' => 4,
    ],
    'payment_status' => [
        'unpaid' => 0,
        'paid' => 1,
    ],
    'quick_book' => [
        'Not_Sync' => 0,
        'Sync' => 0,
    ],
    'driver_type' => [
        'truck_driver' => 1,
        'skidsteer' => 2,
    ],
    'salary_type' => [
        'per_hour' => 1,
        'per_month' => 2,
    ],
    'time_slots' => [
        'total_time_slots' => 11
    ],
    'range_cover' => [
        'distance' => 4
    ],
    'warehouse' => [
        'lat' => '31.23',
        'lon' => '34.34'
    ]
];
