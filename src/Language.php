<?php

namespace Language;

class Language
{
	/** @var string Language name */
	protected $name;


	/**
	 * 
	 * @param array $langList
	 * 
	 * @return Language[] Array of applications
	 */
	public static function makeLanguages($langList) {
		$result = array();
		foreach ($langList as $language) {
			$result[] = is_a($language, 'Language') ? $language : new Language($language);
		}
		return $result;
	}

	/**
	 * 
	 * @param string $name
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * 
	 * @return string
	 */
	public function name() {
		return $this->name;
	}
}