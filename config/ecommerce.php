<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tax Rate
    |--------------------------------------------------------------------------
    |
    | The default tax rate (KDV) applied to all orders.
    | Value should be decimal (e.g., 0.18 for 18%)
    |
    */
    'tax_rate' => env('TAX_RATE', 0.18),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | The default currency code for the store
    |
    */
    'currency' => env('CURRENCY', 'TL'),
    'currency_symbol' => env('CURRENCY_SYMBOL', '₺'),

    /*
    |--------------------------------------------------------------------------
    | Country Settings
    |--------------------------------------------------------------------------
    |
    | Default country and locale settings
    |
    */
    'country_code' => env('COUNTRY_CODE', 'TR'),
    'country_name' => env('COUNTRY_NAME', 'Turkey'),

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Default pagination settings for products and orders
    |
    */
    'products_per_page' => env('PRODUCTS_PER_PAGE', 12),
    'orders_per_page' => env('ORDERS_PER_PAGE', 20),

    /*
    |--------------------------------------------------------------------------
    | Stock Settings
    |--------------------------------------------------------------------------
    |
    | Low stock threshold for notifications
    |
    */
    'low_stock_threshold' => env('LOW_STOCK_THRESHOLD', 5),

    /*
    |--------------------------------------------------------------------------
    | Order Settings
    |--------------------------------------------------------------------------
    |
    | Order related configurations
    |
    */
    'order_number_prefix' => env('ORDER_NUMBER_PREFIX', 'ORD'),

    // Valid order status values
    'order_statuses' => [
        'pending' => 'Beklemede',
        'processing' => 'İşleniyor',
        'shipped' => 'Kargoya Verildi',
        'delivered' => 'Teslim Edildi',
        'cancelled' => 'İptal Edildi',
    ],

    // Valid status transitions
    'order_status_transitions' => [
        'pending' => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered'],
        'delivered' => [],
        'cancelled' => [],
    ],

    // Payment status values
    'payment_statuses' => [
        'pending' => 'Ödeme Bekleniyor',
        'paid' => 'Ödendi',
        'failed' => 'Ödeme Başarısız',
        'refunded' => 'İade Edildi',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Settings
    |--------------------------------------------------------------------------
    |
    | Image upload and processing settings
    |
    */
    'image_max_size' => env('IMAGE_MAX_SIZE', 2048), // KB
    'image_allowed_mimes' => ['jpeg', 'png', 'jpg', 'gif', 'webp'],
    'image_min_width' => env('IMAGE_MIN_WIDTH', 100),
    'image_min_height' => env('IMAGE_MIN_HEIGHT', 100),

];
