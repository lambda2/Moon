<?php

/*
 * This file is part of the Moon Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This class will be able to generate Complex emails.
 * @author lambda2
 */
class Mail {

    /**
     * email adresses to send to
     */
    private $to = array();

    /**
     * the sender email adress
     */
    private $from = '';

    /**
     * email adresses to send a carbon copy
     */
    private $cc = array();

    /**
     * email adresses to send a blinded carbon copy
     */
    private $bcc = array();

    /**
     * the email subject
     */
    private $subject = '';

    /**
     * the email text content, without html code
     */
    private $text = '';

    /**
     * html content of the message. The [text] property must contain the same text, without html
     */
    private $htmlText = null;
    private $boundary;

    /**
     * the files to add with the mail
     */
    private $files = array();
    
    public function __construct($from, $to, $subject)
    {
        $this->to[] = $to;
        $this->from = $from;
        $this->subject = $subject;
        $this->boundary = md5(uniqid(microtime(), TRUE));
    }

    public function addTo($to)
    {
        $this->to = $to;
    }
    
    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to)
    {
        $this->to = $to;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from)
    {
        $this->from = $from;
    }

    public function addCC($cc)
    {
        $this->cc[] = $cc;
    }

    public function getCc()
    {
        return $this->cc;
    }

    public function setCc($cc)
    {
        $this->cc = $cc;
    }

    public function addBcc($bcc)
    {
        $this->bcc[] = $bcc;
    }

    public function getBcc()
    {
        return $this->bcc;
    }

    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getHtmlText()
    {
        return $this->htmlText;
    }

    public function setHtmlText($htmlText)
    {
        $this->htmlText = $htmlText;
    }

    public function addFile($fileName)
    {
        $this->files[] = $fileName;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles($files)
    {
        $this->files = $files;
    }

    public function send()
    {
        /** Generate the header */
        $headers = 'From: '.$this->from."\r\n";
        $headers .= 'Mime-Version: 1.0'."\r\n";
        if($this->htmlText != null)
            $headers .= 'Content-Type: multipart/mixed;boundary='.$this->boundary."\r\n";
        if(count($this->to))
            $headers .= 'To: ' . implode(', ',$this->to) . "\r\n";
        if(count($this->cc))
            $headers .= 'Cc: ' . implode(', ',$this->cc) . "\r\n";
        if(count($this->bcc))
            $headers .= 'Bcc: ' . implode(', ',$this->bcc) . "\r\n";
        $headers .= "\r\n";

        /* The message */
        $message = $this->text . "\r\n";
        if($this->htmlText != null)
        {
            $message .= '--' . $this->boundary . "\r\n";
            $message .= 'Content-type: text/html; charset=utf-8' . "\r\n\r\n";
            $message .= $this->htmlText . "\r\n";
        }

        if(count($this->files))
        {
            foreach($this->files as $file_name)
            {
                if(file_exists($file_name))
                {
                    $file_type = filetype($file_name);
                    $file_size = filesize($file_name);

                    $handle = fopen($file_name, 'r') or die('File '.$file_name.'can t be open');
                    $content = fread($handle, $file_size);
                    $content = chunk_split(base64_encode($content));
                    $f = fclose($handle);

                    $message .= '--'.$this->boundary."\r\n";
                    $message .= 'Content-type:'.$file_type.';name='.$file_name."\r\n";
                    $message .= 'Content-transfer-encoding:base64'."\r\n\r\n";
                    $message .= $content."\r\n";
                }
                else 
                {
                    log("le fichier $file_name n'a pas été trouvé.",2);
                }
            }
            $message .= '--' . $this->boundary . "\r\n";

        }
        mail(implode(', ',$this->to), $this->subject, $message, $headers);

    }

}
