<?php

	namespace GoogleApiMail\Transport;

	use google\Client;
	use Google\Exception;
	use Google\Service\Gmail;

	class GmailApiTransport
	{
		private string $_google_api_credentials = "";

		public Client $client;

		/**
		 * @throws Exception
		 */
		public function __construct($credentials)
	    {
			if (!empty($credentials))
			{
				$this->setGoogleApiCredentials($credentials);
			}

			$this->client = new Client();
			$this->client->setAuthConfig($this->_google_api_credentials);
			$this->client->addScope(Gmail::MAIL_GOOGLE_COM);
	    }

		public function setGoogleApiCredentials($credentials)
        {
            $this->_google_api_credentials = $credentials;
        }
	}
