<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class PositioningSample implements Sample {

	private $text;
	private $entities;

	public function __construct(string $text, array ...$entities) {
		$this->text = $text;
		$this->entities = $entities;
	}

	public function structure(): array {
		return [
			'text' => $this->text,
			'entities' => $this->entities($this->text, $this->entities),
		];
	}

	private function entities(string $text, array $entities): array {
		return array_map(
			function($entity) use ($text) {
				return $this->entity($entity, $text)
					+ $this->subentities($entity);
			},
			$entities
		);
	}

	private function subentities(array $entity): array {
		return isset($entity['subentities'])
			? array_map(
				function($subentity) use ($entity) {
					return $this->entity($subentity, $entity['value']);
				},
				$entity['subentities']
			) : [];
	}

	private function entity(array $entity, string $text): array {
		return ['entity' => $entity['entity'], 'value' => $entity['value']]
			+ $this->position($entity, $text);
	}

	private function position(array $entity, string $text): array {
		$start = $entity['start'] ?? strpos($text, $entity['value']);
		return $start !== false
			? [
				'start' => $start,
				'end' => $entity['end'] ?? $start + strlen($entity['value']),
			] : [];
	}
}
