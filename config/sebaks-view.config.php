<?php

namespace T4web\Queue;

use T4web\Admin\ViewModel;

return [
    'contents' => [
        'admin-QueueMessage-list' => [
            'extend' => 'admin-list',
            'data' => [
                'static' => [
                    'title' => 'Queue',
                    'icon' => 'fa-sort-amount-asc',
                ],
            ],
            'children' => [
                'filter' => [
                    'extend' => 't4web-admin-filter',
                    'template' => 'queue/block/filter',
                    'data' => [
                        'static' => [
                            'horizontal' => true,
                        ],
                    ],
                    'children' => [
                        [
                            'template' => 'queue/block/queue-form-element-select',
                            'viewModel' => Action\Backend\QueueList\ViewModel::class,
                            'capture' => 'form-element',
                            'data' => [
                                'static' => [
                                    'name' => 'queueName_equalTo',
                                    'label' => 'Queue',
                                ],
                                'fromParent' => [
                                    'queueName_equalTo' => 'value'
                                ],
                            ],
                        ],
                        [
                            'template' => 't4web-admin/block/form-element-select',
                            'capture' => 'form-element',
                            'data' => [
                                'static' => [
                                    'name' => 'status_equalTo',
                                    'label' => 'Status',
                                    'options' =>  [
                                        '' => 'All',
                                        Domain\QueueMessage\QueueMessage::STATUS_NEW => 'New',
                                        Domain\QueueMessage\QueueMessage::STATUS_IN_PROCESS => 'In process',
                                        Domain\QueueMessage\QueueMessage::STATUS_COMPLETED => 'Completed',
                                        Domain\QueueMessage\QueueMessage::STATUS_FAILED => 'Failed',
                                    ],
                                ],
                                'fromParent' => [
                                    'status_equalTo' => 'value'
                                ],
                            ],
                        ],
                        'form-button-clear' => [
                            'data' => [
                                'static' => [
                                    'routeName' => 'admin-QueueMessage-list',
                                ],
                            ],
                        ],
                    ],
                ],
                'table' => [
                    'template' => 'queue/block/table',
                    'viewModel' => ViewModel\TableViewModel::class,
                    'children' => [
                        'table-head-row' => [
                            'template' => 't4web-admin/block/table-tr',
                            'data' => [
                                'fromParent' => 'rows',
                            ],
                            'children' => [
                                'table-th-id' => [
                                    'template' => 't4web-admin/block/table-th',
                                    'capture' => 'table-td',
                                    'data' => [
                                        'static' => [
                                            'value' => 'Id',
                                            'width' => '5%',
                                            'align' => 'right',
                                        ],
                                    ],
                                ],
                                'table-th-queue' => [
                                    'template' => 't4web-admin/block/table-th',
                                    'capture' => 'table-td',
                                    'data' => [
                                        'static' => [
                                            'value' => 'Queue',
                                            'width' => '10%',
                                        ],
                                    ],
                                ],
                                'table-th-handler' => [
                                    'template' => 't4web-admin/block/table-th',
                                    'capture' => 'table-td',
                                    'data' => [
                                        'static' => [
                                            'value' => 'Handler',
                                            'width' => '50%',
                                        ],
                                    ],
                                ],
                                'table-th-createdDt' => [
                                    'template' => 't4web-admin/block/table-th',
                                    'capture' => 'table-td',
                                    'data' => [
                                        'static' => [
                                            'value' => 'Created',
                                            'width' => '15%',
                                        ],
                                    ],
                                ],
                                'table-th-status' => [
                                    'template' => 't4web-admin/block/table-th',
                                    'capture' => 'table-td',
                                    'data' => [
                                        'static' => [
                                            'value' => 'Status',
                                            'width' => '10%',
                                        ],
                                    ],
                                ],
                                'table-th-actions' => [
                                    'template' => 't4web-admin/block/table-th',
                                    'capture' => 'table-td',
                                    'data' => [
                                        'static' => [
                                            'value' => 'Actions',
                                            'width' => '10%',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'table-body-row' => [
                            'viewModel' => ViewModel\TableRowViewModel::class,
                            'template' => 'queue/block/table-tr',
                            'data' => [
                                'fromParent' => 'row',
                            ],
                            'children' => [
                                'table-td-id' => [
                                    'template' => 't4web-admin/block/table-td',
                                    'capture' => 'table-td',
                                    'data' => [
                                        'fromParent' => ['id' => 'value'],
                                        'static' => [
                                            'align' => 'right',
                                        ],
                                    ],
                                ],
                                'table-td-queue' => [
                                    'template' => 't4web-admin/block/table-td',
                                    'capture' => 'table-td',
                                    'data' => [
                                        'fromParent' => ['queueName' => 'value'],
                                    ],
                                ],
                                'table-td-handler' => [
                                    'template' => 'queue/block/handler-table-td',
                                    'capture' => 'table-td',
                                    'data' => [
                                        'fromParent' => [
                                            'options' => 'options',
                                            'startedDt' => 'startedDt',
                                            'finishedDt' => 'finishedDt',
                                        ],
                                    ],
                                ],
                                'table-td-createdDt' => [
                                    'template' => 't4web-admin/block/table-td',
                                    'capture' => 'table-td',
                                    'data' => [
                                        'fromParent' => ['createdDt' => 'value'],
                                    ],
                                ],
                                'table-td-status' => [
                                    'template' => 't4web-admin/block/table-td-labeled',
                                    'capture' => 'table-td',
                                    'data' => [
                                        'fromParent' => ['status' => 'value'],
                                        'static' => [
                                            'colorValueMap' => [
                                                Domain\QueueMessage\QueueMessage::STATUS_NEW => 'info',
                                                Domain\QueueMessage\QueueMessage::STATUS_IN_PROCESS => 'default',
                                                Domain\QueueMessage\QueueMessage::STATUS_COMPLETED => 'success',
                                                Domain\QueueMessage\QueueMessage::STATUS_FAILED => 'danger',
                                            ],
                                            'textValueMap' => [
                                                Domain\QueueMessage\QueueMessage::STATUS_NEW => 'New',
                                                Domain\QueueMessage\QueueMessage::STATUS_IN_PROCESS => 'In process',
                                                Domain\QueueMessage\QueueMessage::STATUS_COMPLETED => 'Completed',
                                                Domain\QueueMessage\QueueMessage::STATUS_FAILED => 'Failed',
                                            ],
                                        ],
                                    ],
                                ],
                                'table-td-buttons' => [
                                    'template' => 't4web-admin/block/table-td-buttons',
                                    'capture' => 'table-td',
                                    'data' => [
                                        'fromParent' => 'id',
                                    ],
                                    'children' => [
                                        'link-button-edit' => [
                                            'viewModel' => ViewModel\EditButtonViewModel::class,
                                            'template' => 'queue/block/show-button',
                                            'capture' => 'button',
                                            'data' => [
                                                'static' => [
                                                    'text' => 'Show',
                                                    'color' => 'primary',
                                                    'size' => 'xs',
                                                    'icon' => 'search',
                                                ],
                                                'fromParent' => 'id',
                                            ],
                                        ],
                                    ]
                                ],
                            ],
                        ],
                    ],
                    'childrenDynamicLists' => [
                        'table-body-row' => 'rowsData',
                    ],
                    'data' => [
                        'static' => [
                        ],
                        'fromGlobal' => [
                            'result' => 'rowsData',
                        ],
                    ],
                ],
                'paginator' => [
                    'extend' => 't4web-admin-paginator',
                    'viewModel' => 'Queue\QueueMessage\ViewModel\PaginatorViewModel',
                ],
            ],
        ],
    ],
    'blocks' => [
        't4web-admin-sidebar-menu' => [
            'children' => [
                [
                    'extend' => 't4web-admin-sidebar-menu-item',
                    'capture' => 'item',
                    'data' => [
                        'static' => [
                            'label' => 'Queue',
                            'route' => 'admin-QueueMessage-list',
                            'icon' => 'fa-sort-amount-asc',
                        ],
                    ],
                    'children' => [],
                ],
            ],
        ],
    ],
];

