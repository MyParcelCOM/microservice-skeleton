<?php

declare(strict_types=1);

return [
    'region'    => env('AWS_REGION', 'eu-central-1'),
    'version'   => '2010-03-31',
    'statuses' => [
        'topic_arn' => env('SNS_STATUSES_TOPIC_ARN'),
    ],
];
