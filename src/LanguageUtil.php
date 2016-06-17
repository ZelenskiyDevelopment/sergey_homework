<?php

namespace Language;

trait LanguageUtil {
	/**
	 * 
	 * @return Language[]
	 */
    abstract public function languages();

	/**
	 * 
	 * @return string[]
	 */
	public function languageNames() {
		$result = array();
		
		foreach ($this->languages() as $language) {
			$result[] = $language->name();
		}
		return $result;
	}
}