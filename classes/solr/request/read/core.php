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
	 * @var  array  array of highlight query params
	 */
	protected $_highlight = array();

	/**
	 * @var  array  query parameters that can have multiple values
	 */
	protected $_multiple_params = array(
		'fq', 'facet.query', 'facet.field', 'facet.prefix', 'facet.sort',
		'facet.limit', 'facet.offset', 'facet.mincount', 'facet.missing',
		'facet.method', 'facet.enum.cache.minDf', 'facet.range',
		'facet.range.start', 'facet.range.end', 'facet.range.gap',
		'facet.range.hardend', 'facet.range.other', 'facet.range.include',
		'hl.snippets', 'hl.fragsize', 'hl.mergeContiguous',
		'hl.alternateField', 'hl.formatter', 'hl.simple.pre', 'hl.simple.post',
		'hl.fragmenter', 'hl.useFastVectorHighlighter', 'stats.field',
		'stats.facet', 'terms.regex.flag',
	);

	/**
	 * @var  array  list of methods to param names
	 */
	protected $_methods = array(
		'query' => 'q',
		'filter_query' => 'fq',
		'offset' => 'start',
		'limit' => 'rows',
		'sort' => 'sort',
		'fields' => 'fl',
		'type' => 'defType',
		'time_allowed' => 'timeAllowed',
		'omit_header' => 'omitHeader',
		'debug' => 'debug',
		'debug_query' => 'debugQuery',
		'explain_other' => 'explainOther',
		'facet_field' => 'facet.field',
		'facet_query' => 'facet.query',
		'facet_prefix' => 'facet.prefix',
		'facet_sort' => 'facet.sort',
		'facet_limit' => 'facet.limit',
		'facet_offset' => 'facet.offset',
		'facet_min_count' => 'facet.mincount',
		'facet_missing' => 'facet.missing',
		'facet_method' => 'facet.method',
		'facet_enum_cache_min_df' => 'facet.enum.cache.minDf',
		'facet_range' => 'facet.range',
		'facet_range_start' => 'facet.range.start',
		'facet_range_end' => 'facet.range.end',
		'facet_range_gap' => 'facet.range.gap',
		'facet_range_hard_end' => 'facet.range.hardend',
		'facet_range_other' => 'facet.range.other',
		'facet_range_include' => 'facet.range.include',
		'facet_pivot' => 'facet.pivot',
		'facet_pivot_min_count' => 'facet.pivot.mincount',
		'highlight_fields' => 'hl.fl',
		'highlight_snippets' => 'hl.snippets',
		'highlight_frag_size' => 'hl.fragsize',
		'highlight_merge_contiguous' => 'hl.mergeContiguous',
		'highlight_require_field_match' => 'hl.requireFieldMatch',
		'highlight_max_analyzed_chars' => 'hl.maxAnalyzedChars',
		'highlight_alternate_field' => 'hl.alternateField',
		'highlight_max_alternate_field_length' => 'hl.maxAlternateFieldLength',
		'highlight_formatter' => 'hl.formatter',
		'highlight_simple_pre' => 'hl.simple.pre',
		'highlight_simple_post' => 'hl.simple.post',
		'highlight_fragmenter' => 'hl.fragmenter',
		'highlight_frag_list_builder' => 'hl.fragListBuilder',
		'highlight_fragments_builder' => 'hl.fragmentsBuilder',
		'highlight_use_fast_vector_highlighter' => 'hl.useFastVectorHighlighter',
		'highlight_use_phrase_highlighter' => 'hl.usePhraseHighlighter',
		'highlight_highlight_multi_term' => 'hl.highlightMultiTerm',
		'highlight_regex_slop' => 'hl.regex.slop',
		'highlight_regex_pattern' => 'hl.regex.pattern',
		'highlight_regex_max_analyzed_chars' => 'hl.regex.maxAnalyzedChars',
	);

	public function __call($name, $args)
	{
		if (array_key_exists($name, $this->_methods))
		{
			$prefix = substr($name, 0, strpos($name, '_'));

			switch ($prefix)
			{
				case 'facet':
					$member_name = '_facet';
				break;
				case 'highlight':
					$member_name = '_highlight';
				break;
				default:
					$member_name = '_get';
			}

			$param_name = $this->_methods[$name];

			if (empty($args))
			{
				// we getting a param
				return Arr::get($this->{$member_name}, $param_name);
			}
			else
			{
				// we are setting a param
				$this->{$member_name}[$param_name] = $args[0];
				return $this;
			}
		}
	}

	/**
	 * Executes the write request. Returns a write response.
	 *
	 * @return  Solr_Response_Write
	 */
	public function execute()
	{
		$request = Request::factory($this->_compile_url());
		$response = $request->execute();

		return $this->_response = new Solr_Response_Read($response, $this->_reader_type);
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
		return parent::_compile_url().'&'.$this->_compile_parameters();
	}

}