<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
abstract class Solr_Writer_Core {

	/**
	 * Creates a new Solr_Writer object based an the given driver.
	 *
	 *     $writer = Request::Solr_Writer($driver);
	 *
	 * @param   string  $driver  name of the write driver to use
	 * @return  void
	 */
	public static function factory($driver)
	{
		// Set the class name
		$class = 'Solr_Writer_'.$driver;

		return new $class;
	}

	/**
	 * Compiles the write data into the proper format and returns it.
	 *
	 * @param   array  $data  array of write data
	 * @return  void
	 */
	abstract public function compile(array $data);

}