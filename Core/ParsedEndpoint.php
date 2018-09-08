<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class ParsedEndpoint implements Endpoint {

	private $resource;
	private $parameters;

	public function __construct(string $resource, array $parameters = []) {
		$this->resource = $resource;
		$this->parameters = $parameters;
	}

	public function reference(array $parameters = []): string {
		return $this->parse(
			ltrim($this->resource, '/'),
			http_build_query(
				$this->parameters + $parameters,
				'',
				'&',
				PHP_QUERY_RFC3986
			)
		);
	}

	private function parse(string $resource, string $query): string {
		return sprintf(
			'/%s',
			$query ? sprintf('%s?%s', $resource, $query) : $resource
		);
	}
}