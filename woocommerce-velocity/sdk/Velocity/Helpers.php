<?php

class Velocity_Helpers{

	private function __construct() {}
	private function __clone() {}
	private function __wakeup() {}

	/*
	 * Converts a string from camelCase to under_score notation.
	 * @param string $string camelcase
	 * @return string strtolower(preg_replace('/(?<=\\w)(?=[A-Z0-9])/', "_$1", $string)) converted into underscore string
	 */
	 
	public static function underscore($string){
		return strtolower(preg_replace('/(?<=\\w)(?=[A-Z0-9])/', "_$1", $string));
	}
	
}