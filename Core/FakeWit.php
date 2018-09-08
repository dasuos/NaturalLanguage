<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class FakeWit implements Wit {

	public function response(
		string $method,
		Endpoint $endpoint,
		array $body = []
	): array {
		return [
			'method' => $method,
			'endpoint' => $endpoint->reference(),
			'body' => $body,
		];
	}
}