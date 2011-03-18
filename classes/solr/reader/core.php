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