<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
class Solr_Document_Core {

	/**
	 * @var  float  document boost value
	 */
	protected $_boost;

	/**
	 * @var  array  document field values
	 */
	protected $_fields = array();

	/**
	 * Sets up the document and loads it's fields if supplied.
	 *
	 *     new Solr_Document();
	 *
	 * or if you want to load an array of fields automatically
	 *
	 *     new Solr_Document($fields);
	 *
	 * the $fields param can also be an object that implements
	 * the Solr_Resource interface.
	 *
	 * @param  mixed  $fields  array or object of the documents fields
	 * @param  float  $boost   the boost value for the document
	 */
	public function __construct($fields = NULL, $boost = NULL)
	{
		if ($fields !== NULL)
		{
			if (is_array($fields))
			{
				foreach ($fields as $field)
				{
					$this->_fields[] = new Solr_Document_Field($field['name'], $field['values'], Arr::get($field, 'boost'));
				}
			}
			elseif (is_object($fields) AND ($fields instanceof Solr_Resource))
			{
				$this->_fields = $fields->searchable_fields();
			}
			else
			{
				throw new Solr_Exception('The $fields param must be either an array of '
									   . 'fields or an object the implements the '
									   . 'Solr_Resource interface.');
			}
		}

		if (($boost !== NULL) AND ((float) $boost > 0.0))
		{
			$this->_boost = (float) $boost;
		}
	}

	/**
	 * Sets and gets the boost for the document.
	 *
	 * @param   float   $boost  the boost value for the document
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

	/**
	 * Sets and gets the fields for the document.
	 *
	 * @param   mixed  $fields  array or object of the documents fields
	 * @return  mixed
	 */
	public function fields($fields = NULL)
	{
		if ( ! $fields)
			return $this->_fields;

		if (is_array($fields))
		{
			foreach ($fields as $field)
			{
				$boost = Arr::get($field, 'boost');
				$this->_fields[] = new Solr_Document_Field($field['name'], $field['values'], $boost);
			}
		}
		elseif (is_object($fields) AND ($fields instanceof Solr_Resource))
		{
			$this->_fields = $fields->searchable_fields();
		}
		else
		{
			throw new Solr_Exception('The $fields param must be either an array of '
								   . 'fields or an object the implements the '
								   . 'Solr_Resource interface.');
		}

		return $this;
	}

}