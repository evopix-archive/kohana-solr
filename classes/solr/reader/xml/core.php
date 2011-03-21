<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
class Solr_Reader_XML_Core extends Solr_Reader {

	/**
	 * Parses the XML response into an object.
	 *
	 * @return  object
	 */
	public function parse()
	{
		$body = $this->_response->body();
		return simplexml_load_string($body);
	}

}