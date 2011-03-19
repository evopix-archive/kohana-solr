<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
abstract class Solr_Request_Core {

	/**
	 * @var  Solr_Response  response
	 */
	protected $_response;

	/**
	 * @var  string  the Solr host url
	 */
	protected $_host;

	/**
	 * @var array    query parameters
	 */
	protected $_get;

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
	 *     $request = new Solr_Request();
	 *
	 * @return  void
	 */
	public function __construct()
	{
		$this->_host = Kohana::config('solr.host');
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

	/**
	 * Returns the full url for the current request.
	 *
	 * @return  string
	 */
	protected function _compile_url()
	{
		$query = '';
		if ( ! empty($this->_get))
		{
			$query = '?'.HTTP::www_form_urlencode($this->_get);
		}

		return 'http://'.$this->_host.$this->_handler.$query;
	}

}