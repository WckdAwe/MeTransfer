<?php

class MailException extends Exception
{
    const TYPE_GENERIC = 0;
    public function __construct(int $code) {
        parent::__construct($this->getCustomMessage($code), $code);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    private function getCustomMessage($code){
        switch($code){
            case self::TYPE_GENERIC:
            default:
                return 'Generic issue (Not Defined)';
        }
    }
}