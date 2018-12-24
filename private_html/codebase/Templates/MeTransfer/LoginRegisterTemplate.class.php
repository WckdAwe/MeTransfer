<?php
namespace codebase\Templates\MeTransfer;

use codebase\Templates\Template;

class LoginRegisterTemplate extends Template{

    public function __construct()
    {
        parent::__construct();
        $this->addCSS('/assets/css/login_style.css', true);
    }
}