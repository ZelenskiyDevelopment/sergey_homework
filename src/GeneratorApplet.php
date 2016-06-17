<?php

namespace Language;

class GeneratorApplet extends Generator
{
	/**
	 * Contains the applications which ones require translations.
	 *
	 * @var Applet[]
	 */
	protected $applets;

	/**
	 * 
	 * @param string $pathRoot
	 * @param Applet[] $applets
	 */
	public function __construct($pathRoot, $applets) {
		parent::__construct($pathRoot);
		$this->applets = $applets;
	}
	
	/**
	 * Gets the language files for the applet and puts them into the cache.
	 *
	 * @throws Exception   If there was an error.
	 *
	 * @return void
	 */
	public function generateLanguageFiles()
	{
		echo "\nGetting applet language XMLs..\n";

		$path = $this->getLanguageCachePath();
		foreach ($this->applets as $applet) {
			echo " Getting > ". $applet->id() . ' (' . $applet->directory() . ") language xmls..\n";
			if (empty($applet->languages())) {
				throw new \Exception('There is no available languages for the ' . $applet->id() . ' applet.');
			}
			else {
				echo ' - Available languages: ' . implode(', ', $applet->languageNames()) . "\n";
			}
			foreach ($applet->languages() as $language) {
				$xmlContent = self::getAppletLanguageFile($applet, $language);
				$xmlFile    = $path . 'lang_' . $language->name() . '.xml';
				if (strlen($xmlContent) == file_put_contents($xmlFile, $xmlContent)) {
					echo " OK saving $xmlFile was successful.\n";
				}
				else {
					throw new \Exception('Unable to save applet: (' . $applet->id() . ') language: (' . $language->name()
						. ') xml (' . $xmlFile . ')!');
				}
			}
			echo " < " . $applet->id() . ' (' . $applet->directory() . ") language xml cached.\n";
		}

		echo "\nApplet language XMLs generated.\n";
	}


	/**
	 * Gets a language xml for an applet.
	 *
	 * @param Applet $applet      The identifier of the applet.
	 * @param Language $language    The language identifier.
	 *
	 * @return string|false   The content of the language file or false if weren't able to get it.
	 */
	protected static function getAppletLanguageFile($applet, $language)
	{
		$result = ApiCall::call(
			'system_api',
			'language_api',
			array(
				'system' => 'LanguageFiles',
				'action' => 'getAppletLanguageFile'
			),
			array(
				'applet' => $applet->id(),
				'language' => $language->name()
			)
		);

		try {
			Api::checkForApiErrorResult($result);
		}
		catch (\Exception $e) {
			throw new \Exception('Getting language xml for applet: (' . $applet->id() . ') on language: (' . $language->name() . ') was unsuccessful: '
				. $e->getMessage());
		}

		return $result['data'];
	}

	/**
	 * Gets the directory of the cached language files.
	 *
	 * @return string   The directory of the cached language files.
	 */
	protected function getLanguageCachePath()
	{
		$path = $this->pathRoot . '/cache/flash/';
		// If there is no folder yet, we'll create it.
		if (!is_dir($path)) {
			mkdir($path, 0755, true);
		}
		return $path;
	}
}