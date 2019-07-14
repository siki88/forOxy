<?php

namespace App\Model;

use Nette;
use Nette\Database\Context;

class DatabaseManager{

    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    protected $database;

    public function __construct(Context $database){
        $this->database = $database;
    }

}
