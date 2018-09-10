<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class HttpMessage implements Message {

	private $wit;

	public function __construct(Wit $wit) {
		$this->wit = $wit;
	}

	public function meaning(string $sentence, array $arguments = []): array {
		return $this->wit->response(
			'GET',
			new ParsedEndpoint(
				'/message',
				['q' => $sentence]
			),
			$arguments
		);
	}
}