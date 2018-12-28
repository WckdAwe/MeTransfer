<?php
namespace codebase\Templates\MeTransfer;

use codebase\Templates\Template;

class MeTransferTemplate extends Template{

    public function __construct()
    {
        parent::__construct();
        $this->addCSS('/assets/css/main.css', true);
    }
}