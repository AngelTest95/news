<?php

namespace app\models\data;

use Yii;

/**
 * This is the model class for table "mail_queue".
 *
 * @property int $id
 * @property string $recipient
 * @property string $from
 * @property string $subject
 * @property string $body
 */
class MailQueueData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mail_queue';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recipient', 'from', 'subject', 'body'], 'required'],
            [['recipient', 'body'], 'string'],
            [['from'], 'string', 'max' => 255],
            [['subject'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recipient' => 'Recipient',
            'from' => 'From',
            'subject' => 'Subject',
            'body' => 'Body',
        ];
    }
}
