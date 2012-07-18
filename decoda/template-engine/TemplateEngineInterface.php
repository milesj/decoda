<?php
/**
 * TemplateEngineInterface
 *
 * This interface represents the engine for tags which uses a template. 
 * It contains the path were the templates for the tags are located and the logic
 * to render these templates.
 *
 * @author      Miles Johnson - http://milesj.me
 * @author      Sean C. Koop - sean.koop@icans-gmbh.com
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */


interface TemplateEngineInterface {
    
	/**
	 * Sets the path to the tag templates.
	 *
	 * @access public
	 * @param string $path
	 */
	public function setPath($path);

	/**
	 * Returns the path of the tag templates.
	 *
	 * @access public
	 * @return string
	 */
	public function getPath();

	/**
	 * Renders the tag by using the defined templates.
	 * In case no template were found for the tag, a exception will be thrown.
	 *
	 * @access public
	 * @param array $tag Contains the data of the tag.
	 * @param string $content The content within the tag.
	 * @return string
	 * @throws Exception
	 */
	public function render(array $tag, $content);
}
