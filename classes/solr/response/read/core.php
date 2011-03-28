<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Solr powered search for the Kohana Framework.
 *
 * @package    Solr
 * @author     Brandon Summers <brandon@brandonsummers.name>
 * @copyright  (c) 2011 Brandon Summers
 * @license    MIT
 */
class Solr_Response_Read_Core extends Solr_Response {

	/**
	 * Gets the number of documents that were found.
	 *
	 * @return  integer
	 */
	public function found()
	{
		return (int) Arr::get($this->response(), 'numFound');
	}

	/**
	 * Gets the starting offset.
	 *
	 * @return  integer
	 */
	public function offset()
	{
		return (int) Arr::get($this->response(), 'start');
	}

	/**
	 * Gets the documents that the request returned.
	 *
	 * @return  array
	 */
	public function documents()
	{
		$documents = array();

		if ($docs = Arr::get($this->response(), 'docs'))
		{
			foreach ($docs as $doc)
			{
				$document = new Solr_Document;
				$fields = array();

				foreach ($doc as $field_name => $field_value)
				{
					$fields[] = array(
						'name' => $field_name,
						'values' => $field_value,
					);
				}

				$document->fields($fields);
				$documents[] = $document;
			}
		}

		return $documents;
	}

	/**
	 * Gets the Facet queries.
	 *
	 * @return  array
	 */
	public function facet_queries()
	{
		return Arr::path($this->response(), 'facet_counts.facet_queries');
	}

	/**
	 * Gets the Facets for all fields.
	 *
	 * @return  array
	 */
	public function facet_fields()
	{
		return Arr::path($this->response(), 'facet_counts.facet_fields');
	}

	/**
	 * Gets the Facets for a single field.
	 *
	 * @param   string  $field  name of the field
	 * @return  array
	 */
	public function facet_field($field)
	{
		return Arr::path($this->response(), 'facet_counts.facet_fields.'.$field);
	}

	/**
	 * Gets the Highlighting data for either all documents or a single document
	 * if $id is given.
	 *
	 * @param   string  $id  id of the document
	 * @return  array
	 */
	public function highlighting($id = NULL)
	{
		if ($id)
		{
			return Arr::path($this->response(), 'highlighting.'.$id);

		}

		return Arr::get($this->response(), 'highlighting');
	}

	/**
	 * Gets the MoreLikeThis data for either all documents or a single document
	 * if $id is given.
	 *
	 * @param   string  $id  id of the document
	 * @return  array
	 */
	public function more_like_this($id = NULL)
	{
		if ($id)
		{
			return Arr::path($this->response(), 'moreLikeThis.'.$id);

		}

		return Arr::get($this->response(), 'moreLikeThis');
	}

	/**
	 * Gets the TermVector data.
	 *
	 * @return  array
	 */
	public function term_vectors()
	{
		return Arr::get($this->response(), 'termVectors');
	}

	/**
	 * Gets the Stats data for either all fields or a single field
	 * if $field is given.
	 *
	 * @param   string  $field  id of the field
	 * @return  array
	 */
	public function stats_fields($field = NULL)
	{
		if ($field)
		{
			return Arr::path($this->response(), 'stats_fields.'.$field);

		}

		return Arr::get($this->response(), 'stats_fields');
	}

	/**
	 * Gets the Terms data for either all fields or a single field
	 * if $field is given.
	 *
	 * @param   string  $field  id of the field
	 * @return  array
	 */
	public function terms($field = NULL)
	{
		if ($field)
		{
			return Arr::path($this->response(), 'terms.'.$field);

		}

		return Arr::get($this->response(), 'terms');
	}

}