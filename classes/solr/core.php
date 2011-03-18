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

	public function add_document(Solr_Document $document, $allow_duplicates = FALSE, $overwrite_pending = TRUE, $overwrite_committed = TRUE)
	{
		$documents = array($document);
		return $this->add_documents($documents, $allow_duplicates, $overwrite_pending, $overwrite_committed);
	}

	public function add_documents(array $documents, $allow_duplicates = FALSE, $overwrite_pending = TRUE, $overwrite_committed = TRUE)
	{
		$request = new Solr_Request_Write();
		$request->data('add', 'allowDups', $allow_duplicates);
		$request->data('add', 'overwritePending', $overwrite_pending);
		$request->data('add', 'overwriteCommitted', $overwrite_committed);
		$request->data('add', 'doc', $documents);

		return $request->execute();
	}

}