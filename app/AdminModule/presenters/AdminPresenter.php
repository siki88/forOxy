<?php

namespace App\Presenters;
//namespace App\AdminModule\Presenters;

use  App\CoreModule\Presenters\BasePresenter,
     Nette\Application\UI\Form,
     App\AdminModule\Components,   
     Nette\Security\AuthenticationException;

final class AdminPresenter extends BasePresenter{


    public function renderDefault(){
        $this->template->newsCount = $this->newsManager->getPublicNews()->count();
        $this->template->usersCount = $this->usersManager->getPublicUsers()->count();
        $this->template->role = $this->user->getIdentity()->getRoles()[0];
        $this->template->email = $this->user->getIdentity()->email;
    }

    public function actionDefault(){
        $this->controlUserLogin();
    }


    public function renderNews(){
        $this->template->newsKeys = $this->newsManager->getNameColumns()->getColumns('news');
        $this->template->newsValues = $this->newsManager->getPublicNewsWithInch();
    }

    public function actionNews(){
        $this->controlUserLogin();
    }


    public function renderUsers(){
        $this->template->usersKeys = $this->usersManager->getNameColumns()->getColumns('users');
        $this->template->usersValues = $this->usersManager->getPublicUsers();

        /*
        foreach ($this->usersManager->getPublicUsersRole() as $test){
          var_dump($test);
        }
        die();
        */
    }

    public function actionUsers(){
        $this->controlUserLogin();
    }

    public function renderEvaluations($newsId){
        $this->template->evaluationsKeys = $this->evaluationsManager->getNameColumns()->getColumns('evaluation');
        $this->template->evaluationsValues = $this->evaluationsManager->getPublicEvaluationNewsId($newsId);
        $this->template->titleNews = $this->newsManager->getPublicNewsId($newsId)->short_text;
    }

    public function actionEvaluations($newsId){
        $this->controlUserLogin();
    }

    public function actionLogin(){
       // $this->getUser()->logout();
    }

    private function controlUserLogin(){
        if($this->getUser()->isLoggedIn() && in_array("admin", $this->user->getIdentity()->getRoles())){
            $this->user->setExpiration('15 minutes');
        }else{
            $this->redirect('Admin:login');
        }
    }

    public function actionOut(){
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení bylo úspěšné');
        $this->redirect('Homepage:default');
    }

    public function actionEvaluationsDelete($evaluationsId){
        $status = $this->evaluationsManager->setPublicEvaluationDelete($evaluationsId);
        $this->flashMessage("Smazáno $status hodnocení" );
        $this->redirect('Admin:news');
    }

    
    
    
    
    /*zaregistrování továrničky login*/
    protected function createComponentLogin(){
	$control = new Components\Login\LoginControl();
        $this->flashMessage('Byl jste přihlášen');
	return $control;
    }
    
    /*zaregistrování továrničky login*/
    protected function createComponentNews(){
	$control = new Components\News\NewsControl();
        $this->flashMessage('Byl jste přihlášen');
	return $control;
    }    
    
    
    
    

    protected function createComponentForm(){
        $form = new Form();

        switch ($this->getAction()) {
            case "login": // login už funguje na samostatné továrničce, následně ostranit
                $form->getElementPrototype()->class = 'login';
                $form->addText('username')
                    ->setRequired()
                    ->setAttribute('placeholder', 'Username')
                    ->setType('text');
                $form->addPassword('password')
                    ->setRequired()
                    ->setType('password');
                $form->addSubmit('send', 'LOGIN')
                    ->setOption('id', 'username')
                    ->setAttribute('class', 'button');
                $form->onSuccess[] = [$this, 'formSucceded'];
                break;
            case "news":
                $form->getElementPrototype()->class = 'myForm';
                $form->addText('short_text', 'Nadpis novinky:')
                    ->setRequired();
                $form->addSelect('users_id', 'Autor novinky:', $this->usersManager->getPublicUsers()->fetchPairs('id', 'username'));
                $form->addTextArea('text', 'Text novinky:')
                    ->setRequired();
                $form->addSubmit('send', 'Přidat novinku')
                    ->setAttribute('class', 'button');
                $form->onSuccess[] = [$this, 'formSucceded'];
                break;
            case "users":
                $form->getElementPrototype()->class = 'myForm';
                $form->addText('username', 'Uživatelské jméno')
                    ->setRequired()
                    ->setAttribute('placeholder', 'Uživatelské jméno')
                    ->setType('text');
                $form->addEmail('email', 'E-mail:')
                    ->setRequired();
                $form->addPassword('password', 'Heslo')
                    ->setRequired()
                    ->setType('password');
                $form->addSelect('roles_id', 'Oprávnění:', $this->rolesManager->getPublicRoles()->fetchPairs('id', 'name'));
                $form->addSubmit('send', 'Přidat uživatele')
                    ->setAttribute('class', 'button');
                $form->onSuccess[] = [$this, 'formSucceded'];
            default:
                break;
        }
        return $form; 
    }

    public function formSucceded(Form $form, \stdClass $values){

        switch ($this->getAction()) {
            case "login": // login už funguje na samostatné továrničce, následně ostranit
                try{
                    $this->getUser()->login($values->username,$values->password);
                    $this->redirect('default');
                }catch(AuthenticationException $e){
                    $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
                }
                break;
            case "news":
                $this->newsManager->setAddNews($values);
                $this->flashMessage('Novinka přidána.', 'success');
                $this->redirect('news');
                break;
            case "users":
                $this->usersManager->setAddUser($values);
                $this->flashMessage('Uživatel přidán.', 'success');
                $this->redirect('users');
                break;
            default:
                break;
        }

    }
 
}
