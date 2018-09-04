<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

interface Samples {

	public function validate(string $text, Entity ...$entity): void;
	public function all(
		int $limit,
		int $offset = 0,
		array $ids = [],
		array $values = []
	): array;
	public function delete(array $texts): void;
}