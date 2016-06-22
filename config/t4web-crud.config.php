<?php

namespace T4web\Queue;

return [
    'route-generation' => [
        [
            'entity' => 'QueueMessage',
            'backend' => [
                'actions' => [
                    'list',
                ],
                'options' => [
                    'list' => [
                        'criteriaValidator' => Action\Backend\QueueList\CriteriaValidator::class,
                    ],
                ],
            ],
        ],
    ],
];
