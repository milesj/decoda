<?php

/**
 * PhpEngine
 *
 * Renders tags by using PHP as template engine.
 *
 * @author      Miles Johnson - http://milesj.me
 * @author      Sean C. Koop - sean.koop@icans-gmbh.com
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

class PhpEngine implements TemplateEngineInterface {

	/**
	 * @access protected
	 * @var string
	 */
	protected $_path;

	/**
	 * @access protected
	 * @var DecodaFilter
	 */
	protected $_filter;

	/**
	 * @access public
	 * @param DecodaFilter $filter
	 */
	public function __construct(DecodaFilter $filter) {
		$this->_filter = $filter;
	}

	/**
	 * Returns the path of the tag templates. In case no path were set the default decoda path will be used.
	 *
	 * @access public
	 * @return string
	 */
	public function getPath() {
		if (empty($this->_path)) {
			$this->_path = DECODA . '/templates/';
		}

		return $this->_path;
	}

	/**
	 * Sets the path to the tag templates.
	 *
	 * @access public
	 * @param string $path
	 */
	public function setPath($path) {
		$this->_path = $path;
	}

	/**
	 * Renders the tag by using php templates.
	 * In case no template were found for the tag, a exception will be thrown.
	 *
	 * @access public
	 * @param array $tag Contains the information about a tag.
	 * @param string $content The content in the tag.
	 * @return string
	 * @throws Exception
	 */
	public function render(array $tag, $content) {
		$setup = $this->getFilter()->tag($tag['tag']);
		$path = $this->getPath() . $setup['template'] . '.php';

		if (!file_exists($path)) {
			throw new Exception(sprintf('Template file %s does not exist.', $setup['template']));
		}

		$vars = array();

		foreach ($tag['attributes'] as $key => $value) {
			if (isset($setup['map'][$key])) {
				$key = $setup['map'][$key];
			}

			$vars[$key] = $value;
		}

		extract($vars, EXTR_SKIP);
		ob_start();

		include $path;

		return ob_get_clean();
	}

	/**
	 * Returne the used Filter for this template. Necessary, because some templates need the message method
	 * of the filter class.
	 *
	 * @access public
	 * @return DecodaFilter
	 */
	public function getFilter() {
		return $this->_filter;
	}

}

?>
