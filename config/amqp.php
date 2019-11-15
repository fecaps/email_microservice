<?php

return [

    'use' => 'production',

    'properties' => [

        'production' => [
            'host'                  => env('RABBITMQ_HOST', 'localhost'),
            'port'                  => env('RABBITMQ_PORT', 5672),
            'username'              => env('RABBITMQ_LOGIN', 'guest'),
            'password'              => env('RABBITMQ_PASSWORD', 'guest'),
            'vhost'                 => '/',
            'connect_options'       => [],
            'ssl_options'           => [],

            'exchange'              => env('RABBITMQ_EXCHANGE_NAME', 'amq.direct'),
            'exchange_type'         => env('RABBITMQ_EXCHANGE_TYPE', 'direct'),
            'exchange_passive'      => false,
            'exchange_durable'      => env('RABBITMQ_EXCHANGE_DURABLE', true),
            'exchange_auto_delete'  => false,
            'exchange_internal'     => false,
            'exchange_nowait'       => false,
            'exchange_properties'   => [],

            'queue_force_declare'   => false,
            'queue_passive'         => false,
            'queue_durable'         => env('RABBITMQ_QUEUE_DURABLE', true),
            'queue_exclusive'       => false,
            'queue_auto_delete'     => false,
            'queue_nowait'          => false,
            'queue_properties'      => ['x-ha-policy' => ['S', 'all']],

            'consumer_tag'          => '',
            'consumer_no_local'     => false,
            'consumer_no_ack'       => false,
            'consumer_exclusive'    => false,
            'consumer_nowait'       => false,
            'timeout'               => 0,
            'persistent'            => true,

            'qos'                   => false,
            'qos_prefetch_size'     => 1,
            'qos_prefetch_count'    => 1,
            'qos_a_global'          => false
        ],

    ],

];
