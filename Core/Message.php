<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

interface Message {

	public function meaning(string $sentence, array $arguments = []): array;
}
