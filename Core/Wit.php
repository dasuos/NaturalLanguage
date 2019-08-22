<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

interface Wit {

	public function response(
		string $method,
		Endpoint $endpoint,
		array $body = []
	): array;
}
