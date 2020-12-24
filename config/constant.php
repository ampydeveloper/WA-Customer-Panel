<?php

return [
    'roles' => [
        'Admin' => 1,
        'Admin_Manager' => 2,
        'Driver' => 3,
        'Customer' => 4,
        'Customer_Manager' => 5,
        'Haulers' => 6,
        'Hauler_driver' => 7,
        'Mechanic' => 8
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
        'by_bucket' => 3
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
    'payment_mode_inverse' => [
        0 => 'online',
        1 => 'cheque',
        2 => 'cash',
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
        'Sync' => 1,
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
    'service_slot_type' => [
        'morning' => 1,
        'afternoon' => 2,
        'evening' => 3
    ],
    'time_taken_to_complete_service' => [
        '15_mins' => 1,
        '30_mins' => 2,
        '45_mins' => 3,
        '60_mins' => 4,
        '75_mins' => 5,
        '90_mins' => 6,
    ],
    'time_taken_to_complete_service_reverse' => [
        1 =>'15',
        2 =>'30',
        3 =>'45',
        4 =>'60',
        5 =>'75',
        6 =>'90',
    ],'zipcodes' => [
        0 => '62365',
        1 => '52869',
        2 => '12345',
        3 => '12234',
        4 => '78451',
        5 => '16006',
    ],
    'days' => [
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thusday',
        5 => 'friday',
        6 => 'saturday',
        7 => 'sunday',
    ],
    'warehouse' => [
        'lat' => '26.695145',
        'lon' => '-80.244859'
    ],
    'dumping_area' => [
        'lat' => '31.30',
        'lon' => '34.36'
    ],
    'repeating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
    
    'card_brands' => [
        'Amex' => 3,
        'Visa' => 4,
        'MasterCard' => 5,
        'DiscoverCard' => 6
    ],
    'card_brands_reversed' => [
        3 => 'Amex',
        4 => 'Visa',
        5 => 'MasterCard',
        6 => 'DiscoverCard',
    ],
    'quickbooks' => [
        'client_id' => env('QUICKBCLIENTID', 'ABXFvdnF62facDvQgjmidJS2gxmNJDlSyZ2NNo9s1dlFBHDOCY'),
        'client_secret' => env('QUICKBCLIENTSECRET', 'zchqARWLDfsY1SErpA1Bb80k4uHf2xrfIosivs3T'),
        'baseUrl' => env('QUICKBENV', 'Development'),
        'realm_id' => env('QUICKBREALMID', '4620816365155385730')
    ],
    'customer_activities' => [
        'pickup_created' => 'Pickup is created.',
        'pickup_updated' => 'Pickup is updated.',
        'pickup_cancelled' => 'Pickup is cancelled.',
    ]
]; 
