<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class EntityInText implements Entity {

	private const NO_OCCURRENCE = [];

	private $name;
	private $value;
	private $start;
	private $end;

	public function __construct(
		string $name,
		string $value,
		int $start = 0,
		int $end = 0
	) {
		$this->name = $name;
		$this->value = $value;
		$this->start = $start;
		$this->end = $end;
	}

	public function structure(string $text = self::NO_SOURCE): array {
		if (!($this->occurrences($text, $this->value) === 1 || $this->end))
			throw new \UnexpectedValueException(
				sprintf(
					"Entity '%s' must occur exactly once in the text '%s'",
					$this->value,
					$text
				)
			);
		return ['entity' => $this->name, 'value' => $this->value]
			+ $this->positioning($text);
	}

	private function positioning(string $source): array {
		$start = $this->end ? $this->start : strpos($source, $this->value);
		return $start !== false
			? [
				'start' => $start,
				'end' => $this->end ?: $start + strlen($this->value),
			]
			: self::NO_OCCURRENCE;
	}

	private function occurrences(string $text, string $value): int {
		return substr_count($text, $value);
	}
}