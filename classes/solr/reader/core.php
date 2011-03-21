<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
abstract class Solr_Reader_Core {

	/**
	 * @var  Response  response object
	 */
	protected $_response;

	/**
	 * Creates a new Solr_Reader object based an the given driver.
	 *
	 *     $reader = Request::Solr_Reader($driver);
	 *
	 * if $driver isn't specified, the default driver from the config
	 * will be used.
	 *
	 * @param   string  $driver  name of the read driver to use
	 * @return  void
	 */
	public static function factory($driver, Response $response)
	{
		// Set the class name
		$class = 'Solr_Reader_'.$driver;

		return new $class($response);
	}

	/**
	 * Loads the response object.
	 *
	 * @param  Response  $response  the response object
	 */
	public function __construct(Response $response)
	{
		$this->_response = $response;
	}

	/**
	 * Parses the response object into the correct format.
	 *
	 * @return  array
	 */
	abstract public function parse();

}