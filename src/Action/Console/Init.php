<?php

namespace T4web\Queue\Action\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\MvcEvent;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Db\Sql\Ddl;
use Zend\Db\Sql\Sql;

class Init extends AbstractActionController
{
    /**
     * @var DbAdapter
     */
    private $dbAdapter;

    public function __construct(DbAdapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function onDispatch(MvcEvent $e)
    {
        if (!$e->getRequest() instanceof ConsoleRequest) {
            throw new RuntimeException('You can only use this action from a console!');
        }

        $table = new Ddl\CreateTable('queue_messages');
        $table->addColumn(new Ddl\Column\Integer('id', false, null, ['autoincrement' => true]));
        $table->addColumn(new Ddl\Column\Varchar('queue_name', 100));
        $table->addColumn(new Ddl\Column\Integer('status', false));
        $table->addColumn(new Ddl\Column\Varchar('options', 250));
        $table->addColumn(new Ddl\Column\Text('message', null, true));
        $table->addColumn(new Ddl\Column\Text('output', null, true));
        $table->addColumn(new Ddl\Column\Datetime('started_dt', true));
        $table->addColumn(new Ddl\Column\Datetime('finished_dt', true));
        $table->addColumn(new Ddl\Column\Datetime('created_dt', false));
        $table->addColumn(new Ddl\Column\Datetime('updated_dt', true));
        $table->addConstraint(new Ddl\Constraint\PrimaryKey('id'));

        $sql = new Sql($this->dbAdapter);

        try {
            $this->dbAdapter->query($sql->buildSqlString($table), DbAdapter::QUERY_MODE_EXECUTE);
        } catch (\Exception $e) {
            // currently there are no db-independent way to check if table exists
            // so we assume that table exists when we catch exception
        }
    }
}
