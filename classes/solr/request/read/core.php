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
	 * @var  string  uri to the servlet that handles this request
	 */
	protected $_handler = 'select';

	/**
	 * @var array    query parameters
	 */
	protected $_get;

	/**
	 * @var  array  array of facet query params
	 */
	protected $_facet = array();

	/**
	 * @var  array  query parameters that can have multiple values
	 */
	protected $_multiple_params = array(
		'fq', 'facet.query', 'facet.field', 'facet.prefix', 'facet.sort',
		'facet.limit', 'facet.offset', 'facet.mincount', 'facet.missing',
		'facet.method', 'enum.cache.minDf', 'facet.range', 'facet.range.start',
		'facet.range.end', 'facet.range.gap', 'facet.range.hardend',
		'facet.range.other', 'facet.range.include', 'hl.snippets',
		'hl.fragsize', 'hl.mergeContiguous', 'hl.alternateField',
		'hl.formatter', 'hl.simple.pre', 'hl.simple.post', 'hl.fragmenter',
		'hl.useFastVectorHighlighter', 'stats.field', 'stats.facet',
		'terms.regex.flag',
	);

	/**
	 * Executes the write request. Returns a write response.
	 *
	 * @return  Solr_Response_Write
	 */
	public function execute()
	{
		$request = Request::factory($this->_compile_url());
		$response = $request->execute();

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
	 * Sets and gets the facet.field param for the request.
	 *
	 * @param   mixed  $field  name or array of facet fields
	 * @return  mixed
	 */
	public function facet_field($field = NULL)
	{
		if ( ! $field)
			return Arr::get($this->_facet, 'facet.field');

		if ( ! is_array($field))
		{
			$field = array($field);
		}

		$this->_facet['facet.field'] = array_merge(Arr::get($this->_facet, 'facet.field', array()), $field);
		return $this;
	}

	/**
	 * Sets and gets the facet.query param for the request.
	 *
	 * @param   string  $query  Lucene query for faceting
	 * @return  mixed
	 */
	public function facet_query($query = NULL)
	{
		if ( ! $query)
			return Arr::get($this->_facet, 'facet.query');

		$this->_facet['facet.query'] = $query;
		return $this;
	}

	/**
	 * Sets and gets the facet.prefix param for the request.
	 *
	 * @param   string  $prefix  prefix to limit the terms on which to facet
	 * @return  mixed
	 */
	public function facet_prefix($prefix = NULL)
	{
		if ( ! $prefix)
			return Arr::get($this->_facet, 'facet.prefix');

		$this->_facet['facet.prefix'] = $prefix;
		return $this;
	}

	/**
	 * Sets and gets the facet.sort param for the request.
	 *
	 * @param   mixed  $sort  string or array of sort options
	 * @return  mixed
	 */
	public function facet_sort($sort = NULL)
	{
		if ( ! $sort)
			return Arr::get($this->_facet, 'facet.sort');

		$this->_facet['facet.sort'] = $sort;
		return $this;
	}

	/**
	 * Sets and gets the facet.limit param for the request.
	 *
	 * @param   mixed  $limit  string or array of limit options
	 * @return  mixed
	 */
	public function facet_limit($limit = NULL)
	{
		if ( ! $limit)
			return Arr::get($this->_facet, 'facet.limit');

		$this->_facet['facet.limit'] = $limit;
		return $this;
	}

	/**
	 * Sets and gets the facet.offset param for the request.
	 *
	 * @param   mixed  $offset  string or array of offset options
	 * @return  mixed
	 */
	public function facet_offset($offset = NULL)
	{
		if ( ! $offset)
			return Arr::get($this->_facet, 'facet.offset');

		$this->_facet['facet.offset'] = $offset;
		return $this;
	}

	/**
	 * Sets and gets the facet.mincount param for the request.
	 *
	 * @param   mixed  $min_count  string or array of mincount options
	 * @return  mixed
	 */
	public function facet_min_count($min_count = NULL)
	{
		if ( ! $min_count)
			return Arr::get($this->_facet, 'facet.mincount');

		$this->_facet['facet.mincount'] = $min_count;
		return $this;
	}

	/**
	 * Sets and gets the facet.missing param for the request.
	 *
	 * @param   mixed  $missing  string or array of missing options
	 * @return  mixed
	 */
	public function facet_missing($missing = NULL)
	{
		if ( ! $missing)
			return Arr::get($this->_facet, 'facet.missing');

		$this->_facet['facet.missing'] = $missing;
		return $this;
	}

	/**
	 * Sets and gets the facet.method param for the request.
	 *
	 * @param   mixed  $method  string or array of method options
	 * @return  mixed
	 */
	public function facet_method($method = NULL)
	{
		if ( ! $method)
			return Arr::get($this->_facet, 'facet.method');

		$this->_facet['facet.method'] = $method;
		return $this;
	}

	/**
	 * Sets and gets the facet.enum.cache.minDf param for the request.
	 *
	 * @param   mixed  $value  string or array of enum.cache.minDf options
	 * @return  mixed
	 */
	public function facet_enum_cache_min_df($value = NULL)
	{
		if ( ! $value)
			return Arr::get($this->_facet, 'facet.enum.cache.minDf');

		$this->_facet['facet.enum.cache.minDf'] = $value;
		return $this;
	}

	/**
	 * Sets and gets the facet.range param for the request.
	 *
	 * @param   mixed  $field  name or array of field(s) to create range facets for
	 * @return  mixed
	 */
	public function facet_range($field = NULL)
	{
		if ( ! $field)
			return Arr::get($this->_facet, 'facet.range');

		if ( ! is_array($field))
		{
			$field = array($field);
		}

		$this->_facet['facet.range'] = array_merge(Arr::get($this->_facet, 'facet.range', array()), $field);
		return $this;
	}

	/**
	 * Sets and gets the facet.range.start param for the request.
	 *
	 * @param   mixed  $value  string or array of facet.range.start options
	 * @return  mixed
	 */
	public function facet_range_start($value = NULL)
	{
		if ( ! $value)
			return Arr::get($this->_facet, 'facet.range.start');

		$this->_facet['facet.range.start'] = $value;
		return $this;
	}

	/**
	 * Sets and gets the facet.range.end param for the request.
	 *
	 * @param   mixed  $value  string or array of facet.range.end options
	 * @return  mixed
	 */
	public function facet_range_end($value = NULL)
	{
		if ( ! $value)
			return Arr::get($this->_facet, 'facet.range.end');

		$this->_facet['facet.range.end'] = $value;
		return $this;
	}

	/**
	 * Sets and gets the facet.range.gap param for the request.
	 *
	 * @param   mixed  $value  string or array of facet.range.gap options
	 * @return  mixed
	 */
	public function facet_range_gap($value = NULL)
	{
		if ( ! $value)
			return Arr::get($this->_facet, 'facet.range.gap');

		$this->_facet['facet.range.gap'] = $value;
		return $this;
	}

	/**
	 * Sets and gets the facet.range.hardend param for the request.
	 *
	 * @param   mixed  $value  string or array of facet.range.hardend options
	 * @return  mixed
	 */
	public function facet_range_hardend($value = NULL)
	{
		if ( ! $value)
			return Arr::get($this->_facet, 'facet.range.hardend');

		$this->_facet['facet.range.hardend'] = $value;
		return $this;
	}

	/**
	 * Sets and gets the facet.range.other param for the request.
	 *
	 * @param   mixed  $value  string or array of facet.range.other options
	 * @return  mixed
	 */
	public function facet_range_other($value = NULL)
	{
		if ( ! $value)
			return Arr::get($this->_facet, 'facet.range.other');

		$this->_facet['facet.range.other'] = $value;
		return $this;
	}

	/**
	 * Sets and gets the facet.range.include param for the request.
	 *
	 * @param   mixed  $value  string or array of facet.range.include options
	 * @return  mixed
	 */
	public function facet_range_include($value = NULL)
	{
		if ( ! $value)
			return Arr::get($this->_facet, 'facet.range.include');

		$this->_facet['facet.range.include'] = $value;
		return $this;
	}

	/**
	 * Sets and gets the facet.pivot param for the request.
	 *
	 * @param   array  $fields  array of fields to pivot
	 * @return  mixed
	 */
	public function facet_pivot(array $fields = NULL)
	{
		if ( ! $fields)
			return Arr::get($this->_facet, 'facet.pivot');

		$this->_facet['facet.pivot'] = $fields;
		return $this;
	}

	/**
	 * Sets and gets the facet.pivot.mincount param for the request.
	 *
	 * @param   integer  $value  minimum number of documents that need to match
	 * @return  mixed
	 */
	public function facet_pivot_min_count($value = NULL)
	{
		if ( ! $value)
			return Arr::get($this->_facet, 'facet.pivot.mincount');

		$this->_facet['facet.pivot.mincount'] = $value;
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

	/**
	 * Compiles all request parameters into a url encoded string.
	 *
	 * @return  string
	 */
	protected function _compile_parameters()
	{
		$query_string = array();

		if ( ! empty($this->_facet))
		{
			$this->_get['facet'] = 'true';
		}

		$params = array_merge($this->_get, $this->_facet);

		foreach ($params as $name => $value)
		{
			// skip empty values
			if (empty($value))
			{
				continue;
			}

			if (is_array($value))
			{
				// can this param have multiple values?
				if (in_array($name, $this->_multiple_params))
				{
					foreach ($value as $field_name => $field_value)
					{
						if (is_string($field_name))
						{
							// this param overrides a field
							$query_string[] = 'f.'.$field_name.'.'.$name.'='.rawurlencode($field_value);
						}
						else
						{
							$query_string[] = $name.'='.rawurlencode($field_value);
						}
					}
				}
				else
				{
					$query_string[] = $name.'='.rawurlencode(implode(',', $value));
				}
			}
			else
			{
				if (is_bool($value))
				{
					if ($value === TRUE)
					{
						$value = 'true';
					}
					else
					{
						$value = 'false';
					}
				}

				$query_string[] = $name.'='.rawurlencode($value);
			}
		}

		return implode('&', $query_string);
	}

	/**
	 * Returns the full url for the current request.
	 *
	 * @return  string
	 */
	protected function _compile_url()
	{
		$query = '?'.$this->_compile_parameters();
		return 'http://'.Solr::$host.$this->_handler.$query;
	}

}