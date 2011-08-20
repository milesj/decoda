<?php
/**
 * DecodaAbstract
 *
 * Base class for filters and hooks to extend.
 *
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2011, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

abstract class DecodaAbstract {

	/**
	 * Parent Decoda object.
	 * 
	 * @access protected
	 * @var Decoda
	 */
	protected $_parser;

	/**
	 * Return the Decoda parser.
	 * 
	 * @access public
	 * @return Decoda
	 */
	public function getParser() {
		return $this->_parser;
	}

	/**
	 * Set the Decoda parser.
	 * 
	 * @access public
	 * @param Decoda $parser 
	 * @return void
	 */
	public function setParser(Decoda $parser) {
		$this->_parser = $parser;
	}
	
}