<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class Wit {

	private const URL = 'https://api.wit.ai/';
	private const VERSION = '20180902';

	private $token;

	public function __construct(string $token) {
		$this->token = $token;
	}

	public function response(
		string $method,
		string $endpoint,
		array $body = []
	): array {
		$curl = curl_init();
		try {
			curl_setopt_array(
				$curl,
				[
					CURLOPT_URL => $this->url($endpoint),
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_AUTOREFERER => true,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_CUSTOMREQUEST => strtoupper($method),
					CURLOPT_HTTPHEADER => [
						sprintf('Authorization: Bearer %s', $this->token),
						'Content-Type: application/json',
					],
				] + $this->fields($body)
			);
			return json_decode($this->execution($curl), true);
		} finally {
			curl_close($curl);
		}
	}

	private function url(string $endpoint): string {
		return sprintf(
			strpos($endpoint, '?') === false ? '%s?v=%s' : '%s&v=%s',
			self::URL . $endpoint,
			self::VERSION
		);
	}

	private function fields(array $body): array {
		return $body ? [CURLOPT_POSTFIELDS => json_encode($body)] : [];
	}

	/**
	 * @param resource $curl
	 * @return mixed
	 */
	private function execution($curl) {
		$response = curl_exec($curl);
		if ($response === false)
			throw new \UnexpectedValueException(
				sprintf(
					"Wit HTTP request error with response code %s: '%s'",
					curl_errno($curl),
					curl_error($curl)
				)
			);
		return $response;
	}
}