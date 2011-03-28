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
	 * @var  array  array of MoreLikeThis query params
	 */
	protected $_more_like_this = array();

	/**
	 * @var  array  array of TermVector query params
	 */
	protected $_term_vector = array();

	/**
	 * @var  array  array of Stats query params
	 */
	protected $_stats = array();

	/**
	 * @var  array  array of Spellcheck query params
	 */
	protected $_spellcheck = array();

	/**
	 * @var  array  array of Terms query params
	 */
	protected $_terms = array();

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
		'more_like_this_count' => 'mlt.count',
		'more_like_this_fields' => 'mlt.fl',
		'more_like_this_min_term_freq' => 'mlt.mintf',
		'more_like_this_min_doc_freq' => 'mlt.mindf',
		'more_like_this_min_word_length' => 'mlt.minwl',
		'more_like_this_max_word_length' => 'mlt.maxwl',
		'more_like_this_max_query_terms' => 'mlt.maxqt',
		'more_like_this_max_tokens' => 'mlt.maxntp',
		'more_like_this_boost' => 'mlt.boost',
		'more_like_this_query_fields' => 'mlt.qf',
		'term_vector_term_frequency' => 'tv.tf',
		'term_vector_document_frequency' => 'tv.df',
		'term_vector_positions' => 'tv.positions',
		'term_vector_offsets' => 'tv.offsets',
		'term_vector_term_frequency_idf' => 'tv.tf_idf',
		'term_vector_all' => 'tv.all',
		'term_vector_fields' => 'tv.fl',
		'term_vector_document_ids' => 'tv.docIds',
		'stats_fields' => 'stats.field',
		'stats_facet' => 'stats.facet',
		'spellcheck_query' => 'spellcheck.q',
		'spellcheck_build' => 'spellcheck.build',
		'spellcheck_reload' => 'spellcheck.reload',
		'spellcheck_dictionary' => 'spellcheck.dictionary',
		'spellcheck_count' => 'spellcheck.count',
		'spellcheck_only_more_popular' => 'spellcheck.onlyMorePopular',
		'spellcheck_extended_results' => 'spellcheck.extendedResults',
		'spellcheck_collate' => 'spellcheck.collate',
		'spellcheck_max_collations' => 'spellcheck.maxCollations',
		'spellcheck_max_collation_tries' => 'spellcheck.maxCollationTries',
		'spellcheck_collate_extended_results' => 'spellcheck.collateExtendedResults',
		'spellcheck_accuracy' => 'spellcheck.accuracy',
		'terms_fields' => 'terms.fl',
		'terms_lower' => 'terms.lower',
		'terms_lower_include' => 'terms.lower.incl',
		'terms_min_count' => 'terms.mincount',
		'terms_max_count' => 'terms.maxcount',
		'terms_prefix' => 'terms.prefix',
		'terms_regex' => 'terms.regex',
		'terms_regex_flag' => 'terms.regex.flag',
		'terms_limit' => 'terms.limit',
		'terms_upper' => 'terms.upper',
		'terms_upper_include' => 'terms.upper.incl',
		'terms_raw' => 'terms.raw',
		'terms_sort' => 'terms.sort',
	);

	/**
	 * Adds a spellcheck.<dictionary>.<key> parameter. This can't be defined
	 * as a magic method like the other methods because it takes non-standard 
	 * parameters.
	 *
	 * @param   string  $dictionary  the dictionary name
	 * @param   string  $key         the dictionary key
	 * @param   string  $value       the dictionary value
	 * @return  mixed
	 */
	public function spellcheck_dictionary_key($dictionary, $key, $value = NULL)
	{
		if ($value === NULL)
			return Arr::get($this->_spellcheck, 'spellcheck.'.$dictionary.'.'.$key);

		$this->_spellcheck['spellcheck.'.$dictionary.'.'.$key] = $value;
		return $this;
	}

	/**
	 * Handles query params setting and getting.
	 *
	 * @param   string  $name  method name
	 * @param   array   $args  method arguments
	 * @return  mixed
	 */
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
				case 'more':
					$member_name = '_more_like_this';
				break;
				case 'term':
					$member_name = '_term_vector';
				break;
				case 'stats':
					$member_name = '_stats';
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
		else
		{
			throw new Solr_Exception('Invalid method :method called in :class',
				array(':method' => $name, ':class' => get_class($this)));
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