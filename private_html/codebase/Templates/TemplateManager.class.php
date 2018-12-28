<?php

namespace codebase\Templates;

class TemplateManager {
    const TMPL_DEFAULT = 0;
    const TMPL_ACCOUNT = 1;
    const TMPL_METRANSFER = 2;

    public static function getTemplate($template = self::TMPL_DEFAULT) {
        if($template == self::TMPL_ACCOUNT) {
            return new MeTransfer\AccountTemplate();
        }elseif($template == self::TMPL_METRANSFER){
            return new MeTransfer\MeTransferTemplate();
        }else{
            return new Template();
        }
    }

}
