<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class HttpEntity implements Entity {

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

	public function edit(array $properties): array {
		return $this->wit->response(
			'PUT',
			new ParsedEndpoint(sprintf('/entities/%s', $this->id)),
			$properties
		);
	}
}