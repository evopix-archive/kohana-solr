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

	/**
	 * Sets and gets the name for the document field.
	 *
	 * @param   string   $name  the name for the document field
	 * @return  mixed
	 */
	public function name($name = NULL)
	{
		if ( ! $name)
			return $this->_name;

		$this->_name = $name;
		return $this;
	}

	/**
	 * Sets and gets the values for the document field.
	 *
	 * @param   mixed   $values  the values for the document field
	 * @return  mixed
	 */
	public function values($values = NULL)
	{
		if ( ! $values)
			return $this->_values;

		if ( ! is_array($values))
		{
			$values = array($values);
		}

		$this->_values = $values;
		return $this;
	}

	/**
	 * Sets and gets the boost for the document field.
	 *
	 * @param   float   $boost  the boost value for the document field
	 * @return  mixed
	 */
	public function boost($boost = NULL)
	{
		if ( ! $boost)
			return $this->_boost;

		if ((float) $boost > 0.0)
		{
			$this->_boost = (float) $boost;
		}

		return $this;
	}

}