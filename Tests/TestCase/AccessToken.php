<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage\TestCase;

use Dasuos\Configuration;

trait AccessToken {

	private $token;

	public function setUp(): void {
		$this->token = (new Configuration\ParsedConfiguration(
			__DIR__ . '/../Config/wit.local.ini'
		))->settings()['token'];
	}
}
