<?php

namespace Language;

class Generator
{
	/** @var string */
	protected $pathRoot;

	/**
	 * 
	 * @param string $pathRoot
	 */
	public function __construct($pathRoot) {
		$this->pathRoot = $pathRoot;
	}

		/**
	 * Starts the language file generation.
	 *
	 * @return void
	 */
	public function generateLanguageFiles() {}
}