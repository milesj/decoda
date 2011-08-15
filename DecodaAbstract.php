<?php

abstract class DecodaAbstract {
	
	/**
	 * Configuration.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_config = array();
	
	/**
	 * Parent Decoda object.
	 * 
	 * @access protected
	 * @var Decoda
	 */
	protected $_parser;
	
	/**
	 * Apply configuration when instantiated.
	 * 
	 * @access public
	 * @param array $config 
	 * @return void
	 */
	public function __construct(array $config = array()) {
		$this->_config = $config + $this->_config;
	}
	
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