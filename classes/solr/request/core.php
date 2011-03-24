<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
abstract class Solr_Request_Core {

	/**
	 * @var  Solr_Response  response
	 */
	protected $_response;

	/**
	 * @var  string  the Solr host url
	 */
	protected $_host;

	/**
	 * @var  string  the writer type name
	 */
	protected $_writer_type = 'xml';

	/**
	 * @var  string  the reader type name
	 */
	protected $_reader_type = 'json';

	/**
	 * @var  string  uri to the servlet that handles this request
	 */
	protected $_handler;

	/**
	 * Sets up required params.
	 *
	 * @param   string  $host    the solr host url
	 * @param   string  $reader  the read driver to use
	 * @param   string  $writer  the write driver to use
	 * @return  void
	 */
	public function __construct($host, $reader_type = NULL, $writer_type = NULL)
	{
		$this->_host = $host;

		if ($reader_type !== NULL)
		{
			$this->_reader_type = $reader_type;
		}

		if ($writer_type !== NULL)
		{
			$this->_writer_type = $writer_type;
		}
	}

	/**
	 * Returns the full url for the current request.
	 *
	 * @return  string
	 */
	protected function _compile_url()
	{
		return 'http://'.$this->_host.$this->_handler.'?wt='.$this->_reader_type;
	}

}