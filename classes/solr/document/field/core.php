<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
class Solr_Document_Field_Core {

	/**
	 * @var  string  name of the field
	 */
	protected $_name;

	/**
	 * @var  array  array of values for the field
	 */
	protected $_values;

	/**
	 * @var  float  boost value for the field
	 */
	protected $_boost;

	/**
	 * Sets up the fields properties.
	 *
	 *     new Solr_Document_Field('name', 'T-Shirt');
	 *
	 * or if you want the field to have a boost
	 *
	 *     new Solr_Document_Field('name', 'T-Shirt', 3.0);
	 *
	 * @param  string  $name    the name of the field
	 * @param  mixed   $values  string or array of values for the field
	 * @param  float   $boost   the boost value for the field
	 */
	public function __construct($name, $values, $boost = NULL)
	{
		$this->_name = $name;

		if ( ! is_array($values))
		{
			$values = array($values);
		}

		$this->_values = $values;

		if (($boost !== NULL) AND ((float) $boost > 0.0))
		{
			$this->_boost = (float) $boost;
		}
	}

}