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

class Solr_Request_WriteTest extends Unittest_TestCase {

	/**
	 * Tests Solr_Request_Write::overwrite()
	 *
	 * @test
	 */
	public function test_overwrite()
	{
		$request = new Solr_Request_Write('localhost:8999');

		$this->assertNull($request->overwrite());
		$this->assertTrue($request->overwrite(TRUE) === $request);
		$this->assertEquals($request->overwrite(), TRUE);
	}

	/**
	 * Tests Solr_Request_Write::commit()
	 *
	 * @test
	 */
	public function test_commit()
	{
		$request = new Solr_Request_Write('localhost:8999');

		$this->assertNull($request->commit());
		$this->assertTrue($request->commit(TRUE) === $request);
		$this->assertEquals($request->commit(), TRUE);
		$this->assertTrue($request->commit(array('waitFlush' => TRUE, 'waitSearcher' => TRUE)) === $request);
		$this->assertEquals(count($request->commit()), 2);
	}

	/**
	 * Tests Solr_Request_Write::commit_within()
	 *
	 * @test
	 */
	public function test_commit_within()
	{
		$request = new Solr_Request_Write('localhost:8999');

		$this->assertNull($request->commit_within());
		$this->assertTrue($request->commit_within(10) === $request);
		$this->assertEquals($request->commit_within(), 10);
	}

	/**
	 * Tests Solr_Request_Write::optimize()
	 *
	 * @test
	 */
	public function test_optimize()
	{
		$request = new Solr_Request_Write('localhost:8999');

		$this->assertNull($request->optimize());
		$this->assertTrue($request->optimize(TRUE) === $request);
		$this->assertEquals($request->optimize(), TRUE);
		$this->assertTrue($request->optimize(array('waitFlush' => TRUE, 'waitSearcher' => TRUE)) === $request);
		$this->assertEquals(count($request->optimize()), 2);
	}

	/**
	 * Tests Solr_Request_Write::documents()
	 *
	 * @test
	 */
	public function test_documents()
	{
		$request = new Solr_Request_Write('localhost:8999');

		$this->assertNull($request->documents());
		$this->assertTrue($request->documents(array(new Solr_Document)) === $request);
		$this->assertEquals(count($request->documents()), 1);
	}

	/**
	 * Tests Solr_Request_Write::rollback()
	 *
	 * @test
	 */
	public function test_rollback()
	{
		$request = new Solr_Request_Write('localhost:8999');

		$this->assertNull($request->rollback());
		$this->assertTrue($request->rollback(TRUE) === $request);
		$this->assertEquals($request->rollback(), TRUE);
	}

	/**
	 * Tests Solr_Request_Write::delete()
	 *
	 * @test
	 */
	public function test_delete()
	{
		$request = new Solr_Request_Write('localhost:8999');

		$this->assertNull($request->delete());
		$this->assertTrue($request->delete('test') === $request);
		$this->assertEquals($request->delete(), 'test');
		$this->assertTrue($request->delete(array('test1', 'test2')) === $request);
		$this->assertEquals(count($request->delete()), 2);
	}

	/**
	 * Tests Solr_Request_Write::delete_by_query()
	 *
	 * @test
	 */
	public function test_delete_by_query()
	{
		$request = new Solr_Request_Write('localhost:8999');

		$this->assertNull($request->delete_by_query());
		$this->assertTrue($request->delete_by_query('test') === $request);
		$this->assertEquals($request->delete_by_query(), 'test');
		$this->assertTrue($request->delete_by_query(array('test1', 'test2')) === $request);
		$this->assertEquals(count($request->delete_by_query()), 2);
	}

	/**
	 * Tests Solr_Request_Write::execute()
	 *
	 * @test
	 */
	public function test_execute()
	{
		$request = new Solr_Request_Write('localhost:8999/solr/products/');
		$request->optimize(TRUE);
		$response = $request->execute();

		$this->assertInstanceOf('Solr_Response_Write', $response);
	}

}