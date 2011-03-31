<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Unit Tests for Solr class.
 *
 * @group solr
 *
 * @package    Solr
 * @category   Tests
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */

class Solr_CoreTest extends Unittest_TestCase {

	/**
	 * Provides test data for test_search()
	 *
	 * @return  array
	 */
	public function provider_search()
	{
		return array(
			// $query, $offset, $limit, $expected_count
			array('ipod', NULL, NULL, 3),
			array('ipod', 2, NULL, 1),
			array('*:*', NULL, 5, 5),
		);
	}

	/**
	 * Tests Solr::instance()->search()
	 *
	 * @test
	 * @dataProvider  provider_search
	 * @param  string   $query           the search query
	 * @param  integer  $offset          the starting offset
	 * @param  integer  $limit           the document limit
	 * @param  integer  $expected_count  expected number of documents
	 */
	public function test_search($query, $offset, $limit, $expected_count)
	{
		$solr = Solr::instance('unittest');
		$response = $solr->search($query, $offset, $limit);
		$this->assertEquals(count($response->documents()), $expected_count);
	}

	/**
	 * Tests Solr::instance()->add()
	 *
	 * @test
	 */
	public function test_add()
	{
		$fields = array(
			array(
				'name' => 'id',
				'values' => 'KO-31',
			),
			array(
				'name' => 'name',
				'values' => 'Kohana 3.1',
			),
		);

		$document = new Solr_Document($fields);
		$response = Solr::instance('unittest')->add($document);

		$this->assertEquals($response->status(), 0);
	}

	/**
	 * Tests Solr::instance()->commit()
	 *
	 * @test
	 */
	public function test_commit()
	{
		$response = Solr::instance('unittest')->commit();
		$this->assertEquals($response->status(), 0);
	}

	/**
	 * Tests Solr::instance()->optimize()
	 *
	 * @test
	 */
	public function test_optimize()
	{
		$response = Solr::instance('unittest')->optimize();
		$this->assertEquals($response->status(), 0);
	}

	/**
	 * Tests Solr::instance()->delete()
	 *
	 * @test
	 */
	public function test_delete()
	{
		$response = Solr::instance('unittest')->delete('KO-31');
		$this->assertEquals($response->status(), 0);
	}

	/**
	 * Tests Solr::instance()->rollback()
	 *
	 * @test
	 */
	public function test_rollback()
	{
		$response = Solr::instance('unittest')->rollback();
		$this->assertEquals($response->status(), 0);
	}

	/**
	 * Tests Solr::instance()->delete_by_query()
	 *
	 * @test
	 */
	public function test_delete_by_query()
	{
		$response = Solr::instance('unittest')->delete_by_query('Kohana');
		$this->assertEquals($response->status(), 0);

		Solr::instance('unittest')->commit();
	}

	/**
	 * Tests Solr::instance()->read_open()
	 *
	 * @test
	 */
	public function test_read_open()
	{
		$request = Solr::instance('unittest')->read_open();
		$this->assertInstanceOf('Solr_Request_Read', $request);
	}

	/**
	 * Tests Solr::instance()->write_open()
	 *
	 * @test
	 */
	public function test_write_open()
	{
		$request = Solr::instance('unittest')->write_open();
		$this->assertInstanceOf('Solr_Request_Write', $request);
	}

	/**
	 * Tests Solr::instance()
	 *
	 * @test
	 */
	public function test_instance()
	{
		$solr = Solr::instance();
		$this->assertInstanceOf('Solr', $solr);

		$solr = Solr::instance('unittest');
		$this->assertInstanceOf('Solr', $solr);
	}

}