<?php

namespace app\models;

use dektrium\user\Finder;


class LoginForm extends \dektrium\user\models\LoginForm
{
    
    public function __construct(Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($finder, $config);
    }
}
