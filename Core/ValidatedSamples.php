<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class ValidatedSamples implements Samples {

	private $wit;

	public function __construct(Wit $wit) {
		$this->wit = $wit;
	}

	public function validate(string $text, Entity ...$entities): void {
		$this->wit->response(
			'POST',
			'/samples',
			[['text' => $text, 'entities' => $this->entities($text, $entities)]]
		);
	}

	public function all(
		int $limit,
		int $offset = 0,
		array $ids = [],
		array $values = []
	): array {
		return $this->wit->response(
			'GET',
			sprintf(
				'/samples?%s',
				$this->filter($limit, $offset, $ids, $values)
			)
		);
	}

	public function delete(array $texts): void {
		$this->wit->response(
			'DELETE',
			'/samples',
			array_map(
				static function($text) {
					return ['text' => $text];
				},
				$texts
			)
		);
	}

	private function entities(string $text, array $entities): array {
		return array_map(
			static function(Entity $entity) use ($text) {
				return $entity->structure($text);
			},
			$entities
		);
	}

	private function filter(
		int $limit,
		int $offset,
		array $ids,
		array $values
	): string {
		return http_build_query(
			array_filter(
				[
					'limit' => $limit,
					'offset' => $offset,
					'entity_ids' => implode(',', $ids),
					'entity_values' => $ids ? implode(',', $values) : '',
				]
			)
		);
	}
}