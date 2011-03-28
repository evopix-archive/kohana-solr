<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
class Solr_Reader_XML_Core extends Solr_Reader {

	/**
	 * Parses the XML response into an array.
	 *
	 * @return  array
	 */
	public function parse()
	{
		$body = $this->_response->body();
		$xml = simplexml_load_string($body);
		return $this->_to_array($xml);
	}

	/**
	 * Convert a SimpleXML object into an array.
	 *
	 * @param   SimpleXMLElement  $xml   the xml to convert
	 * @return  array
	 */
	protected function _to_array($xml)
	{
		if ( ! $xml->count())
		{
			// no children, cast the value to a string and return it
			return (string) $xml;
		}

		$array = array();
		foreach ($xml->children() as $element => $node)
		{
			$attributes = $node->attributes();

			switch ($element)
			{
				case 'lst':
				case 'arr':

					$array[(string) $attributes->name] = $this->_to_array($node);

				break;
				case 'result':

					$array['response'] = array(
						'numFound' => (int) $attributes->numFound,
						'start' => (int) $attributes->start,
						'docs' => $this->_to_array($node),
					);

				break;
				case 'doc':

					$array[] = $this->_to_array($node);

				break;
				case 'str':
				case 'date':
				case 'int':
				case 'float':
				case 'bool':

					if ($name = (string) $attributes->name)
					{
						$array[$name] = $this->_to_array($node);
					}
					else
					{
						$array[] = $this->_to_array($node);
					}

				break;
			}
		}

		return $array;
	}

}