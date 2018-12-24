<?php

namespace codebase\Templates;

use codebase\App\Users\Account;
use codebase\Helper;

class Template {

    protected $PAGE_NAME = 'Untitled';
    protected $PAGE_TITLE = 'Untitled';
    protected $PAGE_DESCRIPTION = 'Example Description';
    protected $PAGE_KEYWORDS = [];
    protected $PAGE_CSS = [];
    protected $PAGE_JS = [];
    protected $login_required = false;
    protected $guest_required = false;

    public function __construct() {
        $this->setPageName(__ENV['website_name']);
        $this->setPageDescription(__ENV['website_desc']);

        $this->checkLoginRequired();
        $this->checkGuestRequired();
    }

    public function setPageName($PAGE_NAME) {
        $this->PAGE_NAME = $PAGE_NAME;
    }

    public function getPageName() {
        return $this->PAGE_NAME;
    }

    public function setPageTitle($PAGE_TITLE) {
        $this->PAGE_TITLE = $PAGE_TITLE;
    }

    public function getPageTitle() {
        return $this->PAGE_TITLE;
    }

    public function setPageDescription($PAGE_DESCRIPTION) {
        $this->PAGE_DESCRIPTION = $PAGE_DESCRIPTION;
    }

    public function getPageDescription() {
        return $this->PAGE_DESCRIPTION;
    }

    public function addKeyword($KEYWORD) {
        array_push($this->PAGE_KEYWORDS, $KEYWORD);
    }

    public function addKeywords($KEYWORDS) {
        if (is_array($KEYWORDS)) {
            $this->PAGE_KEYWORDS = array_merge($this->PAGE_KEYWORDS, $KEYWORDS);
        }
    }

    public function clearKeywords() {
        $this->PAGE_KEYWORDS = array();
    }

    public function getKeywords() {
        return implode(', ', $this->PAGE_KEYWORDS);
    }

//    public function setBodyClass($body_class) {
//        $this->PAGE_BODY_CLASS = $body_class;
//    }
//
//    public function getBodyClass() {
//        return $this->PAGE_BODY_CLASS;
//    }

    public function addCSS($CSS, $isURL = true) {
        if (is_string($CSS) && is_bool($isURL)) {
            array_push($this->PAGE_CSS, ['content' => ($isURL ? '<link href="' . $CSS . '" rel="stylesheet">' : $CSS),
                                                'isURL' => $isURL]);
        }
    }

    public function clearCSS() {
        unset($this->PAGE_CSS);
        $this->PAGE_CSS = [];
    }

    public function getCSS() {
        $LINK = '';
        $STYLE = '';
        foreach ($this->PAGE_CSS as $CSS) {
            if ($CSS['isURL'] == false) {
                $STYLE .= $CSS['content'];
            } else {
                $LINK .= $CSS['content'];
            }
        }
        if(!empty($STYLE))
            $STYLE = '<style>'.$STYLE.'</style>';
        return $LINK . $STYLE;
    }

    public function addJS($JS, $isURL = true) {
        if (is_string($JS) && is_bool($isURL)) {
            array_push($this->PAGE_JS, ['content' => ($isURL ? '<script src="' . $JS . '"></script>' : $JS),
                                               'isURL' => $isURL]);
        }
    }

    public function clearJS() {
        unset($this->PAGE_JS);
        $this->PAGE_JS = [];
    }

    public function getJS() {
        $LINK = '';
        $SCRIPT = '';
        foreach ($this->PAGE_JS as $JS) {
            if ($JS['isURL'] == false) {
                $SCRIPT .= $JS['content'];
            } else {
                $LINK .= $JS['content'];
            }
        }
        if(!empty($SCRIPT))
            $SCRIPT = '<script>'.$SCRIPT.'</script>';
        return $LINK . $SCRIPT;
    }


    public function setLoginRequired($login_required){
        $this->login_required = $login_required;
        $this->checkLoginRequired();
    }

    public function setGuestRequired($guest_required){
        $this->guest_required = $guest_required;
        $this->checkGuestRequired();
    }

    public function checkLoginRequired(){
        if($this->login_required && !Account::isLoggedIn()){
            Helper::redirect('/account/login');
        }
    }

    public function checkGuestRequired(){
        if($this->guest_required && Account::isLoggedIn()){
            Helper::redirect('/account/');
        }
    }

    protected function isCurPageURL($page) {
        if (strtolower($this->getPageTitle()) === strtolower($page)) {
            return 'class="active"';
        }
    }

    public function getHead() {
        return '<head>
                    <meta charset=utf-8> 
                    <title>'.$this->getPageName().($this->getPageTitle() != null ?  ' &bull; '.$this->getPageTitle() : '').'</title>
                    <meta name="keywords" content="'.$this->getKeywords().'">
                    <meta name="description" content="'.$this->getPageDescription().'"> 
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">'
                    .$this->getCSS().
                    '<link rel="shortcut icon" href="/assets/images/favicon.ico">
                </head>';
    }

    protected function getNavigation(){}
    protected function getFooter(){}
}