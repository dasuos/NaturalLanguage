<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

interface Entities {

	public function create(string $id, string $doc = ''): array;
	public function all(): array;
	public function delete(string $entity, string $role = ''): array;
}