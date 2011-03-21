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
	 * @var  string  url of the host to use
	 */
	public static $host;

	/**
	 * @var  string  name of the write format to use
	 */
	public static $write_format;

	/**
	 * @var  string  name of the write response format to use
	 */
	public static $write_response_format;

	/**
	 * @var  string  name of the read format to use
	 */
	public static $read_format;

	/**
	 * @var  string  name of the read response format to use
	 */
	public static $read_response_format;

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

	/**
	 * Loads the default config values.
	 *
	 * @return  void
	 */
	protected function __construct()
	{
		foreach (Kohana::config('solr') as $property => $value)
		{
			// Only overwrite the property if it hasn't been set
			if (Solr::$$property === NULL)
			{
				Solr::$$property = $value;
			}
		}
	}

	/**
	 * Adds document(s) to the Solr index.
	 *
	 * @param   mixed    $documents      single document or an array of documents
	 * @param   bool     $overwrite      if TRUE newer documents will replace previously added documents with the same uniqueKey
	 * @param   integer  $commit_within  number of milliseconds within which to add the documents to the index
	 * @return  mixed
	 */
	public function add($documents, $overwrite = TRUE, $commit_within = NULL)
	{
		if ( ! is_array($documents))
		{
			$documents = array($documents);
		}

		$request = new Solr_Request_Write();
		$request->overwrite($overwrite);

		if (($commit_within !== NULL) AND ($commit_within > 0))
		{
			$request->commit_within($commit_within);
		}

		$request->documents($documents);

		return $request->execute();
	}

	/**
	 * Commits updates to the Solr index.
	 *
	 * @return  Response
	 */
	public function commit()
	{
		$request = new Solr_Request_Write();
		$request->commit(TRUE);

		return $request->execute();
	}

	/**
	 * Optimizes the Solr index.
	 *
	 * @param   array     $attributes  array of optimize attributes
	 * @return  Response
	 */
	public function optimize(array $attributes = NULL)
	{
		if ($attributes === NULL)
		{
			$attributes = TRUE;
		}

		$request = new Solr_Request_Write();
		$request->optimize($attributes);

		return $request->execute();
	}

}