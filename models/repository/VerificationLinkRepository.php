<?php

namespace app\models\repository;

use app\models\data\VerificationLinkData;

class VerificationLinkRepository extends AbstractRepository
{
    /**
     * @param string $token
     * @return VerificationLinkData
     */
    public function getByToken($token)
    {
        $query = VerificationLinkData::find()->where(['verification_token' => $token]);
        return $this->build($query, self::ONE);
    }
}