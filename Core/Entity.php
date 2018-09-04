<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

interface Entity {

	public function structure(string $text = ''): array;
}