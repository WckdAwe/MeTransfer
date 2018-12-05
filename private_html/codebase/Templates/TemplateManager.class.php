<?php

namespace codebase\Templates;

class TemplateManager {

    public static function getTemplate($TEMPLATE_NAME = 'Pluton.Template') {
        // One template for now, so...
        return new PlutonTemplate();
    }

}
