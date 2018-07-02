<?php
/**
 * Created by PhpStorm.
 * User: clement
 * Date: 13/06/18
 * Time: 14:48
 */

namespace App\Utils;


use App\Entity\UserEntity;

class UserUtils
{

    public static function getUserInfos(UserEntity $user)
    {

        return array(
            'user_id' => $user->getId(),
            'email' => $user->getEmail(),
            'fullname' => $user->getFullName(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        );
    }


}