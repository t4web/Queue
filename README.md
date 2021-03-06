# Queue
ZF2 Module. Message broker software implementation

### Introduction

Contain parts:
- `Server` - get messages from queue and run workers, monitor running workers count
- `Producer` - produces messages and sends them to the queue
- `Worker` - you background job
- `Storage` - messages storage

### Workflow

- Client tell `Producer` what it want to process
- `Producer` create message and put it in `Storage` and get Message Id. After this push Server to process message by Id
- `Server` check Workers count (if too much workers are running, wait) and run `Worker` with Message Id.
- `Worker` - get message from storage and process it.

```
                               Server
                    message  /        \  run worker
Client -> Producer --------->          -------------> Worker
                             \                          /
                               Storage             --->-
```

In the box we provide 2 Servers:

1. Realtime server - uses ReactPHP to run a non-blocking server that accepts messages via a socket and executes them in a background process.
2. Interval server - check storage for Messages by interval (run by cronjob)

### Configuring

Just add in you config:

```php
't4web-queue' => [

     'realtime-server' => [
         'enabled' => true,
         'hostname' => 'localhost',
         'port' => 4000,
     ],

     'queues' => [

         // Queue name
         'test-engine' => [

             // Handler class
             'handler' => EchoWorker::class,

             // count workers, optional, default 1
             'worker-count' => 1,

             // You can limit the amount of time a process takes to complete by setting a timeout (in seconds)
             // optional, default 300
             'timeout' => 300,

             // optional, default 0
             'debug-enable' => 1,
         ],
     ],
 ];
```

### Runing

```shell
$ php public/index.php queue realtime-server
```