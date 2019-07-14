<?php

namespace App\Model;

use Nette,
     Nette\Database\Context,
     Nette\Security\Passwords;

class UsersManager extends DatabaseManager{

    use Nette\SmartObject;

    const
	TABLE_NAME = 'users',
	COLUMN_ID = 'id',
	COLUMN_NAME = 'username',
	COLUMN_PASSWORD_HASH = 'password',
	COLUMN_EMAIL = 'email',
	COLUMN_CREATE_DATE = 'created_at',
        COLUMN_ROLE = 'roles_id';


    public function getPublicUsers(){
        return $this->database->table(self::TABLE_NAME);
    }

    public function getNameColumns(){
        return $this->database->getConnection()->GetSupplementalDriver();
    }

    public function setAddUser($values){
        $values->password = Passwords::hash($values->password);
        $this->database->table(self::TABLE_NAME)->insert($values);
    }

    public function getPublicUsersRole(){
        return $this->getPublicUsers()->select('users.* , :roles.name')->joinWhere(':roles', 'users.roles_id = :roles.id');

        /*
        return $this->database->table('news')
            ->select('news.*, SUM(:evaluation.inch_up) AS inch_up , SUM(:evaluation.inch_down) AS inch_down')
            ->joinWhere(':evaluation',':evaluation.news_id = news.id')
            ->group('news.id');
        */
    }



}