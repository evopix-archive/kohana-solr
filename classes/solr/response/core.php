<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
class Solr_Response_Core {

	/**
	 * @var  Solr_Reader  reader instance for format
	 */
	protected $_reader;

	/**
	 * @var  array  array of parsed response data
	 */
	protected $_data;

	/**
	 * Overload construct to instantiate the reader.
	 *
	 * @return  void
	 */
	public function __construct(Response $response, $reader = 'json')
	{
		$this->_reader = Solr_Reader::factory($reader, $response);
		$this->_data = $this->_reader->parse();
	}

	/**
	 * Gets the raw parsed response data.
	 *
	 * @return  array
	 */
	public function data()
	{
		return $this->_data;
	}

	/**
	 * Gets the response status.
	 *
	 * @return  integer
	 */
	public function status()
	{
		return (int) Arr::get($this->header(), 'status');
	}

	/**
	 * Gets the query time of the response.
	 *
	 * @return  integer
	 */
	public function query_time()
	{
		return (int) Arr::get($this->header(), 'QTime');
	}

	/**
	 * Gets the full response header.
	 *
	 * @return  array
	 */
	public function header()
	{
		return Arr::get($this->_data, 'responseHeader');
	}

	/**
	 * Gets the full response.
	 *
	 * @return  object
	 */
	public function response()
	{
		return Arr::get($this->_data, 'response');
	}

	/**
	 * Gets the request params.
	 *
	 * @return  object
	 */
	public function params()
	{
		return Arr::get($this->header(), 'params');
	}

}