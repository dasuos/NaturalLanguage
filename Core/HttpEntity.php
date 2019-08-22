<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class HttpEntity implements Entity {

	private const LOOKUPS = ['free-text', 'keywords'];

	private $wit;
	private $id;

	public function __construct(Wit $wit, string $id) {
		$this->wit = $wit;
		$this->id = $id;
	}

	public function properties(): array {
		return $this->wit->response(
			'GET',
			new ParsedEndpoint(sprintf('/entities/%s', $this->id))
		);
	}

	public function edit(
		string $doc,
		array $lookups,
		Keyword ...$keywords
	): array {
		return $this->wit->response(
			'PUT',
			new ParsedEndpoint(sprintf('/entities/%s', $this->id)),
			[
				'doc' => $doc,
				'lookups' => array_map(
					function($strategy) {
						return $this->lookup($strategy);
					},
					$lookups
				),
			] + $this->values($keywords)
		);
	}

	private function values(array $keywords): array {
		return $keywords ? [
			'values' => array_map(
				static function(Keyword $keyword) {
					return $keyword->structure();
				},
				$keywords
			),
		] : [];
	}

	private function lookup(string $strategy): string {
		if (in_array($strategy, self::LOOKUPS, true))
			return $strategy;
		throw new \UnexpectedValueException(
			sprintf("Invalid entity lookup strategy '%s'", $strategy)
		);
	}
}
