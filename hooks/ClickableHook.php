<?php

class ClickableHook extends DecodaHook {
	
	/**
	 * Matches a link or an email, and converts it to an anchor tag.
	 * 
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function parse($content) {
		if ($this->_parser->getFilter('Url')) {
			$protocol = '(http|ftp|irc|file|telnet)s?:\/?\/?';
			$login = '([-a-zA-Z0-9\.\+]+:[-a-zA-Z0-9\.\+]+@)?';
			$domain = '([-a-zA-Z0-9\.]{5,255}+)';
			$port = '(:[0-9]{0,6}+)?';
			$query = '([a-zA-Z0-9'. preg_quote('-_=;:&?/[]', '/') .']+)?';
			$content = preg_replace_callback('/(^|\n|\s)'. $protocol . $login . $domain . $port . $query .'/is', array($this, '_urlCallback'), $content);
		}

		// Based on schema: http://en.wikipedia.org/wiki/Email_address
		if ($this->_parser->getFilter('Email')) {
			$content = preg_replace_callback(EmailFilter::EMAIL_PATTERN, array($this, '_emailCallback'), $content);
		}

		return $content;
	}

    /**
     * Callback for email processing.
     *
     * @access protected
     * @param array $matches
     * @return string
     */
    protected function _emailCallback($matches) {
		return $this->_parser->getFilter('Email')->parse(array(
			'tag' => 'email',
			'attributes' => array()
		), trim($matches[0]));
    }

    /**
     * Callback for URL processing.
     *
     * @access protected
     * @param array $matches
     * @return string
     */
    protected function _urlCallback($matches) {
		return $this->_parser->getFilter('Url')->parse(array(
			'tag' => 'url',
			'attributes' => array()
		), trim($matches[0]));
    }
	
}