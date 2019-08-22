<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage;

final class HttpWit implements Wit {

	private const URL = 'https://api.wit.ai/';
	private const VERSION = '20180902';

	private $token;

	public function __construct(string $token) {
		$this->token = $token;
	}

	public function response(
		string $method,
		Endpoint $endpoint,
		array $body = []
	): array {
		$curl = curl_init();
		try {
			curl_setopt_array(
				$curl,
				[
					CURLOPT_URL => $this->url(self::URL, $endpoint),
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_AUTOREFERER => false,
					CURLOPT_FOLLOWLOCATION => false,
					CURLOPT_MAXREDIRS => 0,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_CUSTOMREQUEST => strtoupper($method),
					CURLOPT_HTTPHEADER => [
						sprintf('Authorization: Bearer %s', $this->token),
						'Content-Type: application/json',
					],
				] + $this->fields($body)
			);
			return $this->decode($this->execution($curl));
		} finally {
			curl_close($curl);
		}
	}

	private function url(string $base, Endpoint $endpoint): string {
		return sprintf(
			'%s/%s',
			rtrim($base, '/'),
			$endpoint->reference(['v' => self::VERSION])
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
					"Wit request error with status code %s: '%s'",
					curl_errno($curl),
					curl_error($curl)
				)
			);
		return $response;
	}

	private function decode(string $json): array {
		$response = json_decode($json, true);
		if (isset($response['error']))
			throw new \UnexpectedValueException(
				sprintf("Wit response error: '%s'", $response['error'])
			);
		return $response;
	}
}
