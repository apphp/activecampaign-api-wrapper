<?php

/**
 * Super simple wrapper for ActiveCampaign API 3.0
 * ActiveCampaign - Integrated email marketing, marketing automation, and small business CRM. Save time while growing your business with sales automation.
 * Website: https://www.activecampaign.com/
 * ActiveCampaign API: https://developers.activecampaign.com/reference
 *
 *
 * @author: Samuel Akopyan<admin@apphp.com>
 * @version: 1.1
 * @license: LGPL/MIT
 * @copyright: ApPHP
 * @link: https://github.com/apphp/activecampaign-api-wrapper
 * @lastChanges: 28.04.2019
 *
 *
 * PUBLIC:                        	PRIVATE:
 * ----------------                	----------------
 * __construct                    	_makeRequest
 * getVersion						_prepareStateForRequest
 * isSuccess                     	_setResponseState
 * getLastError                   	_formatResponse
 * getLastRequest                   _determineSuccess
 *
 * getContact
 * getContactBy
 * addContact
 * updateContact
 * upsertContact
 * updateContactInList
 * deleteContact
 * addContactCustomFieldValue
 * updateContactCustomFieldValue
 * addTag
 *
 * getDeals
 * getDealsBy
 * addDeal
 * updateDeal
 * addDealCustomFieldValue
 * updateDealCustomFieldValue
 * deleteDeal
 *
 * getCustomFields
 *
 * getConnections
 * getConnection
 * createConnection
 *
 */

namespace Apphp\ActiveCampaign;


class ActiveCampaign
{
	const TIMEOUT = 30;
	const VERSION = '3.0';
	
	private $_mode = 'live';    /* sandbox or live */
	private $_apiUrl = '';
	private $_apiKey = '';
	
	private $_requestSuccessful = false;
	private $_lastError = '';
	private $_lastResponse = array();
	private $_lastRequest = array();
	
	public $debug = FALSE;
	
	
	/**
	 * Create a new instance
	 * @param array $config Configuration array
	 * @return void
	 * @throws \Exception
	 */
	public function __construct($config = array())
	{
		$this->_apiUrl = !empty($config['api_url']) ? $config['api_url'] : '';
		$this->_apiKey = !empty($config['api_key']) ? $config['api_key'] : '';
		$this->_mode = !empty($config['mode']) && $config['mode'] == 'sandbox' ? 'sandbox' : 'live';
		
		if ($this->_apiKey === null) {
			throw new \Exception('Empty ActiveCampaign API Endpoint supplied.');
		}
	}
	
	/**
	 * GetVersion
	 * This method returns ActiveCampaign class version
	 * @return string
	 */
	public function getVersion()
	{
		return self::VERSION;
	}
	
	/**
	 * Returns if operation was successful
	 * @return bool
	 */
	public function isSuccess()
	{
		return $this->_requestSuccessful ? true : false;
	}
	
	/**
	 * Get the last error returned by either the network transport, or by the API.
	 * If something didn't work, this should contain the string describing the problem.
	 * @return  string|false  describing the error
	 */
	public function getLastError()
	{
		return $this->_lastError ? $this->_lastError : false;
	}
	
	/**
	 * Get an array containing the HTTP headers and the body of the API response.
	 * @return array  Assoc array with keys 'headers' and 'body'
	 */
	public function getLastResponse()
	{
		return $this->_lastResponse;
	}
	
	/**
	 * Get an array containing the HTTP headers and the body of the API request.
	 * @return array  Assoc array
	 */
	public function getLastRequest()
	{
		return $this->_lastRequest;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| CONTACTS
	|--------------------------------------------------------------------------
	*/
	/**
	 * Get specific contact
	 * @param int $id
	 * @return array|false
	 * @throws \Exception
	 */
	public function getContact($id)
	{
		return $this->_makeRequest(__FUNCTION__, 'contacts/' . $id, 'GET');
	}
	
	/**
	 * Get specific contact by filter
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function getContactBy($data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'contacts?' . http_build_query($data), 'GET');
	}
	
	/**
	 * Add new contact
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function addContact($data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'contacts', 'POST', array('contact' => $data));
	}
	
	/**
	 * Update contact
	 * @param int $id
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function updateContact($id, $data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'contacts/' . $id, 'PUT', array('contact' => $data));
	}
	
	/**
	 * Upsert contact
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function upsertContact($data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'contact/sync', 'POST', array('contact' => $data));
	}
	
	/**
	 * Update contact in list
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function updateContactInList($data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'contactLists', 'POST', array('contactList' => $data));
	}
	
	/**
	 * Delete contact
	 * @param int $id
	 * @return array
	 * @throws \Exception
	 */
	public function deleteContact($id)
	{
		return $this->_makeRequest(__FUNCTION__, 'contacts/' . $id, 'DELETE');
	}
	
	/**
	 * Update a custom field value for contact
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function addContactCustomFieldValue($data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'fieldValues', 'POST', array('fieldValue' => $data));
	}
	
	/**
	 * Update a custom field value for contact
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function updateContactCustomFieldValue($id, $data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'fieldValues/' . $id, 'PUT', array('fieldValue' => $data));
	}

	/**
	 * Add Tag
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function addTag($data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'contactTags', 'POST', array('contactTag' => $data));
	}


	/*
	|--------------------------------------------------------------------------
	| DEALS
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get deals
	 * @return array
	 * @throws \Exception
	 */
	public function getDeals()
	{
		return $this->_makeRequest(__FUNCTION__, 'deals', 'GET');
	}
	
	/**
	 * Get specific deals by filter
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function getDealsBy($data)
	{
		return $this->_makeRequest(__FUNCTION__, 'deals?' . http_build_query($data), 'GET');
	}
	
	/**
	 * Add deal to contact
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function addDeal($data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'deals', 'POST', array('deal' => $data));
	}
	
	/**
	 * Update specific deal
	 * @param int $id
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function updateDeal($id, $data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'deals/' . $id, 'PUT', array('deal' => $data));
	}
	
	/**
	 * Add custom field value to deal
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function addDealCustomFieldValue($data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'dealCustomFieldData', 'POST', array('dealCustomFieldDatum' => $data));
	}
	
	/**
	 * Update custom field value in deal
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function updateDealCustomFieldValue($id, $data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'dealCustomFieldData/' . $id, 'PUT', array('dealCustomFieldDatum' => $data));
	}
	
	/**
	 * Delete deal
	 * @param int $id
	 * @return array
	 * @throws \Exception
	 */
	public function deleteDeal($id)
	{
		return $this->_makeRequest(__FUNCTION__, 'deals/' . $id, 'DELETE');
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| CUSTOM FIELDS
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get custom fields
	 * @return array|false
	 * @throws \Exception
	 */
	public function getCustomFields()
	{
		return $this->_makeRequest(__FUNCTION__, 'fields', 'GET');
	}
	
	/*
	|--------------------------------------------------------------------------
	| CONNECTIONS
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get connections
	 * @return array|false
	 * @throws \Exception
	 */
	public function getConnections()
	{
		return $this->_makeRequest(__FUNCTION__, 'connections', 'GET');
	}
	
	/**
	 * Get connection
	 * @param int $id
	 * @return array|false
	 * @throws \Exception
	 */
	public function getConnection($id)
	{
		return $this->_makeRequest(__FUNCTION__, 'connections/' . $id, 'GET');
	}
	
	/**
	 * Create connection
	 * @param array $data
	 * @return array|false
	 * @throws \Exception
	 */
	public function createConnection($data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'connections', 'POST', array('connection' => $data));
	}

	/**
	 * Update connection
	 * @param $id
	 * @param array $data
	 * @return array|false
	 * @throws \Exception
	 */
	public function updateConnection($id, $data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'connections/' . $id, 'PUT', array('connection' => $data));
	}

	/**
	 * Delete connection
	 * @param $id
	 * @return array|false
	 * @throws \Exception
	 */
	public function deleteConnection($id)
	{
		return $this->_makeRequest(__FUNCTION__, 'connections/' . $id, 'DELETE');
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| CUSTOMERS
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get customers
	 * @return array|false
	 * @throws \Exception
	 */
	public function getCustomers()
	{
		return $this->_makeRequest(__FUNCTION__, 'ecomCustomers', 'GET');
	}

	/**
	 * Get customers by parameter
	 * @param array $data
	 * @return array|false
	 * @throws \Exception
	 */
	public function getCustomersBy($data)
	{
		return $this->_makeRequest(__FUNCTION__, 'ecomCustomers?' . http_build_query($data), 'GET');
	}

	/**
	 * Get customer
	 * @param int $id
	 * @return array|false
	 * @throws \Exception
	 */
	public function getCustomer($id)
	{
		return $this->_makeRequest(__FUNCTION__, 'ecomCustomers/' . $id, 'GET');
	}

	/**
	 * Add new customer
	 * @param array $data
	 * @return array
	 */
	public function addCustomer($data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'ecomCustomers', 'POST', array('ecomCustomer' => $data));
	}

	/**
	 * Update customer
	 * @param int $id
	 * @param array $data
	 * @return array|false
	 * @throws \Exception
	 */
	public function updateCustomer($id, $data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'ecomCustomers/' . $id, 'PUT', array('ecomCustomer' => $data));
	}

	/**
	 * Delete customer
	 * @param int $id
	 * @return array|false
	 * @throws \Exception
	 */
	public function deleteCustomer($id)
	{
		return $this->_makeRequest(__FUNCTION__, 'ecomCustomers/' . $id, 'DELETE');
	}


	/*
	|--------------------------------------------------------------------------
	| ORDERS
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get orders
	 * @return array|false
	 * @throws \Exception
	 */
	public function getOrders()
	{
		return $this->_makeRequest(__FUNCTION__, 'ecomOrders', 'GET');
	}

	/**
	 * Get orders by paremeter
	 * @param array $data
	 * @return array|false
	 * @throws \Exception
	 */
	public function getOrdersBy($data)
	{
		return $this->_makeRequest(__FUNCTION__, 'ecomOrders?' . http_build_query($data), 'GET');
	}

	/**
	 * Add order
	 * @param array $data
	 * @return array|false
	 * @throws \Exception
	 */
	public function addOrder($data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'ecomOrders', 'POST', array('ecomOrder' => $data));
	}

	/**
	 * Update order
	 * @param int $id
	 * @param array $data
	 * @return array|false
	 * @throws \Exception
	 */
	public function updateOrder($id, $data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'ecomOrders/' . $id, 'PUT', array('ecomOrder' => $data));
	}

	/**
	 * Delete order
	 * @param int $id
	 * @return array|false
	 * @throws \Exception
	 */
	public function deleteOrder($id)
	{
		return $this->_makeRequest(__FUNCTION__, 'ecomOrders/' . $id, 'DELETE');
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| SHOPPING CARTS
	|--------------------------------------------------------------------------
	*/

	/**
	 * Create cart
	 * @param array $data
	 * @return array|false
	 * @throws \Exception
	 */
	public function createCart($data = array())
	{
		return $this->_makeRequest(__FUNCTION__, 'ecomOrders', 'POST', array('ecomOrder' => $data));
	}

	/**
	 * Performs the underlying HTTP request
	 * @param string $method
	 * @param string $requestUrl
	 * @param string $httpMethod
	 * @param array $fields
	 * @param int $timeout
	 * @return array|false Assoc array of decoded result
	 * @throws \Exception
	 */
	private function _makeRequest($method, $requestUrl = '', $httpMethod = 'POST', $fields = array(), $timeout = self::TIMEOUT)
	{
		if (!function_exists('curl_init') || !function_exists('curl_setopt')) {
			throw new \Exception("cURL support is required, but can't be found.");
		}
		
		$url = $this->_apiUrl . '/' . $requestUrl;
		
		$ch = curl_init();
		
		$curl_params = array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => $timeout,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $httpMethod,
			CURLOPT_HTTPHEADER => array(
				"content-type: application/json",
				"Api-Token:" . $this->_apiKey
			),
		);
		
		if (in_array($httpMethod, array('POST', 'PUT'))) {
			$curl_params[CURLOPT_POSTFIELDS] = json_encode($fields);
		}
		
		curl_setopt_array($ch, $curl_params);
		
		$responseContent = curl_exec($ch);
		
		$response = $this->_prepareStateForRequest($method, $url, $httpMethod, $timeout);
		$response = $this->_setResponseState($response, $responseContent, $fields, $ch);
		$formattedResponse = $this->_formatResponse($response);
		
		if ($this->debug) {
			echo '<br><br>------------------------------------------------------<br>';
			$backtrace = debug_backtrace();
			$caller = next($backtrace);
			echo !empty($caller['function']) ? $caller['function'] : 'Unknown';
			print_r($curl_params);
			print_r($this->getLastRequest());
			print_r($response);
			print_r($formattedResponse);
			//exit;
		}
		
		$this->_determineSuccess($response, $formattedResponse, $timeout);
		
		return $formattedResponse;
	}
	
	/**
	 * @param string $method
	 * @param string $url
	 * @param integer $timeout
	 */
	private function _prepareStateForRequest($method, $url, $httpMethod, $timeout)
	{
		$this->_lastError = '';
		
		$this->_requestSuccessful = false;
		
		$this->_lastRequest = array(
			'url' => $url,
			'httpMethod' => $httpMethod,
			'method' => $method,
			'params' => '',
			'timeout' => $timeout,
		);
		
		$this->_lastResponse = array(
			'headers' => null,    // array of details from curl_getinfo()
			'body' => null    // content of the response
		);
		
		return $this->_lastResponse;
	}
	
	/**
	 * Do post-request formatting and setting state from the response
	 * @param array $response The response from the curl request
	 * @param string $responseContent The body of the response from the curl request
	 * @param array $fields
	 * @return array    The modified response
	 */
	private function _setResponseState($response, $responseContent, $fields, $ch)
	{
		$response['headers'] = curl_getinfo($ch);
		
		if ($responseContent === false) {
			$this->_lastError = curl_error($ch);
		} else {
			$response['body'] = $responseContent;
			$this->_lastRequest['params'] = $fields;
		}
		
		return $response;
	}
	
	/**
	 * Decode the response and format any error messages for debugging
	 * @param array $response The response from the curl request
	 * @return array|false        The JSON decoded into an array
	 */
	private function _formatResponse($response)
	{
		if (!empty($response['body'])) {
			$response['body'] = json_decode($response['body'], true);
		}
		
		$this->_lastResponse = $response;
		
		return $response;
	}
	
	/**
	 * Check if the response was successful or a failure. If it failed, store the error.
	 * @param array $response The response from the curl request
	 * @param array|false $formattedResponse The response body payload from the curl request
	 * @param int $timeout The timeout supplied to the curl request.
	 * @return bool        If the request was successful
	 */
	private function _determineSuccess($response, $formattedResponse, $timeout)
	{
		$status = $this->_findHTTPStatus($response, $formattedResponse);
		
		if ($status >= 200 && $status <= 299) {
			$this->_requestSuccessful = true;
			return true;
		}

		if (isset($formattedResponse['body']['message'])) {
			$this->_lastError = $formattedResponse['body']['message'];
			return false;
		}
		
		if ($timeout > 0 && $response['headers'] && $response['headers']['total_time'] >= $timeout) {
			$this->_lastError = sprintf('Request timed out after %f seconds.', $response['headers']['total_time']);
			return false;
		}
		
		$this->_lastError = 'Unknown error, call getLastResponse() to find out what happened.';
		return false;
	}
	
	
	/**
	 * Find the HTTP status code from the headers or API response body
	 * @param array $response The response from the curl request
	 * @param array|false $formattedResponse The response body payload from the curl request
	 * @return int  HTTP status code
	 */
	private function _findHTTPStatus($response, $formattedResponse)
	{
		if (!empty($response['headers']) && isset($response['headers']['http_code'])) {
			return (int)$response['headers']['http_code'];
		}
		
		if (!empty($response['body']) && isset($formattedResponse['status'])) {
			return (int)$formattedResponse['status'];
		}
		
		return 418;
	}
	
}
