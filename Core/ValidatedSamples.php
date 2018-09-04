<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class ValidatedSamples implements Samples {

	private $wit;

	public function __construct(Wit $wit) {
		$this->wit = $wit;
	}

	public function validate(string $text, Entity ...$entities): void {
		$response = $this->wit->response(
			'POST',
			'samples',
			[['text' => $text, 'entities' => $this->entities($text, $entities)]]
		);
		if (!$this->sent($response))
			$this->throw('Sample validation failed', $response);
	}

	public function all(
		int $limit,
		int $offset = 0,
		array $ids = [],
		array $values = []
	): array {
		$response = $this->wit->response(
			'GET',
			sprintf(
				'samples?limit=%s&offset=%s',
				$limit,
				$offset
				. $this->filter('entity_ids', $ids)
				. $this->filter('entity_values', $values)
			)
		);
		if ($this->sent($response))
			return $response;
		$this->throw('Getting sample list failed', $response);
	}

	public function delete(array $texts): void {
		$response = $this->wit->response(
			'DELETE',
			'samples',
			array_map(
				static function($text) {
					return ['text' => $text];
				},
				$texts
			)
		);
		if (!$this->sent($response))
			$this->throw('Sample delete failed', $response);
	}

	private function entities(string $text, array $entities): array {
		return array_map(
			static function(Entity $entity) use ($text) {
				return $entity->structure($text);
			},
			$entities
		);
	}

	private function filter(string $name, array $list): string {
		return $list ? sprintf('&%s=%s', $name, implode(',', $list)) : '';
	}

	private function sent(array $response): bool {
		return !isset($response['error']);
	}

	private function throw(string $message, array $response): void {
		throw new \UnexpectedValueException(
			sprintf(
				'%s: %s',
				$message,
				$response['error']
			)
		);
	}
}