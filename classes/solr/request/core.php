<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
abstract class Solr_Request_Core implements Http_Request {

	/**
	 * @var  string  method: GET, POST, PUT, DELETE, HEAD, etc
	 */
	protected $_method = 'GET';

	/**
	 * @var  string  protocol: HTTP/1.1, FTP, CLI, etc
	 */
	protected $_protocol;

	/**
	 * @var  Kohana_Response  response
	 */
	protected $_response;

	/**
	 * @var  Kohana_Http_Header  headers to sent as part of the request
	 */
	protected $_header;

	/**
	 * @var  string the body
	 */
	protected $_body;

	/**
	 * @var  string  the URI of the request
	 */
	protected $_uri;

	/**
	 * @var array    query parameters
	 */
	protected $_get;

	/**
	 * @var array    post parameters
	 */
	protected $_post;

	/**
	 * @var Kohana_Request_Client
	 */
	protected $_client;

	/**
	 * @var  string  uri to the servlet that handles this request
	 */
	protected $_handler;

	/**
	 * @var  array  array of request data
	 */
	protected $_data;

	/**
	 * Creates a new request object for the given URI.
	 *
	 *     $request = new Solr_Request($uri);
	 *
	 * If $cache parameter is set, the response for the request will attempt to
	 * be retrieved from the cache.
	 *
	 * @return  void
	 */
	public function __construct()
	{
		// Initialise the header
		$this->_header = new Http_Header(array());

		// Setup the client
		$this->_client = new Request_Client_External();
	}

	/**
	 * Forces the `HTTP/1.1` protocol.
	 *
	 * @TODO Implement HTTPS support for Solr
	 *
	 * @param   string   $protocol  Protocol to set to the request/response
	 * @return  mixed
	 */
	public function protocol($protocol = NULL)
	{
		return 'http';
	}

	/**
	 * Gets or sets HTTP headers to the request or response. All headers
	 * are included immediately after the HTTP protocol definition during
	 * transmission. This method provides a simple array or key/value
	 * interface to the headers.
	 *
	 * @param   mixed   $key    Key or array of key/value pairs to set
	 * @param   string  $value  Value to set to the supplied key
	 * @return  mixed
	 */
	public function headers($key = NULL, $value = NULL)
	{
		if ($key instanceof Http_Header)
		{
			$this->_header = $key;
			return $this;
		}
		elseif (is_array($key))
		{
			$this->_header->exchangeArray($key);
			return $this;
		}

		if ($key === NULL)
		{
			return $this->_header;
		}
		elseif ($value === NULL)
		{
			return ($this->_header->offsetExists($key)) ? $this->_header->offsetGet($key) : NULL;
		}
		else
		{
			$this->_header[$key] = $value;
			return $this;
		}
	}

	/**
	 * Gets or sets the HTTP body to the request or response. The body is
	 * included after the header, separated by a single empty new line.
	 *
	 * @param   string    $content  Content to set to the object
	 * @return  string
	 * @return  void
	 */
	public function body($content = NULL)
	{

	}

	/**
	 * Renders the Http_Interaction to a string, producing
	 *
	 *  - Protocol
	 *  - Headers
	 *  - Body
	 *
	 * @return  string
	 */
	public function render()
	{

	}

	/**
	 * Gets or sets the Http method. Throws an exception if the method isn't
	 * GET or POST.
	 *
	 * @param   string   $method  Method to use for this request
	 * @return  mixed
	 */
	public function method($method = NULL)
	{
		if ($method === NULL)
			return $this->_method;

		$method = strtoupper($method);

		if (($method !== Http_Request::GET) OR
			($method !== Http_Request::POST))
		{
			throw new Kohana_Request_Exception('Solr only accepts GET or POST requests.');
		}

		$this->_method = $method;
		return $this;
	}

	/**
	 * Gets the URI of this request, optionally allows setting
	 * of [Route] specific parameters during the URI generation.
	 * If no parameters are passed, the request will use the
	 * default values defined in the Route.
	 *
	 * @param   array    $params  Optional parameters to include in uri generation
	 * @return  string
	 */
	public function uri(array $params = array())
	{

	}

	/**
	 * Gets or sets HTTP query string.
	 *
	 * @param   mixed   $key    Key or key value pairs to set
	 * @param   string  $value  Value to set to a key
	 * @return  mixed
	 */
	public function query($key = NULL, $value = NULL)
	{

	}

	/**
	 * Gets or sets HTTP POST parameters to the request.
	 *
	 * @param   mixed   $key   Key or key value pairs to set
	 * @param   string  $value Value to set to a key
	 * @return  mixed
	 */
	public function post($key = NULL, $value = NULL)
	{

	}

	/**
	 * Creates a response based on the type of request, i.e. an
	 * Request_Http will produce a Response_Http, and the same applies
	 * to CLI.
	 *
	 *      // Create a response to the request
	 *      $response = $request->create_response();
	 *
	 * @param   boolean  $bind  Bind to this request
	 * @return  Response
	 * @since   3.1.0
	 */
	public function create_response($bind = TRUE)
	{
		$response = new Response(array('_protocol' => $this->protocol()));

		if ( ! $bind)
			return $response;
		else
			return $this->_response = $response;
	}

	/**
	 * Executes the Solr HTTP request.
	 *
	 * By default, the output from the request is captured and returned, and
	 * no headers are sent.
	 *
	 *     $request->execute();
	 *
	 * @return  Solr_Response
	 * @throws  Solr_Request_Exception
	 */
	public function execute()
	{
		if ( ! $this->_client instanceof Kohana_Request_Client)
			throw new Solr_Request_Exception('Unable to execute :uri without a Kohana_Request_Client', array(':uri', $this->_uri));

		return $this->_client->execute($this);
	}

	/**
	 * Sets and gets data for the request.
	 *
	 * @param   string  $type   the type of data
	 * @param   string  $name   name of the data
	 * @param   mixed   $value  value for the data
	 * @return  mixed
	 */
	protected function _data($type, $name, $value = NULL)
	{
		if ($value === NULL)
			return Arr::path($this->_data, $type.'.'.$name);

		$this->_data[$type][$name] = $value;
		return $this;
	}

}