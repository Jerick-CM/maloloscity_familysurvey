<?php

return [
    'itinerary' => [
        'create' => '`:name` created an itinerary control number of :control_number.',
        'update' => '`:name` updated an itinerary id of :itinerary_id.',
        'delete' => '`:name` deleted an itinerary id of :delete_id .',
        'delete_multiple' => '`:name` deleted  itinerary id of :deleted_ids .',
    ],
    'user' => [
        'login' => '`:email` has logged in.',
        'logout' => '`:email` has logged out.',
        'delete' => '`:email` has deleted :delete_email.',
        'create' => '`:email` has created :create_email.',
        'update' => '`:email` has updated :email_update to role :is_admin and name :name .',

        'changepassword' => '`:email` has changed password of :email_change.',
        'resetpassword' => '`:email` has changed password of :email_change.',
    ],
    'business' => [
        'delete' => '`:email` has deleted :business_name.',
        'create' => '`:email` has created :business_name.',
        'update' => '`:email` has updated :business_name.',
    ],
    'checklist' => [
        'update' => '`:email` has updated the checklist.',
    ],
    'roles' => [
        'update' => '`:email` has updated the roles.',
    ]
];
