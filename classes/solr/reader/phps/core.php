<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
class Solr_Reader_PHPS_Core extends Solr_Reader {

	/**
	 * Parses the serialized php response into an object.
	 *
	 * @return  object
	 */
	public function parse()
	{
		$body = $this->_response->body();
		return $this->_to_object(unserialize($body));
	}

	/**
	 * Converts the multidimensional response array into an object.
	 *
	 * @param   array   $array  multidimensional response array
	 * @return  object
	 */
	protected function _to_object($array)
	{
		if (is_array($array) AND (count($array) > 0))
		{
			$object = (object) $array;

			foreach ($object as $property => $value)
			{
				if (is_array($value) AND (count($value) > 0))
				{
					$object->$property = $this->_to_object($value);
				}
			}

			return $object;
		}

		return FALSE;
	}
}