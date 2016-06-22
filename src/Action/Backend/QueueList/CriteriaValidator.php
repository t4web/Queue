<?php

namespace T4web\Queue\Action\Backend\QueueList;

use Sebaks\Controller\EmptyValidator;

class CriteriaValidator extends EmptyValidator
{
    /**
     * @return mixed
     */
    public function getValid()
    {
        $data = parent::getValid();

        foreach ($data as $name => $value) {
            if (!empty($value)) {
                $valid[$name] = $value;
            }
        }

        return $valid;
    }
}
