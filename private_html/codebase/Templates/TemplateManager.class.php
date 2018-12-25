<?php

namespace codebase\Templates;

class TemplateManager {
    const TMPL_DEFAULT = 0;
    const TMPL_ACCOUNT = 1;

    public static function getTemplate($template = self::TMPL_DEFAULT) {
        if($template == self::TMPL_ACCOUNT) {
            return new MeTransfer\AccountTemplate();
        }else{
            return new Template();
        }
    }

}
