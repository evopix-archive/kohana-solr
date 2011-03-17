<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A sample object that implements Solr_Resource for testing purposes.
 *
 * @group solr
 *
 * @package    Solr
 * @category   Tests
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
class Model_Solr_Resource implements Solr_Resource {

	public function searchable_fields()
	{
		return array(
			new Solr_Document_Field('id', 5),
			new Solr_Document_Field('name', 'T-Shirt'),
		);
	}

}