<?php

namespace codebase\Templates;

class TemplateManager {
    const TMPL_DEFAULT = 0;
    const TMPL_LOGIN_REGISTER = 1;

    public static function getTemplate($template = self::TMPL_DEFAULT) {
        if($template == self::TMPL_LOGIN_REGISTER) {
            return new MeTransfer\LoginRegisterTemplate();
        }else{
            return new Template();
        }
    }

}
