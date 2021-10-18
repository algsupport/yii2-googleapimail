<?php

	namespace GoogleApiMail\Transport;

	use google\Client;
	use Google\Exception;
	use yii\base\BaseObject;
	use Google\Service\Gmail;

	class GmailApiTransport extends BaseObject
	{
		private string $_credentials = "";

		public Client $client;

		public function __construct($credentials, $config = [])
	    {
			if (!empty($credentials))
			{
				$this->setCredentials($credentials);
			}
			 parent::__construct($config);
	    }

		public function setCredentials($credentials)
        {
			$this->_credentials = $credentials;
        }

		/**
		 * @throws Exception
		 */
		public function init()
		{
			$this->client = new Client();
			$this->client->setAuthConfig($this->_credentials);
			$this->client->addScope(Gmail::MAIL_GOOGLE_COM);
			parent::init();
		}
	}
