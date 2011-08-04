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