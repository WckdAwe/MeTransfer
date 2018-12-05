<?php
namespace codebase\Emails;
class Email {
    private $receiver, $sender, $subject, $contents;
    
    public function __construct(){
        $this->sender = \main\config::EMAIL_DO_NOT_REPLY;
    }
    
    public function sendEmail(){ mail($this->getReceiver(), $this->getSubject(), $this->getContents(), $this->getHeaders()); }
    
    public function getReceiver(){ return $this->receiver; }
    public function setReceiver($receiver){ $this->receiver = $receiver; }
    
    public function getSender(){ return $this->sender; }
    public function setSender($sender){ $this->sender = $sender; }
    
    public function getSubject(){ return $this->subject; }
    public function setSubject($subject){ $this->subject = $subject; }
    
    public function getContents(){ return $this->contents; }
    public function setContents($contents){ $this->contents = $contents; }
    
    private function getHeaders(){
        $headers = "From: Vasil7112.com <" . $this->getSender() . ">\r\n"
                 . "Reply-To: Vasil7112.com <" . $this->getSender() . ">\r\n"
                 . "MIME-Version: 1.0\r\n"
                 . "Content-Type: text/html; charset=ISO-8859-1\r\n";
        return $headers;
    }
}
