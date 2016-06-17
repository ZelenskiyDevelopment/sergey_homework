<?php

namespace Language;

/**
 * Business logic related to generating language files.
 */
class LanguageBatchBo
{
	/**
	 * Starts the language file generation.
	 *
	 * @return void
	 */
	public static function generateLanguageFiles()
	{
		$applications = Application::makeApplications(Config::get('system.translated_applications'));
		$generator = new GeneratorApplication(Config::get('system.paths.root'), $applications);
		$generator->generateLanguageFiles();
	}

	/**
	 * Gets the language files for the applet and puts them into the cache.
	 *
	 * @throws Exception   If there was an error.
	 *
	 * @return void
	 */
	public static function generateAppletLanguageXmlFiles()
	{
		// List of the applets [directory => applet_id].
		$applets = Applet::makeApplets(array(
			'memberapplet' => 'JSM2_MemberApplet',
		));

		$generator = new GeneratorApplet(Config::get('system.paths.root'), $applets);
		$generator->generateLanguageFiles();
	}
}
