<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class PositionedSample implements Sample {

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
				return [
					'entity' => $entity['entity'],
					'value' => $entity['value'],
				]
				+ $this->positioning($text, $entity)
				+ $this->subentities($entity);
			},
			$entities
		);
	}

	private function subentities(array $entity): array {
		return isset($entity['subentities'])
			? array_map(
				function($subentity) use ($entity) {
					return [
						'entity' => $subentity['entity'],
						'value' => $subentity['value'],
					] + $this->positioning($entity['value'], $subentity);
				},
				$entity['subentities']
			)
			: [];
	}

	private function positioning(string $text, array $entity): array {
		$start = strpos($text, $entity['value']);
		return $start !== false
			? ['start' => $start, 'end' => $start + strlen($entity['value'])]
			: [];
	}
}