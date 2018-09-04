<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

interface Entity {

	public const NO_SOURCE = '';

	public function structure(string $text = self::NO_SOURCE): array;
}