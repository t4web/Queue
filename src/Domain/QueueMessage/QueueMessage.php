<?php

namespace T4web\Queue\Domain\QueueMessage;

use T4webDomain\Entity;

class QueueMessage extends Entity
{
    const STATUS_NEW = 1;
    const STATUS_IN_PROCESS = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_FAILED = 4;

    /**
     * @var string
     */
    protected $queueName;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $message;

    /**
     * @var string
     */
    protected $output;

    /**
     * @var string
     */
    protected $startedDt;

    /**
     * @var string
     */
    protected $finishedDt;

    /**
     * @var string
     */
    protected $createdDt;

    /**
     * @var string
     */
    protected $updatedDt;

    public function populate(array $array = [])
    {
        if ($this->id === null && empty($array['id'])) {
            if (empty($array['createdDt'])) {
                $array['createdDt'] = date('Y-m-d H:i:s');
            }
            if (isset($array['updatedDt'])) {
                unset($array['updatedDt']);
            }

        } elseif ($this->id !== null) {
            $array['updatedDt'] = date('Y-m-d H:i:s');
        }

        parent::populate($array);
    }

    public function setProcessed()
    {
        $this->status = self::STATUS_IN_PROCESS;
        $this->startedDt = date('Y-m-d H:i:s');
    }

    public function setFailed($output)
    {
        $this->status = self::STATUS_FAILED;
        $this->output = $output;
        $this->finishedDt = date('Y-m-d H:i:s');
    }

    public function setCompleted()
    {
        $this->status = self::STATUS_COMPLETED;
        $this->finishedDt = date('Y-m-d H:i:s');
    }

    /**
     * @return int
     */
    public function getExecutionTime()
    {
        return strtotime($this->finishedDt) - strtotime($this->startedDt);
    }

    /**
     * @return array
     */
    public function getMessage()
    {
        return json_decode($this->message, true);
    }

    /**
     * @return string
     */
    public function getStartedDt()
    {
        return $this->startedDt;
    }

}