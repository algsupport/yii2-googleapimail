<?php
/**
 * @link http://www.algsupport.com/
 * @copyright Copyright (c) 2021 ALGSUPPORT OÜ
 */


namespace algsupport\googleapimail;

use Yii;
use ReflectionObject;
use yii\mail\BaseMailer;
use Google\Service\Gmail;
use yii\base\InvalidConfigException;


class Mailer extends BaseMailer
{
    private Gmail $_gmailMailer;

    private array $_transport = [];


	/**
	 * @throws InvalidConfigException
	 */
	public function getGmailMailer(): Gmail
    {
        if (!is_object($this->_gmailMailer)) {
            $this->_gmailMailer = $this->createGmailMailer();
        }

        return $this->_gmailMailer;
    }

	/**
	 * @throws InvalidConfigException
	 */
	protected function createGmailMailer(): Gmail
    {
        return new Gmail($this->getTransport());
    }

	/**
	 * @throws InvalidConfigException
	 */
	public function getTransport(): array
	{
        if (!is_object($this->_transport)) {
            $this->_transport = $this->createTransport($this->_transport);
        }

        return $this->_transport;
    }

	/**
	 * @throws InvalidConfigException
	 */
	protected function createTransport(array $config)
    {
        if (!isset($config['class'])) {
            $config['class'] = GmailApiTransport::class;
        }
	    return $this->createGmailObject($config)->client;
    }

	/**
	 * @throws InvalidConfigException
	 */
	protected function createGmailObject(array $config)
    {
        if (isset($config['class'])) {
            $className = $config['class'];
            unset($config['class']);
        } else {
            throw new InvalidConfigException('Object configuration must be an array containing a "class" element.');
        }

		$object = Yii::createObject($className);

        if (!empty($config)) {
            $reflection = new ReflectionObject($object);
            foreach ($config as $name => $value) {
                if ($reflection->hasProperty($name) && $reflection->getProperty($name)->isPublic()) {
                    $object->$name = $value;
                } else {
                    $setter = 'set' . $name;
                    if ($reflection->hasMethod($setter) || $reflection->hasMethod('__call')) {
                        $object->$setter($value);
                    } else {
                        throw new InvalidConfigException('Setting unknown property: ' . $className . '::' . $name);
                    }
                }
            }
        }

        return $object;
    }


	/**
	 * @throws InvalidConfigException
	 */
	protected function sendMessage($message): bool
    {
        $address = $message->getTo();

		$this->getGmailMailer()->getClient()->setSubject($message->getFrom());

        if (is_array($address)) {
            $address = implode(', ', array_keys($address));
        }
        Yii::info('Sending email "' . $message->getSubject() . '" to "' . $address . '"', __METHOD__);

		$googleMessage = new \Google\Service\Gmail\Message();
		$googleMessage->setRaw(strtr(base64_encode($message->toString()), array('+' => '-', '/' => '_')));

        return $this->getGmailMailer()->users_messages->send('me', $googleMessage) > 0;
    }

}
