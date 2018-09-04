<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class FakeEntity implements Entity {

	private $structure;

	public function __construct(array $structure) {
		$this->structure = $structure;
	}

	public function structure(string $text = self::NO_SOURCE): array {
		return $this->structure;
	}
}