<?php
namespace codebase\Emails;
class Email {
    const SEND_TYPE_SINGLE = 0;
    const SEND_TYPE_MULTI = 1;

    private $receivers, $senders, $subject, $contents;

    public function sendEmail($send_type = self::SEND_TYPE_MULTI){
        if(empty($this->getReceivers())) return;
        if($send_type == self::SEND_TYPE_MULTI){
            if(!mail(implode(',', $this->getReceivers()), $this->getSubject(), $this->getContents(), $this->getHeaders()))
                throw new \MailException(\MailException::TYPE_GENERIC);
        }else{
            foreach($this->getReceivers() as $receiver){
                if(!mail($receiver, $this->getSubject(), $this->getContents(), $this->getHeaders()))
                    throw new \MailException(\MailException::TYPE_GENERIC);
            }
        }
    }

    public function getReceivers(){ return $this->receivers; }
    public function setReceivers($receivers)
    {
        $this->receivers = is_array($receivers) ? $receivers : [$receivers];
    }

    public function addReceivers($receivers){
        $receivers = is_array($receivers) ? $receivers : [$receivers];
        $this->receivers = array_merge($this->receivers, $receivers);
    }
    
    public function getSenders(){ return $this->senders; }
    public function setSenders($senders)
    {
        $this->senders = is_array($senders) ? $senders : [$senders];
    }

    public function addSenders($senders){
        $senders = is_array($senders) ? $senders : [$senders];
        $this->senders = array_merge($this->senders, $senders);
    }
    
    public function getSubject(){ return $this->subject; }
    public function setSubject($subject){ $this->subject = $subject; }
    
    public function getContents(){ return $this->contents; }
    public function setContents($contents){ $this->contents = $contents; }

    private function getHeaders(){
        if(empty($this->getSenders())){
            $headers = "From: ".__ENV["website_name"]." <" . __EMAILS["email_do_not_reply"] . ">\r\n"
                     . "Reply-To:".__ENV["website_name"]." <" . __EMAILS["email_do_not_reply"] . ">\r\n"
                     . "MIME-Version: 1.0\r\n"
                     . "Content-Type: text/html; charset=UTF-8\r\n";
        }else{
            $headers = "From: ".implode(",", $this->getSenders())."\r\n"
                     . "Reply-To:".implode(",", $this->getSenders())."\r\n"
                     . "MIME-Version: 1.0\r\n"
                     . "Content-Type: text/html; charset=UTF-8\r\n";
        }
        return $headers;
    }
}
