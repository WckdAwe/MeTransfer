<?php

namespace codebase\Templates;

class Template {

    protected $PAGE_NAME = 'Untitled';
    protected $PAGE_TITLE = 'Untitled';
    protected $PAGE_DESCRIPTION = 'Example Description';
    protected $PAGE_KEYWORDS = array('Vasil7112');
    protected $PAGE_CSS = array();
    protected $PAGE_JS = array();
    protected $requireLogin = false;

    public function __construct() {
        $this->setPageName(\main\config::WEBSITE_NAME);
        $this->setPageDescription(\main\config::WEBSITE_DESCRIPTION);
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

    public function setBodyClass($body_class) {
        $this->PAGE_BODY_CLASS = $body_class;
    }

    public function getBodyClass() {
        return $this->PAGE_BODY_CLASS;
    }

    public function addCSS($CSS, $isURL = true) {
        if (is_string($CSS) && is_bool($isURL)) {
            array_push($this->PAGE_CSS, array('content' => ($isURL ? '<link href="' . $CSS . '" rel="stylesheet">' : $CSS), 'isURL' => $isURL));
        }
    }

    public function clearCSS() {
        $this->PAGE_CSS = array();
    }

    public function getCSS() {
        $LINK = '';
        $STYLE = '<style>';
        foreach ($this->PAGE_CSS as $CSS) {
            if ($CSS['isURL'] == false) {
                $STYLE .= $CSS['content'];
            } else {
                $LINK .= $CSS['content'];
            }
        }
        $END_STYLE = ($STYLE != '<style>' ? $STYLE . '</style>' : '');
        return $LINK . $END_STYLE;
    }

    public function addJS($JS, $isURL = true) {
        if (is_string($JS) && is_bool($isURL)) {
            array_push($this->PAGE_JS, array('content' => ($isURL ? '<script src="' . $JS . '"></script>' : $JS), 'isURL' => $isURL));
        }
    }

    public function clearJS() {
        $this->PAGE_KS = array();
    }

    public function getJS() {
        $LINK = '';
        $SCRIPT = '<script>';
        foreach ($this->PAGE_JS as $JS) {
            if ($JS['isURL'] == false) {
                $SCRIPT .= $JS['content'];
            } else {
                $LINK .= $JS['content'];
            }
        }
        $END_SCRIPT = ($SCRIPT != '<script>' ? $SCRIPT . '</script>' : '');
        return $LINK . $END_SCRIPT;
    }

    /**
      public function setRequireLogin($Boolean){
      if(is_bool($Boolean)){
      $this->requireLogin = $Boolean;
      if($Boolean == true && !\codebase\Authentication\Account::isLoggedIn()){
      header('Location: /account/login');
      die();
      }
      }
      }* */
    protected function isCurPageURL($page) {
        if (strtolower($this->getPageTitle()) === strtolower($page)) {
            return 'class="active"';
        }
    }

    protected function getHead() {}
    protected function getNavigation(){}
    protected function getFooter(){}
}