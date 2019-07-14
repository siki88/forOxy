<?php

namespace App\AdminModule\Components\Login;

use Nette\Application\UI;
use Nette\Security\AuthenticationException;

class LoginControl extends UI\Control {
    
    public function render(){
        $this->template->render(__DIR__.'/LoginControl.latte');
    }
    
    protected function createComponentLoginFactor(){
        $form = new UI\Form;
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
            $form->addProtection('Vypršel časový limit, odešlete formulář znovu');
            $form->onSuccess[] = [$this, 'loginFormSucceded'];
        return $form; 
    }

    public function loginFormSucceded(UI\Form $form){
        try{
            $presenter = $form->getPresenter();
            $values = $form->getValues();
            $presenter->getUser()->login($values->username,$values->password);
            $presenter->redirect('default');
        }catch(AuthenticationException $e){
            $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
/*
    protected function createComponentRegistrationForm(){
        $form = new UI\Form();
      //  $form->getElementPrototype()->class = 'myLogRegForm';
        $form->addText('name', 'Jméno')
            ->setAttribute('class', 'uk-input')
            ->setRequired('Zadejte prosím jméno');
        $form->addText('surname', 'Příjmení')
            ->setAttribute('class', 'uk-input')
            ->setRequired('Zadejte prosím příjmení');
        $form->addText('birth', 'Datum narození')
            ->setAttribute('class', 'uk-input')
            ->setRequired('Zadejte prosím datum narození')
            ->setType('date')
            ->setDefaultValue((new \DateTime)->format('Y-m-d hh:mm:ss'));
        $form->addText('skills', 'Vaše schopnost')
            ->setAttribute('class', 'uk-input')
            ->setRequired('Zadejte prosím vaši schopost');
        $form->addSubmit('send', 'Odeslat')
            ->setAttribute('class', 'uk-button uk-button-primary');
        $form->addProtection('Vypršel časový limit, odešlete formulář znovu');
        $form->onSuccess[] = [$this, 'registrationFormSucceeded'];
        return $form;
    }

    public function registrationFormSucceeded(UI\Form $form){
        $values = $form->getValues();

        $this->userManager->add($values->name,$values->surname,$values->birth,$values->skills);

        $this->redirect('this');
    }
*/
}