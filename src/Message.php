<?php
/**
 * @link http://www.algsupport.com/
 * @copyright Copyright (c) 2021 ALGSUPPORT OÜ
 */

namespace GoogleApiMail;

use yii\mail\BaseMessage;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Message extends BaseMessage
{

    private PHPMailer $_gmailMessage;

    public function __clone()
    {
        if (is_object($this->_gmailMessage)) {
            $this->_gmailMessage = clone $this->_gmailMessage;
        }
    }

    public function getGmailMessage(): PHPMailer
    {
        if (!isset($this->_gmailMessage) or !is_object($this->_gmailMessage)) {
            $this->_gmailMessage = $this->createGmailMessage();
        }

        return $this->_gmailMessage;
    }

	public function getCharset(): string
    {
        return $this->getGmailMessage()->CharSet;
    }

    public function setCharset($charset): Message
    {
        $this->getGmailMessage()->CharSet = $charset;

        return $this;
    }

    public function getFrom()
    {
        return $this->getGmailMessage()->From;
    }


	/**
	 * @throws Exception
	 */
	public function setFrom($from): Message
    {
        $this->getGmailMessage()->setFrom($from);

        return $this;
    }

    public function getReplyTo()
    {
        return $this->getGmailMessage()->getReplyToAddresses();
    }

	/**
	 * @throws Exception
	 */
    public function setReplyTo($replyTo): Message
    {
	    if (is_array($replyTo) && sizeof($replyTo) == 1) {
		    $this->getGmailMessage()->addReplyTo($replyTo[0], $replyTo[1]);
	    } elseif (is_array($replyTo) && sizeof($replyTo) > 1)
	    {
			foreach ($replyTo as $from => $name)
			{
				$this->getGmailMessage()->addReplyTo($from, $name);
			}
	    }
		else
		{
			$this->getGmailMessage()->addReplyTo($replyTo);
		}
        return $this;
    }


    public function getTo()
    {
        return $this->getGmailMessage()->getToAddresses();
    }

	/**
	 * @throws Exception
	 */
    public function setTo($to): Message
    {
		if (is_array($to) && sizeof($to) == 1) {
		    $this->getGmailMessage()->addAddress($to[0], $to[1]);
	    } elseif (is_array($to) && sizeof($to) > 1)
	    {
			foreach ($to as $from => $name)
			{
				$this->getGmailMessage()->addAddress($from, $name);
			}
	    }
		else
		{
			$this->getGmailMessage()->addAddress($to);
		}

        return $this;
    }

    public function getCc()
    {
        return $this->getGmailMessage()->getCcAddresses();
    }

	/**
	 * @throws Exception
	 */
    public function setCc($cc): Message
    {
		if (is_array($cc) && sizeof($cc) == 1) {
		    $this->getGmailMessage()->addCC($cc[0], $cc[1]);
	    } elseif (is_array($cc) && sizeof($cc) > 1)
	    {
			foreach ($cc as $from => $name)
			{
				$this->getGmailMessage()->addCC($from, $name);
			}
	    }
		else
		{
			$this->getGmailMessage()->addCC($cc);
		}

        return $this;
    }

    public function getBcc()
    {
        return $this->getGmailMessage()->getBccAddresses();
    }

	/**
	 * @throws Exception
	 */
    public function setBcc($bcc): Message
    {
		if (is_array($bcc) && sizeof($bcc) == 1) {
		    $this->getGmailMessage()->addBCC($bcc[0], $bcc[1]);
	    } elseif (is_array($bcc) && sizeof($bcc) > 1)
	    {
			foreach ($bcc as $from => $name)
			{
				$this->getGmailMessage()->addBCC($from, $name);
			}
	    }
		else
		{
			$this->getGmailMessage()->addBCC($bcc);
		}

        return $this;
    }

     public function getSubject(): string
    {
        return $this->getGmailMessage()->Subject;
    }

    public function setSubject($subject): Message
    {
        $this->getGmailMessage()->Subject = $subject;

        return $this;
    }

    public function setTextBody($text): Message
    {
		$this->getGmailMessage()->isHTML(false);
        $this->getGmailMessage()->Body = $text;

        return $this;
    }


    public function setHtmlBody($html): Message
    {
		$this->getGmailMessage()->isHTML();
        $this->getGmailMessage()->Body = $html;

        return $this;
    }


	/**
	 * @throws Exception
	 */
    public function attach($fileName, array $options = []): Message
    {
		if (!empty($options['contentType'])) {
            $contentType = $options['contentType'];
        }
		else{
			$contentType = $this->getGmailMessage()::ENCODING_BASE64;
		}
		$name = $options['fileName'];
		$this->getGmailMessage()->addAttachment($fileName, $name, $contentType);

        return $this;
    }

	/**
	 * @throws Exception
	 */
    public function attachContent($content, array $options = []): Message
    {
		if (!empty($options['contentType'])) {
            $contentType = $options['contentType'];
        }
		else{
			$contentType = $this->getGmailMessage()::ENCODING_BASE64;
		}
		$name = $options['fileName'];
		$this->getGmailMessage()->addStringAttachment($content, $name, $contentType);

        return $this;
    }

	/**
	 * @throws Exception
	 */
    public function embed($fileName, array $options = [])
    {
 		if (!empty($options['contentType'])) {
            $contentType = $options['contentType'];
        }
		else{
			$contentType = $this->getGmailMessage()::ENCODING_BASE64;
		}
		$name = $options['fileName'];
		$cid = explode("/",str_replace(".", "_", $fileName));
        return $this->getGmailMessage()->addEmbeddedImage($fileName, end($cid), $name, $contentType);
    }

	/**
	 * @throws Exception
	 */
    public function embedContent($content, array $options = [])
    {
		if (!empty($options['contentType'])) {
            $contentType = $options['contentType'];
        }
		else{
			$contentType = $this->getGmailMessage()::ENCODING_BASE64;
		}
		$name = $options['fileName'];
		$cid = explode("/",str_replace(".", "_", $name));
        return $this->getGmailMessage()->addStringEmbeddedImage($content, end($cid), $name, $contentType);
    }

	/**
	 * @throws Exception
	 */
    public function toString(): string
    {
		$this->getGmailMessage()->preSend();
        return $this->getGmailMessage()->getSentMIMEMessage();
    }

    protected function createGmailMessage(): PHPMailer
    {
        return new PHPMailer();
    }
}
