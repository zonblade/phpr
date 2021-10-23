<?php

namespace phpr\mailer;

class __mail__{

    function sender_mail($sender='admin@root.com'){
        $this->sender_mail = $sender;
        return $this;
    }

    function sender_name($sender_name='No-Reply'){
        $this->sender_name = $sender_name;
        return $this;
    }

    function target($target=''){
        $this->target_mail = $target;
        return $this;
    }

    function title($title='No-Reply'){
        $this->mail_title = $title;
        return $this;
    }

    function content($content='hello this is an test mail'){
        $this->mail_content = $content;
        return $this;
    }

    function send()
    {
        $from = $this->sender_mail;
        $headers = "";
        $headers .= "From: ".$this->sender_name." <".$this->sender_mail."> \r\n";
        $headers .= "Reply-To:" . $from . "\r\n" . "X-Mailer: PHP/" . phpversion();
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $res = mail($this->target_mail, $this->mail_title, $this->mail_content, $headers);
        return $res; /* true false */
    }
}