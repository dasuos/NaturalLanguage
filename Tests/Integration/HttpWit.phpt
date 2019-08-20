<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage\Integration;

use Tester\Assert;
use Dasuos\NaturalLanguage;
use Dasuos\NaturalLanguage\TestCase;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 * @phpVersion > 7.1
 */

final class HttpWit extends \Tester\TestCase {

	use TestCase\AccessToken;

	public function testSendingPostRequest() {
		Assert::same(
			['sent' => true, 'n' => 1],
			(new NaturalLanguage\HttpSamples(
				new NaturalLanguage\HttpWit($this->token)
			))->validate(
				new NaturalLanguage\PositioningSample(
					'I want to fly to Prague',
					['entity' => 'wit$location', 'value' => 'Prague']
				)
			)
		);
	}

	public function testSendingGetRequest() {
		$response = (new NaturalLanguage\HttpMessage(
			new NaturalLanguage\HttpWit($this->token)
		))->meaning('I want a blue ford');
		Assert::same(
			['_text' => 'I want a blue ford'],
			['_text' => $response['_text']]
		);
		Assert::true(isset($response['entities']));
	}

	public function testSendingDeleteRequest() {
		Assert::same(
			['sent' => true, 'n' => 1],
			(new NaturalLanguage\HttpSamples(
				new NaturalLanguage\HttpWit($this->token)
			))->delete('I want to fly to Prague')
		);
	}

	public function testThrowingOnPutRequestOnPredefinedEntity() {
		Assert::exception(
			function() {
				(new NaturalLanguage\HttpEntity(
					new NaturalLanguage\HttpWit($this->token),
					'wit$location'
				))->edit(
					'These are locations worth going to',
					['keywords'],
					new NaturalLanguage\ExpressedKeyword('Prague')
				);
			},
			\UnexpectedValueException::class
		);
	}
}

(new HttpWit)->run();
