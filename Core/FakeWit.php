<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class FakeWit implements Wit {

	private $response;

	public function __construct(array $response = []) {
		$this->response = $response;
	}

	public function response(
		string $method,
		Endpoint $endpoint,
		array $body = []
	): array {
		return $this->response ?: [
			'method' => $method,
			'endpoint' => $endpoint->reference(),
			'body' => $body,
		];
	}
}