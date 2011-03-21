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
	public static function factory($driver = NULL)
	{
		if ($driver === NULL)
		{
			// Use the default driver
			$driver = Kohana::config('solr.reader');
		}

		// Set the class name
		$class = 'Solr_Reader_'.$driver;

		return new $class;
	}

}