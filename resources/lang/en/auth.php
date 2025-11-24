<?php

return [
    'errors' => [
        'validation' => [
            'required_fields' => 'Authentication requires all fields to be completed.',
            'invalid_format' => 'Invalid input format provided.',
            'password_requirements' => 'Password must meet the required security criteria.',
            'invalid_token' => 'Invalid or expired authentication token.',
        ],
        'auth' => [
            'invalid_credentials' => 'The provided credentials are incorrect.',
            'unauthorized' => 'Access denied. Insufficient permissions.', // for 403
            'unauthenticated' => 'Please login to access this resource.', // for 401
            'account_locked' => 'Account temporarily locked due to multiple failed attempts.',
            'too_many_attempts' => 'Too many login attempts. Please try again in :minutes minutes.',
            'session_expired' => 'Your session has expired. Please log in again.',
            'token_expired' => 'Authentication token has expired.',
            'concurrent_session' => 'New login detected from another device.',
            'already_authenticated' => 'You are already authenticated.',
        ],
        'system' => [
            'server_error' => 'An unexpected error occurred. Please try again later.',
            'maintenance' => 'System is currently under maintenance. Please try again later.',
            'service_unavailable' => 'Authentication service temporarily unavailable.',
        ],
        'security' => [
            'suspicious_activity' => 'Unusual activity detected. Additional verification required.',
            'ip_blocked' => 'Access denied from current IP address.',
            'location_change' => 'Login attempt from new location detected.',
            'device_not_recognized' => 'Unrecognized device detected.',
        ]
    ],
    'success' => [
        'login' => 'Authentication successful.',
        'logout' => 'You have been successfully logged out.',
        'token_refresh' => 'Authentication token successfully refreshed.',
        'password_reset' => 'Password has been successfully reset.',
        'verification' => 'Account successfully verified.',
    ],
    'info' => [
        'password_expiring' => 'Your password will expire in :days days.',
        'verification_required' => 'Please verify your account.',
        'mfa_required' => 'Two-factor authentication required.',
        'session_timeout' => 'Your session will expire in :minutes minutes.',
    ]
];
