<?php

namespace App\Model;

use Nette,
     Nette\Database\Context,
     Nette\Utils\DateTime,
     Nette\Utils\Validators,
     Nette\Utils\Random;


class TokenManager extends DatabaseManager{

    use Nette\SmartObject;
    
    const
	TABLE_NAME = 'tokens',
	COLUMN_ID = 'id',
	COLUMN_TOKEN = 'token',
        COLUMN_USER_ID = 'user_id',
	COLUMN_CREATE_DATE = 'create_at',
        COLUMN_UPDATE_DATE = 'update_at',
        COLUMN_EXPIRATE_DATE = 'expirate_at';    

    private $usersManager;

    public function __construct(UsersManager $usersManager){
        $this->usersManager = $usersManager;
    }

    private function getToken(){
        return $this->database->table(self::TABLE_NAME);
    }


    /*vyhledá zda má uživatel token,
         pokud nemá -> založí nový,
         pokud má -> updatuje expirační datum datum, a to :
                    pokud je expirační datum menší než aktuální -> pouze prodlouží expirační datum
                    pokud je expirační datum větší než aktuální -> vygeneruje nový token a prodlouží expirační datum
    */
    public function setTokenUserId($user_id){
        //controll exists old token
        $controll = $this->getToken()->select('id,expirate_at')->where(self::COLUMN_USER_ID, $user_id)->fetch();
        if($controll){ // exists user token -> update
            //pokud existuje token pro user_id, ale expirace je stará - vygenerujeme nový token
            $status = 'update';
            //kontrola expirace
            if(DateTime::from('0')->format('Y-m-d H:m:s') > $controll->expirate_at->format('Y-m-d H:m:s')){
                $status = 'insert';
            }

             $this->expirateExtended($controll->id, $user_id, $status);

            return $this->getToken()->select(self::COLUMN_TOKEN)->get($controll->id)->toArray();
        }else{ // none exists -> insert
            $tokenTable = $this->getToken()->insert($this->generateTableToken($user_id,'insert'));
            return $this->getToken()->select(self::COLUMN_TOKEN)->get($tokenTable->id)->toArray();
        }
    }



    public function setTokenToken($token){
        return $this->getToken()->where(self::COLUMN_TOKEN, $token)->fetch();
    }

    //vyhledá zda existuje token, pokud ano, prodlouží expiraci, pokud ne vyhodí false
    public function getControlExpiration($token){
        $tokenTable = $this->setTokenToken($token);
        if($tokenTable && Validators::isNumericInt($tokenTable->id)){
            $this->expirateExtended($tokenTable->id, $tokenTable->user_id,'update');
            return $tokenTable;
        }else{
            return false;
        }
    }


    // prodloužení platnosti, a za určitých podmínek změna tokenu
    private function expirateExtended($id,$user_id, $status){
        $this->getToken()->where(self::COLUMN_ID,$id)->update($this->generateTableToken($user_id, $status));
    }


    private function generateTableToken($user_id, $status){
        $actualDate = DateTime::from('0');
        $createTokenTable = [
            self::COLUMN_USER_ID => $user_id,
            self::COLUMN_UPDATE_DATE => $actualDate->format('Y-m-d H:m:s'),
            self::COLUMN_EXPIRATE_DATE => $actualDate->modify('+12 hours')->format('Y-m-d H:m:s')
        ];
        if($status == 'insert'){
            //$randomBytes = random_bytes(64);
            //$token = base64_encode($randomBytes);
            $myToken = Random::generate(88, '0–9a-zA-Z');
            //array_push($createTokenTable, $myToken);
             $createTokenTable['token'] = $myToken;
        }
        return($createTokenTable);
    }

}