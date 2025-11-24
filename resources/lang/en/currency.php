<?php

return [
    'errors' => [
        'validation' => [
            'required' => [
                'code' => 'Currency code is required.',
                'name' => 'Currency name is required.',
                'buy_rate' => 'Buy rate is required.',
                'sell_rate' => 'Sell rate is required.',
                'icon' => 'Currency icon is required.',
                'fields' => 'At least one field must be provided for update.',
            ],
            'no_updates' => 'No fields provided for update. Please provide at least one field to update.',
            'format' => [
                'code' => 'Invalid currency code format.',
                'name' => 'Invalid currency name format.',
            ],
            'numeric' => [
                'buy_rate' => 'Buy rate must be a valid number.',
                'sell_rate' => 'Sell rate must be a valid number.',
            ],
            'icon' => [
                'process_failed' => 'Failed to process icon file.',
            ],
            'unique' => [
                'code' => 'This currency code already exists.',
            ],
        ],
        'not_found' => 'Currency not found.',
        'unauthorized' => 'You are not authorized to perform this action.',
        'create_failed' => 'Failed to create currency.',
        'update_failed' => 'Failed to update currency.',
        'delete_failed' => 'Failed to delete currency.',
        'system_error' => 'An unexpected error occurred.',
    ],
    'success' => [
        'created' => 'Currency created successfully.',
        'updated' => 'Currency updated successfully.',
        'deleted' => 'Currency deleted successfully.',
        'retrieved' => 'Currency retrieved successfully.',
        'no_records' => 'No currencies found.',
        'deleted_all' => 'All currencies deleted successfully.',
    ],
];
