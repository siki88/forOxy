<?php

namespace App\Model;

use Nette,
     Nette\Security\IAuthenticator;

class AuthenticatorManager extends DatabaseManager implements IAuthenticator {

    use Nette\SmartObject;

    private $usersManager,
         $tokenManager,
         $rolesManager;

    public function __construct(UsersManager $usersManager, TokenManager $tokenManager, RolesManager $rolesManager){
        $this->usersManager = $usersManager;
        $this->tokenManager = $tokenManager;
        $this->rolesManager = $rolesManager;
    }

// http://php.net/manual/en/function.http-response-code.php

    public function authenticate(array $credentials){

        if(isset($credentials['email']) && isset($credentials['password'])){
            $credentials[0] = $credentials['email'];
            $credentials[1] = $credentials['password'];
            unset($credentials['email']);
            unset($credentials['password']);
            return $this->customAuthenticate($credentials);
        }else{
            return $this->defaultAuthenticate($credentials);
        }

    }

    private function defaultAuthenticate(array $credentials){
        list($email, $password) = $credentials;
         $row = $this->usersManager->getPublicUsers()->where('email', $email)->fetch();
            if (!$row) {
                throw new Nette\Security\AuthenticationException('User not found.');
            }elseif(!Nette\Security\Passwords::verify($password, $row->password)) {
                throw new Nette\Security\AuthenticationException('Invalid password.');
            }
            $role = $this->rolesManager->setRolesId($row->roles_id);
        $authorize = new Nette\Security\Identity($row->id, $role->name, ['email' => $row->email]); //->getData()
        return($authorize);
    }


    private function customAuthenticate($credentials){
        list($email, $password) = $credentials;
        $row = $this->usersManager->getPublicUsers()->where('email', $email)->fetch();
        if (!$row){
            $data = ['code' => 404,'description' => 'User not found.'];
        }elseif(!Nette\Security\Passwords::verify($password, $row->password)) {
            $data = ['code' => 404,'description' => 'Invalid password.'];
        }else{
            //controll and create token
            $tokenData = $this->tokenManager->setTokenUserId($row->id);
            $data = [
                'email' => $row->email,
                'token' => $tokenData['token'],
                'code' => 200,
                'description' => 'Login.'
            ];
        }
            $authorize = new Nette\Security\Identity($row->id, $row->role, $data); //->getData()
        return($authorize);
    }


}