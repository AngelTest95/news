<?php

namespace app\models\repository;

use app\models\data\UserData;

class UserRepository extends AbstractRepository
{
    /**
     * @param string $email
     * @return boolean
     */
    public function emailExists($email)
    {
        $query = UserData::find()->where(['email' => $email]);
        return $this->build($query, self::EXISTS);
    }

    /**
     * @param string $username
     * @return boolean
     */
    public function usernameExists($username)
    {
        $query = UserData::find()->where(['username' => $username]);
        return $this->build($query, self::EXISTS);
    }

    /**
     * @param integer $userId
     * @return UserData|null
     */
    public function getById($userId)
    {
        $query = UserData::find()->where(['id' => $userId]);
        return $this->build($query, self::ONE);
    }

    /**
     * @param string $identifier
     * @param string $password
     * @return UserData|null
     */
    public function getByIdentifierAndPassword($identifier, $password)
    {
        $query = UserData::find()
            ->where(['password' => $password])
            ->andWhere(['or',
                ['username' => $identifier],
                ['email' => $identifier]
            ]);
        return $this->build($query, self::ONE);
    }
}