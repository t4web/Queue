<?php

namespace T4web\Queue;

return [
    'QueueMessage' => [
        'entityClass' => Domain\QueueMessage\QueueMessage::class,
        'table' => 'queue_messages',
        'primaryKey' => 'id',
        'columnsAsAttributesMap' => [
            'id' => 'id',
            'queue_name' => 'queueName',
            'status' => 'status',
            'options' => 'options',
            'message' => 'message',
            'output' => 'output',
            'started_dt' => 'startedDt',
            'finished_dt' => 'finishedDt',
            'created_dt' => 'createdDt',
            'updated_dt' => 'updatedDt',
        ],
        'criteriaMap' => [
            'id' => 'id_equalTo'
        ]
    ],
];
