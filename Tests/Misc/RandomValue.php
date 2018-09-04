<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage\Misc;

final class RandomValue {

	private $prefix;

	public function __construct(string $prefix = '') {
		$this->prefix = $prefix;
	}

	public function output(): string {
		return md5(uniqid($this->prefix, true));
	}
}