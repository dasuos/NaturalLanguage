<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class FakeWit implements Wit {

	public function response(
		string $method,
		string $endpoint,
		array $body = [],
		array $query = []
	): array {
		return [$method, $endpoint, $body, $query];
	}
}