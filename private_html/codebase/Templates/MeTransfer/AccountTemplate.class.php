<?php
namespace codebase\Templates\MeTransfer;

use codebase\Templates\Template;

class AccountTemplate extends Template{

    public function __construct()
    {
        parent::__construct();
        $this->addCSS('/assets/css/account.css', true);
    }
}