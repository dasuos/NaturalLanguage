<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class HttpSamples implements Samples {

	private $wit;

	public function __construct(Wit $wit) {
		$this->wit = $wit;
	}

	public function validate(Sample ...$samples): array {
		return $this->wit->response(
			'POST',
			new ParsedEndpoint('/samples'),
			array_map(
				static function(Sample $sample) {
					return $sample->structure();
				},
				$samples
			)
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
			new ParsedEndpoint(
				'/samples',
				array_filter(
					[
						'limit' => $limit,
						'offset' => $offset,
						'entity_ids' => implode(',', $ids),
						'entity_values' => $ids ? implode(',', $values) : '',
					],
					'strlen'
				)
			)
		);
	}

	public function delete(string ...$texts): array {
		return $this->wit->response(
			'DELETE',
			new ParsedEndpoint('/samples'),
			array_map(
				static function($text) {
					return ['text' => $text];
				},
				$texts
			)
		);
	}
}