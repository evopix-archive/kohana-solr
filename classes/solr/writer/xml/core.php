<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
class Solr_Writer_XML_Core extends Solr_Writer {

	public function compile(array $data)
	{
		$writer = new XMLWriter();
		$writer->openMemory();
		$writer->setIndent(true);
		$writer->startDocument('1.0', 'utf-8');

		foreach ($data as $data_type => $data_value)
		{
			switch ($data_type)
			{
				case 'add':

					$writer->startElement($data_type);

					foreach ($data_value as $name => $values)
					{
						if ( ! is_array($values))
						{
							$values = array($values);
						}

						foreach ($values as $value)
						{
							if ($value instanceof Solr_Document)
							{
								$writer->startElement($name);

								if ($value->boost())
								{
									$writer->writeAttribute('boost', $value->boost());
								}

								foreach ($value->fields() as $field)
								{
									foreach ($field->values() as $field_value)
									{
										$writer->startElement('field');
										$writer->writeAttribute('name', $field->name());

										if ($field->boost())
										{
											$writer->writeAttribute('boost', $field->boost());
										}

										$writer->text($field_value);
										$writer->endElement();
									}
								}

								$writer->endElement();
							}
							else
							{
								if (is_bool($value))
								{
									if ($value)
									{
										$value = 'true';
									}
									else
									{
										$value = 'false';
									}
								}

								$writer->writeAttribute($name, $value);
							}
						}
					}

					$writer->endElement();

				break;
				case 'delete':



				break;
				case 'commit':
				case 'optimize':



				break;
				case 'rollback':

					if ($data_value['rollback'] === TRUE)
					{
						$writer->writeElement('rollback');
					}

				break;
			}
		}

		$writer->endDocument();
		return $writer->outputMemory();
	}

}