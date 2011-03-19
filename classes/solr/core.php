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

}