<?php

namespace Language;

class GeneratorApplication extends Generator
{
	/**
	 * Contains the applications which ones require translations.
	 *
	 * @var Application[]
	 */
	protected $applications;

	/**
	 * 
	 * @param string $pathRoot
	 * @param Application[] $applications
	 */
	public function __construct($pathRoot, $applications) {
		parent::__construct($pathRoot);
		$this->applications = $applications;
	}

	/**
	 * Starts the language file generation.
	 *
	 * @return void
	 */
	public function generateLanguageFiles()
	{
		echo "\nGenerating language files\n";
		foreach ($this->applications as $application) {
			echo "[APPLICATION: " . $application->name() . "]\n";
			foreach ($application->languages() as $language) {
				echo "\t[LANGUAGE: " . $language->name() . "] ";
				if ($this->getLanguageFile($application, $language)) {
					echo " OK\n";
				}
				else {
					throw new \Exception('Unable to generate language file!');
				}
			}
		}
	}

	/**
	 * Gets the language file for the given language and stores it.
	 *
	 * @param Application $application   The name of the application.
	 * @param Language $language      The identifier of the language.
	 *
	 * @throws CurlException   If there was an error during the download of the language file.
	 *
	 * @return bool   The success of the operation.
	 */
	protected function getLanguageFile($application, $language)
	{
		$result = false;
		$languageResponse = ApiCall::call(
			'system_api',
			'language_api',
			array(
				'system' => 'LanguageFiles',
				'action' => 'getLanguageFile'
			),
			array('language' => $language->name())
		);

		try {
			Api::checkForApiErrorResult($languageResponse);
		}
		catch (\Exception $e) {
			throw new \Exception('Error during getting language file: (' . $application->name() . '/' . $language->name() . ')');
		}

		// If we got correct data we store it.
		$destination = $this->getLanguageCachePath($application) . $language->name() . '.php';
		echo $destination;

		$result = file_put_contents($destination, $languageResponse['data']);

		return (bool)$result;
	}

	/**
	 * Gets the directory of the cached language files.
	 *
	 * @param Application $application   The application.
	 *
	 * @return string   The directory of the cached language files.
	 */
	protected function getLanguageCachePath($application)
	{
		$path = $this->pathRoot . '/cache/' . $application->name(). '/';
		// If there is no folder yet, we'll create it.
		if (!is_dir($path)) {
			mkdir($path, 0755, true);
		}
		return $path;
	}
}