<?php

namespace codebase\Templates;

class TemplateManager {

    public static function getTemplate() {
        return new MeTransfer\LoginRegisterTemplate();
    }

}
