<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
class Solr_Request_Write_Core extends Solr_Request {

	public static $writer;

	protected $_writer;

	public function __construct()
	{
		parent::__construct();
		$this->_handler = 'update';
		$this->_writer = Solr_Writer::factory(Solr_Request_Write::$writer);
	}

	public function execute()
	{
		$data = $this->_writer->compile($this->_data);
		var_export($data);
	}

}