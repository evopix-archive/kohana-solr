<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
class Solr_Request_Read_Core extends Solr_Request {

	/**
	 * @var  Solr_Reader  reader instance for format
	 */
	protected $_reader;

	/**
	 * Instantiate the reader and set up some defaults.
	 *
	 * @return  void
	 */
	public function __construct()
	{
		$this->_handler = 'select';
		$this->_get['wt'] = Solr::$read_response_format;
		$this->_reader = Solr_Reader::factory(Solr::$read_format);
	}

	/**
	 * Executes the write request. Returns a write response.
	 *
	 * @return  Solr_Response_Write
	 */
	public function execute()
	{
		var_export($this->_compile_url());
		$request = Request::factory($this->_compile_url());
		$response = $request->execute();

var_export($response->body());
		return $this->_response = new Solr_Response_Read($response);
	}

	/**
	 * Sets and gets the query param for the request.
	 *
	 * @param   string  $query  the raw query string
	 * @return  mixed
	 */
	public function query($query = NULL)
	{
		if ( ! $query)
			return Arr::get($this->_get, 'q');

		$this->_get['q'] = $query;
		return $this;
	}

	/**
	 * Sets and gets the fq param for the request.
	 *
	 * @param   string  $query  the filter query string
	 * @return  mixed
	 */
	public function filter_query($query = NULL)
	{
		if ( ! $query)
			return Arr::get($this->_get, 'fq');

		$this->_get['fq'] = $query;
		return $this;
	}

	/**
	 * Sets and gets the offset param for the request.
	 *
	 * @param   integer  $offset  starting offset for documents
	 * @return  mixed
	 */
	public function offset($offset = NULL)
	{
		if ( ! $offset)
			return Arr::get($this->_get, 'start');

		$this->_get['start'] = $offset;
		return $this;
	}

	/**
	 * Sets and gets the limit param for the request.
	 *
	 * @param   integer  $limit  maximum number of documents to return
	 * @return  mixed
	 */
	public function limit($limit = NULL)
	{
		if ( ! $limit)
			return Arr::get($this->_get, 'rows');

		$this->_get['rows'] = $limit;
		return $this;
	}

	/**
	 * Sets and gets the sort param for the request.
	 *
	 * @param   string  $sort  field to sort the documents by
	 * @return  mixed
	 */
	public function sort($sort = NULL)
	{
		if ( ! $sort)
			return Arr::get($this->_get, 'sort');

		$this->_get['sort'] = $sort;
		return $this;
	}

	/**
	 * Sets and gets the fields param for the request.
	 *
	 * @param   mixed  $fields  list of fields for the request to return
	 * @return  mixed
	 */
	public function fields($fields = NULL)
	{
		if ( ! $fields)
			return Arr::get($this->_get, 'fl');

		if (is_array($fields))
		{
			$fields = implode(',', $fields);
		}

		$this->_get['fl'] = $fields;
		return $this;
	}

	/**
	 * Sets and gets the defType param for the request.
	 *
	 * @param   string  $type  the queryParser name
	 * @return  mixed
	 */
	public function type($type = NULL)
	{
		if ( ! $type)
			return Arr::get($this->_get, 'defType');

		$this->_get['defType'] = $type;
		return $this;
	}

	/**
	 * Sets and gets the timeAllowed param for the request.
	 *
	 * @param   integer  $time  the time, in milliseconds, for the search to finish
	 * @return  mixed
	 */
	public function time_allowed($time = NULL)
	{
		if ( ! $time)
			return Arr::get($this->_get, 'timeAllowed');

		$this->_get['timeAllowed'] = $time;
		return $this;
	}

	/**
	 * Sets and gets the omitHeader param for the request.
	 *
	 * @param   boolean  $value  if TRUE the result header is excluded
	 * @return  mixed
	 */
	public function omit_header($value = NULL)
	{
		if ( ! $value)
			return Arr::get($this->_get, 'omitHeader');

		if ($value === TRUE)
		{
			$this->_get['omitHeader'] = 'true';
		}

		return $this;
	}

	/**
	 * Sets and gets the debug param for the request.
	 *
	 * @param   boolean  $debug  information to debug
	 * @return  mixed
	 */
	public function debug($debug = NULL)
	{
		if ( ! $debug)
			return Arr::get($this->_get, 'debug');

		$this->_get['debug'] = $debug;
		return $this;
	}

	/**
	 * Sets and gets the debugQuery param for the request.
	 *
	 * @param   boolean  $debug  if TRUE, the query will be debugged
	 * @return  mixed
	 */
	public function debug_query($debug = NULL)
	{
		if ( ! $debug)
			return Arr::get($this->_get, 'debugQuery');

		if ($debug === TRUE)
		{
			$this->_get['debugQuery'] = 'true';
		}

		return $this;
	}

	/**
	 * Sets and gets the explainOther param for the request.
	 *
	 * @param   string  $query  the Lucene query
	 * @return  mixed
	 */
	public function explain_other($query = NULL)
	{
		if ( ! $query)
			return Arr::get($this->_get, 'explainOther');

		$this->_get['explainOther'] = $query;
		return $this;
	}

	/**
	 * Sets an array of params for the request. Useful for setting a mass
	 * collection of params.
	 *
	 * @param   array  $params  array of params for the request
	 * @return  Solr_Request_Read
	 */
	public function params(array $params)
	{
		foreach ($params as $param => $value)
		{
			if (method_exists($this, $param))
			{
				$this->$param($value);
			}
		}

		return $this;
	}

}