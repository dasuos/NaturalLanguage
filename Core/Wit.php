<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

interface Wit {

	public function response(
		string $method,
		string $endpoint,
		array $body = [],
		array $query = []
	): array;
}