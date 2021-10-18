<?php

	namespace GoogleApiMail\Transport;

	use google\Client;
	use Google\Exception;
	use Google\Service\Gmail;

	class GmailApiTransport
	{
<<<<<<< HEAD
		private string $_google_api_credentials = "";
=======
		public string $google_api_credentials = "";
>>>>>>> ee5961962b6818e1c65aae2add6a4b48eda94da8

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
<<<<<<< HEAD

		public function setGoogleApiCredentials($credentials)
        {
            $this->_google_api_credentials = $credentials;
        }
	}
=======
	}
>>>>>>> ee5961962b6818e1c65aae2add6a4b48eda94da8
