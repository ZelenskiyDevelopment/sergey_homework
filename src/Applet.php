<?php

namespace Language;

class Applet
{
	use LanguageUtil;

	/** @var string Applet directory */
	protected $directory;
	
	/** @var string Applet Id */
	protected $id;
	
	/** @var Language[] List of supported languages */
	protected $languages;


	/**
	 * 
	 * @param array $appList
	 * 
	 * @return Applet[] Array of applets
	 */
	public static function makeApplets($appList) {
		$result = array();
		foreach ($appList as $directory => $id) {
			$result[] = is_a($id, 'Applet') ? $id : new Applet($directory, $id);
		}
		return $result;
	}

	/**
	 * 
	 * @param string $directory
	 * @param string $id
	 */
	public function __construct($directory, $id) {
		$this->directory = $directory;
		$this->id = $id;
		$this->languages = false;
//		$this->languages = Language::makeLanguages($languages);
	}

	/**
	 * 
	 * @return string
	 */
	public function directory() {
		return $this->directory;
	}

	/**
	 * 
	 * @return string
	 */
	public function id() {
		return $this->id;
	}

	/**
	 * 
	 * @return Language[]
	 */
	public function languages() {
		if ($this->languages === false) {
			$result = ApiCall::call(
				'system_api',
				'language_api',
				array(
					'system' => 'LanguageFiles',
					'action' => 'getAppletLanguages'
				),
				array('applet' => $this->id)
			);

			try {
				Api::checkForApiErrorResult($result);
			}
			catch (\Exception $e) {
				throw new \Exception('Getting languages for applet (' . $this->id . ') was unsuccessful ' . $e->getMessage());
			}

			$this->languages = Language::makeLanguages($result['data']);
		}
		return $this->languages;
	}
}