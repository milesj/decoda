<?php

class DecodaNode extends DecodaAbstract {
		
	/**
	 * Tag type constants.
	 */
	const TAG_OPEN = 1;
	const TAG_CLOSE = 2;
	const TAG_NONE = 0;
	
	/**
	 * Extracted chunks of text and tags.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_chunks = array();
	
	/**
	 * Children nodes.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_nodes = array();

	/**
	 * The parent node.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_parent = array();

	/**
	 * The parsed string.
	 * 
	 * @access protected
	 * @var string
	 */
	protected $_parsed = '';
	
	/**
	 * The raw string before parsing.
	 * 
	 * @access protected
	 * @var string
	 */
	protected $_string = '';
	
	/**
	 * The wrapping tag if present.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_wrapper = array();
	
	/**
	 * Convert the extracted chunks into nodes. 
	 * Nodes will be created in a parent child hierarchy depending on the amount of nested tags.
	 * 
	 * @access public
	 * @param type $chunks
	 * @param Decoda $parser
	 * @return void
	 */
	public function __construct($chunks, Decoda $parser) {
		if (is_string($chunks)) {
			$this->_string = $chunks;
			return;
		}
		
		$this->setParser($parser);
		
		$first = $chunks[0];
		$last = $chunks[count($chunks) - 1];
		
		// Validate the data
		if ($first['type'] == self::TAG_OPEN && $last['type'] == self::TAG_CLOSE && $first['tag'] == $last['tag']) {
			$this->_wrapper = $first;
		}
		
		$chunks = $this->_cleanChunks($chunks);

		// Generate child nodes
		$tag = array();
		$text = '';
		$openIndex = -1;
		$openCount = -1;
		$closeIndex = -1;
		$closeCount = -1;
				
		foreach ($chunks as $i => $chunk) {
			if ($chunk['type'] == self::TAG_NONE && empty($tag)) {
				$this->addChild( new DecodaNode($chunk['text'], $this->getParser()) );
				
			} else if ($chunk['type'] == self::TAG_OPEN) {
				$openCount++;

				if (empty($tag)) {
					$openIndex = $i;
					$tag = $chunk;
				}
				
			} else if ($chunk['type'] == self::TAG_CLOSE) {
				$closeCount++;

				if ($openCount == $closeCount && $chunk['tag'] == $tag['tag']) {
					$closeIndex = $i;
					$tag = array();

					// Slice a section of the array if the correct closing tag is found
					$this->addChild( new DecodaNode(array_slice($chunks, $openIndex, ($closeIndex - $openIndex) + 1), $this->getParser()) );
				}
			}
			
			$text .= $chunk['text'];
		}
		
		$this->_chunks = $chunks;
		$this->_string = $text;
	}
	
	/**
	 * Add a child node.
	 * 
	 * @access public
	 * @param DecodaNode $node 
	 * @return void
	 */
	public function addChild(DecodaNode $node) {
		$node->setParent($this);

		$this->_nodes[] = $node;
	}
	
	/**
	 * Return the children nodes.
	 * 
	 * @access public
	 * @return array
	 */
	public function getChildren() {
		return $this->_nodes;
	}
	
	/**
	 * Return the parent node.
	 * 
	 * @access public
	 * @return DecodaNode
	 */
	public function getParent() {
		return $this->_parent;
	}
	
	/**
	 * Returns true if the node has children.
	 * 
	 * @access public
	 * @return boolean
	 */
	public function hasChildren() {
		return !empty($this->_nodes);
	}
	
	/**
	 * Returns true if the node has a parent.
	 * 
	 * @access public
	 * @return boolean
	 */
	public function hasParent() {
		return !empty($this->_parent);
	}
	
	/**
	 * Validate that the following child can be nested within the parent.
	 * 
	 * @access public
	 * @param array $parent
	 * @param string $tag
	 * @return boolean 
	 */
	public function isAllowed($parent, $tag) {
		$filter = $this->getParser()->getFilterByTag($tag);

		if (!$filter) {
			return false;
		}
		
		$child = $filter->tag($tag);
		
		if (is_array($parent['allowed']) && in_array($child['tag'], $parent['allowed'])) {
			return true;
		
		} else if ($parent['allowed'] == DecodaFilter::TYPE_BOTH) {
			return true;
			
		} else if (($parent['allowed'] == DecodaFilter::TYPE_INLINE || $parent['allowed'] == DecodaFilter::TYPE_BLOCK) && $child['type'] == DecodaFilter::TYPE_INLINE) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Is the node wrapped in a tag?
	 * 
	 * @access public
	 * @return boolean
	 */
	public function isWrapped() {
		return !empty($this->_wrapper);
	}

	/**
	 * Parse the node hierarchy by looping over all children nodes and parse using the respective filters.
	 * 
	 * @access public
	 * @return string
	 */
	public function parse() {
		if (!empty($this->_parsed)) {
			return $this->_parsed;
		}
		
		// Child nodes, validate and build tags
		if ($this->hasChildren()) {
			foreach ($this->getChildren() as $node) {
				$this->_parsed .= $node->parse();
			}
			
			if ($this->isWrapped()) {
				$this->_parsed = $this->getParser()->getFilterByTag($this->_wrapper['tag'])->parse($this->_wrapper, $this->_parsed);
			}
			
		// No child nodes, return text
		} else {
			// Only nl2br nodes that are purely linebreaks/whitespace
			//if (trim($this->_string) === "") {
				$this->_parsed = nl2br($this->_string);
			//} else {
			//	$this->_parsed = $this->_string;
			//}
		}

		return $this->_parsed;
	}
	
	/**
	 * Set the parent node.
	 * 
	 * @access public
	 * @param DecodaNode $node 
	 * @return void
	 */
	public function setParent(DecodaNode $node) {
		$this->_parent = $node;
	}
	
	/**
	 * Clean the chunk list by verifying that open and closing tags are nested correctly.
	 * 
	 * @access protected
	 * @param array $chunks
	 * @return array 
	 */
	protected function _cleanChunks($chunks) {
		$clean = array();
		$openTags = array();
		$prevTag = array();
		$root = true;
		
		if ($this->isWrapped()) {
			$parent = $this->getParser()->getFilterByTag($this->_wrapper['tag'])->tag($this->_wrapper['tag']);
			$root = false;
		}
		
		foreach ($chunks as $i => $chunk) {
			$prevTag = end($clean);
			
			switch ($chunk['type']) {
				case self::TAG_NONE:
					if ($prevTag['type'] === self::TAG_NONE) {
						$chunk['text'] = $prevTag['text'] . $chunk['text'];
						array_pop($clean);
					}
					
					$clean[] = $chunk;
				break;

				case self::TAG_OPEN:
					if ($root) {
						$clean[] = $chunk;
						$openTags[] = array('tag' => $chunk['tag'], 'index' => $i);
						
					} else if ($i != 0 && $this->isAllowed($parent, $chunk['tag'])) {
						$clean[] = $chunk;
					}
				break;
				
				case self::TAG_CLOSE:
					if ($root) {
						if (empty($openTags)) {
							continue;
						}
						
						$last = end($openTags);
						
						if ($last['tag'] == $chunk['tag']) {
							array_pop($openTags);
						} else {
							while (!empty($openTags)) {
								$last = array_pop($openTags);
								
								if ($last['tag'] != $chunk['tag']) {
									unset($clean[$last['index']]);
								}
							}
						}
						
						$clean[] = $chunk;
						
					} else if ($i != (count($chunks) - 1) && $this->isAllowed($parent, $chunk['tag'])) {
						$clean[] = $chunk;
					}
				break;
			}
		}

		// Remove any unclosed tags
		while (!empty($openTags)) {
			$last = array_pop($openTags);
			unset($clean[$last['index']]);
		}
		
		return array_values($clean);
	}
	
}