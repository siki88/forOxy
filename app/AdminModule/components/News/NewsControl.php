<?php

namespace App\AdminModule\Components\News;

use Nette\Application\UI;
use App\Model;


class NewsControl extends UI\Control {
    
    /** @var Model\UsersManager @inject */
    public $usersManager;

/*    
    public function __construct(Model\UsersManager $usersManager){
        $this->usersManager = $usersManager;
    }         
 */
    
    public function render(){
        $this->template->render(__DIR__.'/NewsControl.latte');
    }
  
    protected function createComponentNewsFactor(){
        $form = new UI\Form;
                $form->getElementPrototype()->class = 'myForm';
                $form->addText('short_text', 'Nadpis novinky:')
                    ->setRequired();
                $form->addSelect('users_id', 'Autor novinky:', $this->usersManager->getPublicUsers()->fetchPairs('id', 'username'));
                $form->addTextArea('text', 'Text novinky:')
                    ->setRequired();
                $form->addSubmit('send', 'Přidat novinku')
                    ->setAttribute('class', 'button');
                $form->onSuccess[] = [$this, 'newsFormSucceded'];
        return $form; 
    }

    public function newsFormSucceded(UI\Form $form){
        /*
        try{
            $presenter = $form->getPresenter();
            $values = $form->getValues();
            $presenter->getUser()->login($values->username,$values->password);
            $presenter->redirect('default');
        }catch(AuthenticationException $e){
            $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
        }
         */
        try{
            $this->newsManager->setAddNews($values);
            $this->flashMessage('Novinka přidána.', 'success');
            $this->redirect('news');
        }catch(AuthenticationException $e){
            $form->addError('Nesprávné údaje.');
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