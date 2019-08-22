<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class ExpressedKeyword implements Keyword {

	private $value;
	private $expressions;
	private $metadata;

	public function __construct(
		string $value,
		array $expressions = [],
		string $metadata = ''
	) {
		$this->value = $value;
		$this->expressions = $expressions;
		$this->metadata = $metadata;
	}

	public function structure(): array {
		return [
			'value' => $this->value,
			'expressions' => array_values(
				array_unique(
					array_merge([$this->value], $this->expressions)
				)
			),
		] + array_filter(['metadata' => $this->metadata]);
	}
}
