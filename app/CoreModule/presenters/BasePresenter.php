<?php

namespace App\CoreModule\Presenters;

use Nette\Application\UI\Presenter;
use Nette;
use  App\Model\NewsManager,
     App\Model\AuthenticatorManager,
     App\Model\UsersManager,
     App\Model\EvaluationsManager,
     App\Model\TokenManager,
     App\Model\RolesManager;

class BasePresenter extends Presenter{

    protected $newsManager,
              $usersManager,
              $evaluationsManager,
              $authenticatorManager,
              $tokenManager,
              $rolesManager;

    public function __construct(NewsManager $newsManager, UsersManager $usersManager, EvaluationsManager $evaluationsManager, AuthenticatorManager $authenticatorManager, TokenManager $tokenManager, RolesManager $rolesManager){
        $this->newsManager = $newsManager;
        $this->usersManager = $usersManager;
        $this->evaluationsManager = $evaluationsManager;
        $this->authenticatorManager = $authenticatorManager;
        $this->tokenManager = $tokenManager;
        $this->rolesManager = $rolesManager;
    }


}