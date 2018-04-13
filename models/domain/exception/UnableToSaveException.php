<?php

namespace app\models\domain\exception;

/**
 * Represents error that occurred during execution of campaign action
 */
class UnableToSaveException extends \yii\base\Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Unable to save';
    }
}
