<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Unit Tests for Solr's Request class.
 *
 * @group solr
 *
 * @package    Solr
 * @category   Tests
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */

class Solr_Request_ReadTest extends Unittest_TestCase {

	/**
	 * Provides test data for test_call()
	 *
	 * @return  array
	 */
	public function provider_call()
	{
		return array(
			// $method, $value
			array('query', '*:*'),
			array('filter_query', 'popularity:[10 TO *]'),
			array('offset', 10),
			array('limit', 10),
			array('sort', 'score desc'),
			array('fields', array('id', 'name', 'price')),
			array('type', 'myqueryparser'),
			array('time_allowed', 5000),
			array('omit_header', 'true'),
			array('debug', 'query'),
			array('debug_query', 'true'),
			array('explain_other', 'id:MA*'),
			array('facet_field', array('price', 'sku')),
			array('facet_query', 'price:[*+TO+500]'),
			array('facet_prefix', 'xx'),
			array('facet_sort', 'count'),
			array('facet_limit', 20),
			array('facet_offset', 20),
			array('facet_min_count', 5),
			array('facet_missing', 'true'),
			array('facet_method', 'fc'),
			array('facet_enum_cache_min_df', 25),
			array('facet_range', array('price', 'sku')),
			array('facet_range_start', 10),
			array('facet_range_end', 100),
			array('facet_range_gap', 10),
			array('facet_range_hard_end', 'true'),
			array('facet_range_other', 'before'),
			array('facet_range_include', 'all'),
			array('facet_pivot', 'cat,popularity,inStock'),
			array('facet_pivot_min_count', 1),
			array('highlight_fields', array('price', 'sku')),
			array('highlight_snippets', 1),
			array('highlight_frag_size', 100),
			array('highlight_merge_contiguous', 'true'),
			array('highlight_require_field_match', 'true'),
			array('highlight_max_analyzed_chars', 51200),
			array('highlight_alternate_field', 'name'),
			array('highlight_max_alternate_field_length', 100),
			array('highlight_formatter', 'simple'),
			array('highlight_simple_pre', '<em>'),
			array('highlight_simple_post', '</em>'),
			array('highlight_fragmenter', 'gap'),
			array('highlight_frag_list_builder', 'test'),
			array('highlight_fragments_builder', 'test'),
			array('highlight_use_fast_vector_highlighter', 'true'),
			array('highlight_use_phrase_highlighter', 'true'),
			array('highlight_highlight_multi_term', 'true'),
			array('highlight_regex_slop', 0.6),
			array('highlight_regex_pattern', '[-\w ,/\n\"]{20,200}'),
			array('highlight_regex_max_analyzed_chars', 10000),
			array('more_like_this_count', 10),
			array('more_like_this_fields', array('price', 'sku')),
			array('more_like_this_min_term_freq', 5),
			array('more_like_this_min_doc_freq', 5),
			array('more_like_this_min_word_length', 3),
			array('more_like_this_max_word_length', 20),
			array('more_like_this_max_query_terms', 10),
			array('more_like_this_max_tokens', 10),
			array('more_like_this_boost', 'true'),
			array('more_like_this_query_fields', 'fieldOne^2.3 fieldTwo fieldThree^0.4'),
			array('term_vector_term_frequency', 'true'),
			array('term_vector_document_frequency', 'true'),
			array('term_vector_positions', 'true'),
			array('term_vector_offsets', 'true'),
			array('term_vector_term_frequency_idf', 'true'),
			array('term_vector_all', 'true'),
			array('term_vector_fields', array('price', 'sku')),
			array('term_vector_document_ids', 'test'),
			array('stats_fields', array('price', 'sku')),
			array('stats_facet', 'status'),
			array('spellcheck_query', 'hell ultrashar'),
			array('spellcheck_build', TRUE),
			array('spellcheck_reload', TRUE),
			array('spellcheck_dictionary', 'default'),
			array('spellcheck_count', '10'),
			array('spellcheck_only_more_popular', TRUE),
			array('spellcheck_extended_results', TRUE),
			array('spellcheck_collate', TRUE),
			array('spellcheck_max_collations', 5),
			array('spellcheck_max_collation_tries', 5),
			array('spellcheck_collate_extended_results', FALSE),
			array('spellcheck_accuracy', 1.0),
			array('terms_fields', 'name'),
			array('terms_lower', 'a'),
			array('terms_lower_include', FALSE),
			array('terms_min_count', 3),
			array('terms_max_count', 10),
			array('terms_prefix', 'at'),
			array('terms_regex', 'at.*'),
			array('terms_regex_flag', 'case_insensitive'),
			array('terms_limit', 10),
			array('terms_upper', 't'),
			array('terms_upper_include', FALSE),
			array('terms_raw', TRUE),
			array('terms_sort', 'count'),
			array('shards', array('localhost:8983/solr', 'localhost:7574/solr')),
			array('shards_query_type', 'test'),
		);
	}

	/**
	 * Tests Solr_Request_Read::__call()
	 *
	 * @test
	 * @dataProvider  provider_call
	 * @param  string  $method  name of method to call
	 * @param  mixed   $value   value to set on method
	 */
	public function test_call($method, $value)
	{
		$request = new Solr_Request_Read('localhost:8983');

		$this->assertNull($request->$method());
		$this->assertTrue($request->$method($value) === $request);
		$this->assertEquals($request->$method(), $value);
	}

	/**
	 * Tests Solr_Request_Read::spellcheck_dictionary_key()
	 *
	 * @test
	 */
	public function test_spellcheck_dictionary_key()
	{
		$request = new Solr_Request_Read('localhost:8983');

		$this->assertNull($request->spellcheck_dictionary_key('test_key', 'test_value'));
		$this->assertTrue($request->spellcheck_dictionary_key('test_key', 'test_value', 'test') === $request);
		$this->assertEquals($request->spellcheck_dictionary_key('test_key', 'test_value'), 'test');
	}

	/**
	 * Tests Solr_Request_Read::params()
	 *
	 * @test
	 */
	public function test_params()
	{
		$request = new Solr_Request_Read('localhost:8983');

		$params = array(
			'query' => '*:*',
			'filter_query' => 'popularity:[10 TO *]',
			'offset' => 0,
			'limit' => 20,
			'sort' => 'score desc',
			'fields' => array('id', 'name', 'price'),
		);

		$this->assertTrue($request->params($params) === $request);
		$this->assertEquals($request->query(), '*:*');
		$this->assertEquals($request->filter_query(), 'popularity:[10 TO *]');
		$this->assertEquals($request->offset(), 0);
		$this->assertEquals($request->limit(), 20);
		$this->assertEquals($request->sort(), 'score desc');
		$this->assertEquals(count($request->fields()), 3);
	}

}