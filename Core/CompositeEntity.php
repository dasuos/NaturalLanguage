<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class CompositeEntity implements Entity {

	private $origin;
	private $subentities;

	public function __construct(Entity $origin, Entity ...$subentities) {
		$this->origin = $origin;
		$this->subentities = $subentities;
	}

	public function structure(string $text = self::NO_SOURCE): array {
		$structure = $this->origin->structure($text);
		return $structure + [
			'subentities' => array_map(
				static function(Entity $subentity) use ($text) {
					return $subentity->structure($text);
				},
				$this->subentities
			),
		];
	}
}