<?php

abstract class DecodaHook {
	
	public function beforeParse(Decoda $decoda) {
		
	}
	
	public function afterParse($content, Decoda $decoda) {
		return $content;
	}
	
}