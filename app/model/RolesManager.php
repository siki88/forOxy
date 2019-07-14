<?php

namespace App\Model;

use Nette,
     Nette\Database\Context;

class RolesManager extends DatabaseManager{

    use Nette\SmartObject;
    
    const
	TABLE_NAME = 'roles',
	COLUMN_ID = 'id',
	COLUMN_NAME = 'name',
	COLUMN_CREATE_DATE = 'created_at';     

    public function getPublicRoles(){
        return $this->database->table(SELF::TABLE_NAME);
    }

    public function setRolesId($roles_id){
        return $this->getPublicRoles()->get($roles_id);
    }
}