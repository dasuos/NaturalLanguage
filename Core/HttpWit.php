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
		string $endpoint,
		array $body = [],
		array $query = []
	): array {
		$curl = curl_init();
		try {
			curl_setopt_array(
				$curl,
				[
					CURLOPT_URL => $this->url($endpoint, $query),
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
			return $this->decode($this->execution($curl));
		} finally {
			curl_close($curl);
		}
	}

	private function url(string $endpoint, array $data): string {
		return sprintf(
			'%s?%s',
			rtrim(self::URL, '/') . $endpoint,
			$this->query($data)
		);
	}

	private function query(array $data): string {
		return http_build_query(
			['v' => self::VERSION] + $data,
			'',
			'&',
			PHP_QUERY_RFC3986
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