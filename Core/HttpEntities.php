<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class HttpEntities implements Entities {

	private $wit;

	public function __construct(Wit $wit) {
		$this->wit = $wit;
	}

	public function create(string $id, string $doc = ''): array {
		return $this->wit->response(
			'POST',
			new ParsedEndpoint('/entities'),
			array_filter(['id' => $id, 'doc' => $doc], 'strlen')
		);
	}

	public function all(): array {
		return $this->wit->response('GET', new ParsedEndpoint('/entities'));
	}

	public function delete(string $entity, string $role = ''): array {
		return $this->wit->response(
			'DELETE',
			new ParsedEndpoint(
				sprintf(
					'/entities/%s',
					$role ? sprintf('%s:%s', $entity, $role) : $entity
				)
			)
		);
	}
}