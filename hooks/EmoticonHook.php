<?php

class EmoticonHook extends DecodaHook {
	
    /**
     * Mapping of emoticons and smilies.
     *
     * @access protected
     * @var array
     */
    protected $_emoticons = array();
	
    /**
     * Load the emoticons from the JSON file.
     *
     * @access public
     * @return void
     */
    public function __construct() {
		$path = DECODA_CONFIG .'emoticons.json';

		if (file_exists($path)) {
			$this->_emoticons = json_decode(file_get_contents($path), true);
		}
    }
	
	/**
	 * Parse out the emoticons and replace with images.
	 * 
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function parse($content) {
		$imageFilter = $this->_parser->getFilter('Image');
		
		if (!$imageFilter) {
			return $content;
		}
		
		$path = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', DECODA_EMOTICONS);
		$path = str_replace(array('\\', '/'), '/', $path);

		foreach ($this->_emoticons as $emoticon => $smilies) {
			foreach ($smilies as $smile) {
				$image = $imageFilter->parse(array(
					'tag' => 'img',
					'attributes' => array()
				), $path . $emoticon .'.png');

				$content = preg_replace('/(\s)?'. preg_quote($smile, '/') .'(\s)?/is', '$1'. $image .'$2', $content);
				unset($image);
			}
		}
		
		return $content;
	}
	
}
