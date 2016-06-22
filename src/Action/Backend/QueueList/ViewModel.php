<?php

namespace T4web\Queue\Action\Backend\QueueList;

use Zend\View\Model\ViewModel as ZendViewModel;

class ViewModel extends ZendViewModel
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getVariable($name, $default = null)
    {
        if ($name != 'options') {
            return parent::getVariable($name, $default);
        }

        $options = [0 => 'All'];

        foreach ($this->config as $name=>$queue) {
            $options[$name] = $name;
        }

        return $options;
    }
}