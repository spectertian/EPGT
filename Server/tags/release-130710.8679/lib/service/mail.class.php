<?php
class Mail {
    private $smtpServer = '';
    private $port = '';
    private $timeout = '45';
    private $username = '';
    private $password = '';
    private $newline = "\r\n";
    private $localdomain = '';
    private $charset = '';
    private $contentTransferEncoding = false;

    // Do not change anything below
    private $smtpConnect = false;
    private $to = false;
    private $subject = false;
    private $message = false;
    private $headers = false;
    private $logArray = array(); // Array response message for debug
    private $Error = '';

    public function __construct($to, $subject, $message) {
        $this->smtpServer = sfConfig::get("app_email_host");
        $this->charset = sfConfig::get("app_email_charset");
        $this->port = sfConfig::get("app_email_port");
        $this->username = sfConfig::get("app_email_user");
        $this->password = sfConfig::get("app_email_pass");
        $this->to = &$to;
        $this->subject = &$subject;
        $this->message = &$message;
        // Connect to server
        if(!$this->Connect2Server()) {
        	// Display error message
            echo $this->Error.$this->newline.'<!-- '.$this->newline;
            print_r($this->logArray);
            echo $this->newline.'-->'.$this->newline;
            return false;
        }
        return true;
    }

    private function Connect2Server() {
        // Connect to server
        $this->smtpConnect = fsockopen($this->smtpServer,$this->port,$errno,$error,$this->timeout);
        $this->logArray['CONNECT_RESPONSE'] = $this->readResponse();

        if (!is_resource($this->smtpConnect)) {
            return false;
        }
        $this->logArray['connection'] = "Connection accepted: $smtpResponse";
        // Hi, server!
        $this->sendCommand("EHLO $this->localdomain");
        $this->logArray['EHLO'] = $this->readResponse();
        // Let's know each other
        $this->sendCommand('AUTH LOGIN');
        $this->logArray['AUTH_REQUEST'] = $this->readResponse();
        // My name...
        $this->sendCommand(base64_encode($this->username));
        $this->logArray['REQUEST_USER'] = $this->readResponse();
        // My password..
        $this->sendCommand(base64_encode($this->password));
        $this->logArray['REQUEST_PASSWD'] = $this->readResponse();
        // If error in response auth...
        if (substr($this->logArray['REQUEST_PASSWD'],0,3)!='235') {
            $this->Error .= 'Authorization error! '.$this->logArray['REQUEST_PASSWD'].$this->newline;
            return false;
        }
        // "From" mail...
        $this->sendCommand("MAIL FROM: $this->username");
        $this->logArray['MAIL_FROM_RESPONSE'] = $this->readResponse();
        if (substr($this->logArray['MAIL_FROM_RESPONSE'],0,3)!='250') {
            $this->Error .= 'Mistake in sender\'s address! '.$this->logArray['MAIL_FROM_RESPONSE'].$this->newline;
            return false;
        }
        // "To" address
        $this->sendCommand("RCPT TO: $this->to");
        $this->logArray['RCPT_TO_RESPONCE'] = $this->readResponse();
        if (substr($this->logArray['RCPT_TO_RESPONCE'],0,3)!='250') {
            $this->Error .= 'Mistake in reciepent address! '.$this->logArray['RCPT_TO_RESPONCE'].$this->newline;
        }
        // Send data to server
        $this->sendCommand('DATA');
        $this->logArray['DATA_RESPONSE'] = $this->readResponse();
        // Send mail message
        if (!$this->sendMail()) return false;
        // Good bye server! =)
        $this->sendCommand('QUIT');
        $this->logArray['QUIT_RESPONSE'] = $this->readResponse();
        // Close smtp connect 
        fclose($this->smtpConnect);
        return true;
    }
    // Function send mail
    private function sendMail() {
        $this->sendHeaders();
        $this->sendCommand($this->message);
        $this->sendCommand('.');
        $this->logArray['SEND_DATA_RESPONSE'] = $this->readResponse();
        if(substr($this->logArray['SEND_DATA_RESPONSE'],0,3)!='250') {
            $this->Error .= 'Mistake in sending data! '.$this->logArray['SEND_DATA_RESPONSE'].$this->newline;
            return false;
        }
        return true;
    }
    // Function read response
    private function readResponse() {
        $data="";
        while($str = fgets($this->smtpConnect,4096))
        {
            $data .= $str;
            if(substr($str,3,1) == " ") { break; }
        }
        return $data;
    }
    // function send command to server
    private function sendCommand($string) {
        fputs($this->smtpConnect,$string.$this->newline);
        return ;
    }
    // function send headers
    private function sendHeaders() {
        $this->sendCommand("Date: ".date("D, j M Y G:i:s")." +0700");
        $this->sendCommand("From: <$this->username>");
        $this->sendCommand("Reply-To: <$this->username>");
        $this->sendCommand("To: <$this->to>");
        $this->sendCommand("Subject: $this->subject");
        $this->sendCommand("MIME-Version: 1.0");
        $this->sendCommand("Content-Type: text/html; charset=$this->charset");
        if ($this->contentTransferEncoding) $this->sendCommand("Content-Transfer-Encoding: $this->contentTransferEncoding");
        $this->sendCommand($this->newline);
        return ;
    }

    public function __destruct() {
        if (is_resource($this->smtpConnect)) fclose($this->smtpConnect);
    }
}
?> 