<?php

namespace app\models\data;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 *
 * @property NewsData[] $news
 */
class UserData extends \yii\db\ActiveRecord
{
    const PASSWORD_SALT = '123h9u0dfhapudhqwahfupdiagsdoqwgd';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['username', 'email', 'password'], 'string', 'max' => 60],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(NewsData::className(), ['user_id' => 'id']);
    }

    public static function generateSaltedPassword($password)
    {
        return sha1($password . self::PASSWORD_SALT);
    }
}
