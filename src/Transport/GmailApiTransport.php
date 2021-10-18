<?php

	namespace GoogleApiMail\Transport;

	use google\Client;
	use Google\Exception;
	use Google\Service\Gmail;

	class GmailApiTransport
	{
		public string $google_api_credentials = "";

		public Client $client;

		/**
		 * @throws Exception
		 */
		public function __construct($credentials)
	    {
			if (!empty($credentials))
			{
				$this->google_api_credentials = $credentials;
			}

			$this->client = new Client();
			$this->client->setAuthConfig($this->google_api_credentials);
			$this->client->addScope(Gmail::MAIL_GOOGLE_COM);
	    }
	}
