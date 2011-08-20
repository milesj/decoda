<?php

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