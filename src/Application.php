<?php

namespace Language;

class Application
{
	use LanguageUtil;

	/** @var string Application name */
	protected $name;
	
	/** @var Language[] List of supported languages */
	protected $languages;


	/**
	 * 
	 * @param array $appList
	 * 
	 * @return Application[] Array of applications
	 */
	public static function makeApplications($appList) {
		$result = array();
		foreach ($appList as $application => $languages) {
			$result[] = is_a($languages, 'Application') ? $languages : new Application($application, $languages);
		}
		return $result;
	}

	/**
	 * 
	 * @param string $name
	 * @param Language[] $languages
	 */
	public function __construct($name, $languages) {
		$this->name = $name;
		$this->languages = Language::makeLanguages($languages);
	}

	/**
	 * 
	 * @return string
	 */
	public function name() {
		return $this->name;
	}

	/**
	 * 
	 * @return Language[]
	 */
	public function languages() {
		return $this->languages;
	}
}