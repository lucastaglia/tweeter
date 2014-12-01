<?php


include(APPPATH.'libraries/twitteroauth/twitteroauth.php');

class Twconnect extends TwitterOAuth {

	public $tw_user_id = null;
	public $tw_user_name = null;
	public $tw_user_info = null;
	public $tw_config = null;
	public $tw_request_token = null;
	public $tw_access_token = null;
	public $tw_status = null;

	/**
	 * 1. Twconnect class constructor, // not yet - checks login and puts user information in $this->user array
	 */
	public function Twconnect() {
		
		$ci =& get_instance();
		$ci->config->load('twitter', true);
		$config = $ci->config->item('twitter');

		/* Try to retrieve user access token (permanent) from session */
		$access_token = $ci->session->userdata('tw_access_token');

		if ( $access_token && isset($access_token['oauth_token']) && isset($access_token['oauth_token_secret']) ) {
			// echo 'Create a TwitterOauth object with consumer(app) and user tokens.';
			// echo 'It is the last (third) step of Twitter OAuth process';
			parent::__construct($config['consumer_key'], $config['consumer_secret'], $access_token['oauth_token'], $access_token['oauth_token_secret']);
			$this->tw_access_token = $access_token;
			$this->tw_user_id = ( isset($access_token['user_id']) ? $access_token['user_id'] : null);
			$this->tw_user_name = ( isset($access_token['screen_name']) ? $access_token['screen_name'] : null);

		/* Try to retrieve request token (temporary) from session */
		} else {
			$request_token = $ci->session->userdata('tw_request_token');
			if ( $request_token && isset($request_token['oauth_token']) && isset($request_token['oauth_token_secret']) ) {
				// echo 'Create object with request token key/secret sent previously to twitter';
				// echo 'It is the second step of Twitter OAuth process';
				parent::__construct($config['consumer_key'], $config['consumer_secret'], $request_token['oauth_token'], $request_token['oauth_token_secret']);
				$this->tw_request_token = $request_token;

			} else {
				// echo 'Create object without access or request token';
				// echo 'It is the first step of Twitter OAuth process';
				parent::__construct($config['consumer_key'], $config['consumer_secret']);
			}
		}

		$this->tw_config = $config;
		$this->tw_status = $ci->session->userdata('tw_status');
		$this->host = 'https://api.twitter.com/1.1/'; // Use API v1.1

	}

	
	/**
	 * 2. Redirects to Twitter for authetication
	 *    $site_redirect_path - site segment where the user will be redirected after authentication
	 */
	public function twredirect($site_callback_path = '') {

		/* if callback path is not specified take it from config file */
		if ($site_callback_path == '') {
			$site_callback_path = $this->tw_config['oauth_callback'];
		}

		// echo 'Get temporary credentials.';
		$request_token = $this->getRequestToken(site_url($site_callback_path));
		$token = $request_token['oauth_token'];
		$ci =& get_instance();

		// echo 'Save temporary credentials to session.';
		$ci->session->set_userdata('tw_request_token', $request_token);

		/* If last connection failed don't redirect to Twitter: */
		switch ($this->http_code) {
			case 200:
			    // echo 'Redirecting user to Twitter authorize URL'.';
				redirect( $this->getAuthorizeURL($token) );
				return true;
			    break;
			default:
				/* Return false if cannot connect to Twitter */
				// echo 'Could not connect to Twitter. Refresh the page or try again later.';
				return false;
		}
	}
	

	/**
	 * 3. Requests permanent access token after callback
	 */
	public function twprocess_callback() {
		$ci =& get_instance();

		$returned_request_token = $ci->input->get('oauth_token');
		$request_token = $this->tw_request_token;
		if ($returned_request_token) {

			if ( $request_token['oauth_token'] != $returned_request_token) {
				// echo 'The oauth_token is old, deleting it and returning false';
				$ci->session->unset_userdata('tw_request_token');
				$this->tw_request_token = null;
				$this->tw_status = 'old_token';
				$ci->session->set_userdata('tw_status', 'old_token');
				return false;

			} else {
				// echo 'Request access tokens from twitter';
				$access_token = $this->getAccessToken($ci->input->get('oauth_verifier'));

				/* If HTTP response is 200 (successful) continue otherwise return false */
				if ($this->http_code == 200) {
					// echo 'The user has been verified and the access tokens can be saved for future use';
					/* Save the access token to session.
					 * Later it should be saved to a database in a user's record. */
					$this->tw_access_token = $access_token;
					$this->tw_user_id = $access_token['user_id'];
					$this->tw_user_name = $access_token['screen_name'];
					$ci->session->set_userdata('tw_access_token', $access_token);

					// echo 'Remove no longer needed request token';
					$this->tw_request_token = null;
					$ci->session->unset_userdata('tw_request_token');

					// echo 'Setting tw_status to verified';
					$this->tw_status = 'verified';
					$ci->session->set_userdata('tw_status', 'verified');
					return true;
				} else {
					// echo 'Cannot access Twitter, tw_status = access_error';
					$this->tw_status = 'access_error';
					$ci->session->set_userdata('tw_status', 'access_error');
					return false;
				}
			}
		} else return false;
	}


	/**
	 * 4. GET request to Twitter API
	 */
	public function tw_get($url, $parameters = array()) {
		$token = $this->tw_access_token;

		if (isset($token['oauth_token'])
			&& isset($token['oauth_token_secret'])
			&& $this->tw_status == 'verified') {

			/* If method is set change API call made. Test is called by default. */
			return $this->get($url, $parameters);
		} else {
			// echo 'access tokens are not available, return false';
			return false;
		}
	}


	/**
	 * 5. POST request to Twitter API
	 */
	public function tw_post($url, $parameters = array()) {
		$token = $this->tw_access_token;

		if ($token && isset($token['oauth_token'])
			&& isset($token['oauth_token_secret'])
			&& $this->tw_status == 'verified') {

			/* If method is set change API call made. Test is called by default. */
			return $this->post($url, $parameters);
		} else {
			// echo 'access tokens are not available, return false';
			return false;
		}
	}

	/**
	 * 6. Gets all Twitter user info and saves it to $this->tw_user_info
	 */
	public function twaccount_verify_credentials() {
		$this->tw_user_info = $this->tw_get('account/verify_credentials');
		return $this->tw_user_info;
	}


}

?>
