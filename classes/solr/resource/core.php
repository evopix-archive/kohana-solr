<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
interface Solr_Resource_Core {

	/**
	 * Gets an array of searchable fields for the resource.
	 *
	 * @return  array
	 */
	public function searchable_fields();

}