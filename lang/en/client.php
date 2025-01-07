<?php

return [
    'menu_seans' => 'My Services',
    'menu_appointment' => 'My Appointments',
    'menu_payments' => 'My Installments',
    'menu_offer' => 'My Offers',
    'menu_referans' => 'Invite Friends',
    'menu_coupon' => 'My Coupons',
    'menu_package' => 'Packages',
    'menu_earn' => 'Use & Earn',
    'menu_invoice' => 'My Invoices',
    'menu_profil' => 'Profile',

    'page_seans' => [
        'title' => 'My Services',
        'subtitle' => 'View and manage your service sessions',
        'stats' => [
            'total_services' => 'Total Services',
            'ongoing_services' => 'Ongoing',
            'completed_services' => 'Completed'
        ],
        'sections' => [
            'ongoing' => [
                'title' => 'Ongoing Services',
                'count' => ':count services'
            ],
            'completed' => [
                'title' => 'Completed Services',
                'count' => ':count services'
            ]
        ],
        'service' => [
            'sessions' => ':count sessions',
            'category' => 'Category'
        ],
        'loading' => 'Loading...'
    ],

    'page_seans_add_seans' => 'Add Service',

    'page_appointment_subtitle' => 'Tap on the appointment you want to take action',
    'page_appointment_create' => 'Create Appointment',
    'page_appointment_review_tip' => 'REVIEW',

    'page_seans_category' => 'Category',
    'page_seans_remaining' => 'Remaining',
    'page_seans_total' => 'Total',

    'waiting' => 'Waiting',
    'awaiting_approve' => 'Awaiting Approval',
    'confirmed' => 'Confirmed',
    'rejected' => 'Rejected',
    'cancel' => 'Cancelled',
    'merkez' => 'At the Center',
    'late' => 'Late',
    'forwarded' => 'Forwarded',
    'finish' => 'Finished',
    'teyitli' => 'Verified',

    'menu_shop' => 'ONLINE SHOP',

    'loading_overlay_message' => 'Please wait...',
    'page_appointment_create' => 'Create Appointment',
    'page_appointment_subtitle' => 'Follow the steps to create a new appointment',
    'appointment_step_titles' => ['Appointment', 'Branch', 'Category', 'Service', 'Date', 'Complete'],
    'appointment_type_date' => [
        'title' => 'Select Date and Time',
        'description' => 'Create an appointment by selecting a specific date and time.',
        'example' => 'Example: :date',
    ],
    'appointment_type_range' => [
        'title' => 'Specify Date Range',
        'description' => 'Define a date range to create an appointment slot.',
        'example' => 'Example: See availability between :start_date and :end_date',
    ],
    'appointment_type_multi' => [
        'title' => 'Select Multiple Dates',
        'description' => 'Choose multiple dates to create appointments.',
        'example' => 'Example: Check availability on :days.',
    ],
    'branch_selection_title' => 'Branch Selection',
    'branch_selection_description' => 'Select the branch for your appointment',
    'service_category_selection_title' => 'Select Category',
    'service_category_selection_description' => 'Click to view services in this category',
    'room_selection_title' => 'Room Selection',
    'service_selection_title' => 'Select Services',
    'service_remaining' => ':remaining remaining',
    'appointment_date_selection_title' => 'Select Date',
    'appointment_notes_label' => 'Appointment Notes',
    'appointment_create_button' => 'Create Appointment',
    'appointment_range_find_button' => 'Find Available Slots',
    'appointment_complete_title' => 'Appointment Created',
    'appointment_complete_message' => 'Your appointment has been created successfully.',
    'navigation_back' => 'Back',
    'navigation_next' => 'Next',

    'page_appointment' => [
        'subtitle' => 'View and manage your appointments.',
        'create' => 'New Appointment',
        'stats' => [
            'total' => 'Total Appointments',
            'pending' => 'Pending',
            'completed' => 'Completed',
        ],
    ],

    'appointment' => [
        'create' => [
            'title' => 'Book Appointment',
            'subtitle' => 'Start by selecting appointment type',
        ],
        'steps' => [
            '1' => 'Type',
            '2' => 'Category',
            '3' => 'Service',
            '4' => 'Date',
        ],
        'type' => [
            'title' => 'Appointment Type',
            'range' => [
                'title' => 'Range',
                'description' => 'Select date range',
            ],
            'date' => [
                'title' => 'Single',
                'description' => 'Single session',
            ],
            'multi' => [
                'title' => 'Multiple',
                'description' => 'Multiple sessions',
            ],
        ],
        'category' => [
            'title' => 'Select Category',
            'subtitle' => 'Choose the category you want to book',
        ],
        'service' => [
            'title' => 'Select Service',
            'subtitle' => 'Choose the services you want',
            'remaining' => 'Remaining: :count',
            'duration' => 'minutes',
            'select_room' => 'Select room',
            'continue' => 'Continue',
            'room' => 'Room',
        ],
        'date' => [
            'title' => 'Select Date',
            'subtitle' => 'Choose your preferred date',
            'select' => 'Select date',
            'message' => 'Add note',
            'create' => 'Create Appointment',
        ],
        'summary' => [
            'type' => 'Appointment Type',
            'category' => 'Category',
            'services' => 'Selected Services',
            'total_duration' => 'Total Duration',
            'room' => 'Room'
        ],
        'success' => 'Your appointment has been created successfully',
        'errors' => [
            'initialization' => 'Could not initialize appointment creation',
            'service' => 'Please select at least one service',
            'date' => 'Please select a date',
            'room' => 'Please select a room',
        ],
        'back' => 'Back',
    ],

    'loading' => 'Loading...',

    // Steps
    'step_type_title' => 'Appointment Type Selection',
    'step_branch_title' => 'Branch Selection',
    'step_category_title' => 'Category Selection',
    'step_service_title' => 'Service Selection',
    'step_date_title' => 'Date Selection',
    'step_complete_title' => 'Completed',

    // Appointment Types
    'type_date' => 'Single Appointment',
    'type_range' => 'Date Range',
    'type_multi' => 'Multiple Dates',
    'type_description' => 'Select appointment type',

    // Selection Titles
    'branch_selection_title' => 'Branch Selection',
    'branch_selection_description' => 'Select the branch for your appointment',
    'service_category_selection_title' => 'Select Category',
    'service_category_selection_description' => 'Click to view services in this category',
    'service_selection_title' => 'Select Services',
    'room_selection_title' => 'Room Selection',
    'appointment_date_selection_title' => 'Select Date',

    // Service Information
    'service_remaining' => 'Remaining: :remaining',
    'service_duration' => ':duration min',

    // Form Fields
    'appointment_notes_label' => 'Appointment Notes',
    'appointment_date_label' => 'Appointment Date',

    // Buttons
    'navigation_next' => 'Next',
    'navigation_back' => 'Back',
    'appointment_create_button' => 'Create Appointment',
    'appointment_range_find_button' => 'Find Available Slots',

    // Success/Error Messages
    'appointment_complete_title' => 'Appointment Created',
    'appointment_complete_message' => 'Your appointment has been created successfully.',
    'error_service_required' => 'Please select a service.',
    'error_room_required' => 'Please select a room.',
    'error_date_required' => 'Please select a date.',
    'error_duration' => 'Duration could not be calculated.',
    'error_try_again' => 'Please try again.',
    'success_appointment_pending' => 'You will be notified when your appointment request is approved.',
    'success_appointment_created' => 'Your appointment has been created.',

    // Available Slots
    'appointment_available_slots_title' => 'Available Slots',
    'error_no_available_slots' => 'No available appointment slots found for the selected date range',

    // Summary Section
    'appointment_summary_title' => 'Appointment Summary',
    'appointment_summary_category' => 'Category',
    'appointment_summary_services' => 'Selected Services',
    'appointment_summary_total_duration' => 'Total Duration',
    'appointment_summary_date' => 'Date',

    // General Errors
    'error_try_again' => 'An error occurred, please try again',

    'service_duration_minutes' => 'minutes',
    'service_remaining' => ':remaining remaining',

    'appointment_notes_placeholder' => 'You can write any notes about your appointment here...',
];
