<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
class Solr_Core {

	/**
	 * @var  Solr  singleton instance of solr
	 */
	public static $instance;

	/**
	 * Solr singleton instance.
	 *
	 *     $solr = Solr::instance();
	 *
	 * @return  Solr
	 **/
	public static function instance()
	{
		if ( ! Solr::$instance)
		{
			Solr::$instance = new Solr;
		}

		return Solr::$instance;
	}

}