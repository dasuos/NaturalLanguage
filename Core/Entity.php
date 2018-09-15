<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

interface Entity {

	public function properties(): array;
	public function edit(
		string $doc,
		array $lookups,
		Keyword ...$keywords
	): array;
}