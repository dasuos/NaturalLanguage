<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

interface Endpoint {

	public function reference(array $parameters = []): string;
}