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
    protected $PAGE_HEAD_JS = [];
    protected $login_required = false;
    protected $guest_required = false;
    protected $admin_required = false;

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

    public function addHeadJS($JS, $isURL = true) {
        if (is_string($JS) && is_bool($isURL)) {
            array_push($this->PAGE_HEAD_JS, ['content' => ($isURL ? '<script src="' . $JS . '"></script>' : $JS),
                'isURL' => $isURL]);
        }
        var_dump($this->PAGE_HEAD_JS);
    }

    public function clearHeadJS() {
        unset($this->PAGE_HEAD_JS);
        $this->PAGE_HEAD_JS = [];
    }

    public function getHeadJS() {
        $LINK = '';
        $SCRIPT = '';
        foreach ($this->PAGE_HEAD_JS as $JS) {
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

    public function setAdminRequired($admin_required){
        $this->admin_required = $admin_required;
        $this->checkAdminRequired();
    }

    public function setLoginRequired($login_required){
        $this->login_required = $login_required;
        $this->checkLoginRequired();
    }

    public function setGuestRequired($guest_required){
        $this->guest_required = $guest_required;
        $this->checkGuestRequired();
    }

    public function checkAdminRequired(){
        if($this->admin_required && (!Account::isLoggedIn() ||
                                     (Account::isLoggedIn() && !Account::user()->isAdmin())
                                    )){
            Helper::redirect('/account/');
        }
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
                    '<link rel="apple-touch-icon" sizes="57x57" href="/assets/favicons/apple-icon-57x57.png">
                     <link rel="apple-touch-icon" sizes="60x60" href="/assets/favicons/apple-icon-60x60.png">
                     <link rel="apple-touch-icon" sizes="72x72" href="/assets/favicons/apple-icon-72x72.png">
                     <link rel="apple-touch-icon" sizes="76x76" href="/assets/favicons/apple-icon-76x76.png">
                     <link rel="apple-touch-icon" sizes="114x114" href="/assets/favicons/apple-icon-114x114.png">
                     <link rel="apple-touch-icon" sizes="120x120" href="/assets/favicons/apple-icon-120x120.png">
                     <link rel="apple-touch-icon" sizes="144x144" href="/assets/favicons/apple-icon-144x144.png">
                     <link rel="apple-touch-icon" sizes="152x152" href="/assets/favicons/apple-icon-152x152.png">
                     <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicons/apple-icon-180x180.png">
                     <link rel="icon" type="image/png" sizes="192x192"  href="/assets/favicons/android-icon-192x192.png">
                     <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicons/favicon-32x32.png">
                     <link rel="icon" type="image/png" sizes="96x96" href="/assets/favicons/favicon-96x96.png">
                     <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicons/favicon-16x16.png">
                     <link rel="manifest" href="/assets/favicons/manifest.json">
                     <meta name="msapplication-TileColor" content="#ffffff">
                     <meta name="msapplication-TileImage" content="/assets/favicons/ms-icon-144x144.png">
                     <meta name="theme-color" content="#ffffff">'
                    .$this->getHeadJS().
               '</head>';
    }

    public function getUserMenu() {
        $result = '<div class="logo">
                    <p class="logo_text"><a href="/"> MeTransfer </a></p>
                   </div>
                   <div class="login_subscribe_button">';
        if(!Account::isLoggedIn()){
            $result .= '<div><a href="/account/login"> LOGIN </a></div>
                        <div><a href="/account/register"> REGISTER </a></div>';
        }else{
            $user = Account::user();
            $result .= 'Hello <b>'.Account::user()->getUsername().'</b> <br>';
            $result .= '<div><a href="/account"> MY PROFILE </a></div>
                        <div><a href="/account/my_files"> MY FILES </a></div>
                        <div><a href="/account/logout"> LOGOUT </a></div>';
            if($user->isAdmin()){
                $result .= '<div><br><a href="/admin">ADMIN PAGE </a></div>';
            }
        }
        $result .= '</div>';
        return $result;
    }
    protected function getNavigation(){}
    protected function getFooter(){}
}