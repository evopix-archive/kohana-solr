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
	 * @var  array  Solr instances
	 */
	public static $instances = array();

	/**
	 * @var  array  configuration parameters
	 */
	protected $_config;

	/**
	 * Get a singleton Solr instance. If a configuration is not specified,
	 * it will be loaded from the solr configuration file using the same
	 * group as the name.
	 *
	 *     // Load the default Solr instance
	 *     $solr = Solr::instance();
	 *
	 *     // Create a custom configured Solr instance
	 *     $solr = Solr::instance('custom', $config);
	 *
	 * @param   string  $name    instance name
	 * @param   array   $config  configuration parameters
	 * @return  Solr
	 **/
	public static function instance($name = 'default', array $config = NULL)
	{
		if ( ! isset(Solr::$instances[$name]))
		{
			if ($config === NULL)
			{
				$config = Kohana::config('solr')->$name;
			}
		}

		return Solr::$instances[$name] = new Solr($config);
	}

	/**
	 * Stores the configuration parameters.
	 *
	 * @param   array  $config  configuration parameters
	 * @return  void
	 */
	protected function __construct($config)
	{
		$this->_config = $config;
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

		$request = $this->write_open();
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
		$request = $this->write_open();
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

		$request = $this->write_open();
		$request->optimize($attributes);

		return $request->execute();
	}

	/**
	 * Rolls back all add/deletes made to the index since the last commit.
	 *
	 * @return  Response
	 */
	public function rollback()
	{
		$request = $this->write_open();
		$request->rollback(TRUE);

		return $request->execute();
	}

	/**
	 * Deletes a document from the index by id.
	 *
	 * @param   string    $id  id of the document to delete
	 * @return  Response
	 */
	public function delete($id)
	{
		$request = $this->write_open();
		$request->delete($id);

		return $request->execute();
	}

	/**
	 * Deletes all documents that match the given query.
	 *
	 * @param   string    $query  search query for documents to delete
	 * @return  Response
	 */
	public function delete_by_query($query)
	{
		$request = $this->write_open();
		$request->delete_by_query($query);

		return $request->execute();
	}

	/**
	 * Retrieves all documents that match the given query.
	 *
	 * @param   string   $query   raw query string
	 * @param   integer  $offset  starting offset for documents
	 * @param   integer  $limit   maximum number of documents to return
	 * @param   array    $params  array of optional query parameters
	 * @return  Solr_Response_Read
	 */
	public function search($query, $offset = 0, $limit = 10, array $params = NULL)
	{
		$request = $this->read_open();
		$request->query($query);
		$request->offset($offset);
		$request->limit($limit);

		if (is_array($params) AND (count($params) > 0))
		{
			$request->params($params);
		}

		return $request->execute();
	}

	/**
	 * Opens a new read request.
	 *
	 * @return  Solr_Request_Read
	 */
	public function read_open()
	{
		return new Solr_Request_Read(
			$this->_config['host'],
			$this->_config['reader'],
			$this->_config['writer']
		);
	}

	/**
	 * Opens a new write request.
	 *
	 * @return  Solr_Request_Write
	 */
	public function write_open()
	{
		return new Solr_Request_Write(
			$this->_config['host'],
			$this->_config['reader'],
			$this->_config['writer']
		);
	}

}