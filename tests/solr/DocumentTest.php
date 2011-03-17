<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Unit Tests for Solr's Document class.
 *
 * @group solr
 *
 * @package    Solr
 * @category   Tests
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */

include Kohana::find_file('tests', 'solr/test_data/resource_object');

class Solr_DocumentTest extends Unittest_TestCase {

	/**
	 * Provides the data for test_create()
	 * @return array
	 */
	public function provider_create_array()
	{
		return array(
			array(
				array(
					'name' => 'id',
					'value' => 5,
				),
				NULL,
				2,
				NULL,
			),
			array(
				array(
					'name' => 'id',
					'value' => 5,
				),
				3.3,
				2,
				3.3,
			),
			array(
				NULL,
				3.3,
				0,
				3.3,
			),
		);
	}

	/**
	 * Ensures a document can be created with an array of fields.
	 *
	 * @test
	 * @dataProvider provider_create_array
	 * @param  array    $fields  document fields
	 * @param  float    $boost   document boost value
	 * @param  integer  $expected_field_count  expected amount of fields in document
	 * @param  float    $expected_boost_value  expected boost value
	 */
	public function test_create_array($fields = NULL, $boost = NULL, $expected_field_count, $expected_boost_value = NULL)
	{
		$document = new Solr_Document($fields, $boost);

		$this->assertEquals($expected_field_count, count($document->fields()));
		$this->assertEquals($expected_boost_value, $document->boost());

	}

	/**
	 * Ensures that an empty document can be created.
	 *
	 * @test
	 */
	public function test_create_empty()
	{
		$document = new Solr_Document;

		$this->assertInstanceOf('Solr_Document', $document);
	}

}