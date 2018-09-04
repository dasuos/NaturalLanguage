<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage\Integration;

use Tester\Assert;
use Dasuos\NaturalLanguage\TestCase;
use Dasuos\NaturalLanguage\Misc;
use Dasuos\NaturalLanguage;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 * @phpVersion > 7.1
 */

final class ValidatedSamples extends \Tester\TestCase {

	use TestCase\AccessToken;

	public function testValidatingGettingAndDeletingSample() {
		$samples = new NaturalLanguage\ValidatedSamples(
			new NaturalLanguage\Wit($this->token)
		);
		$value = (new Misc\RandomValue)->output();
		$text = sprintf('I want to fly to %s', $value);
		Assert::noError(
			function() use ($samples, $text, $value) {
				$samples->validate(
					$text,
					new NaturalLanguage\FakeEntity(
						[
							'entity' => 'intent',
							'value' => 'flight_request',
						]
					),
					new NaturalLanguage\FakeEntity(
						[
							'entity' => 'wit$location',
							'start' => 17,
							'end' => strlen($value) + 17,
							'value' => $value,
						]
					)
				);
			}
		);
		sleep(2);
		Assert::same(
			[
				[
					'text' => $text,
					'entities' => [
						[
							'entity' => 'intent',
							'value' => 'flight_request'
						],
						[
							'entity' => 'wit$location',
							'value' => $value,
							'start' => 17,
							'end' => strlen($value) + 17,
						],
					],
				],
			],
			(new NaturalLanguage\ValidatedSamples(
				new NaturalLanguage\Wit($this->token)
			))->all(1, 0, ['wit$location'], [$value])
		);
		Assert::noError(
			function() use ($samples, $text) {
				$samples->delete([$text]);
			}
		);
	}

	public function testValidatingAndDeletingSampleWithEntityInText() {
		$text = 'I want to fly to Birmingham';
		$samples = new NaturalLanguage\ValidatedSamples(
			new NaturalLanguage\Wit($this->token)
		);
		Assert::noError(
			function() use ($samples, $text) {
				$samples->validate(
					$text,
					new NaturalLanguage\EntityInText(
						'wit$location',
						'Birmingham'
					)
				);
			}
		);
		sleep(2);
		Assert::same(
			[
				[
					'text' => $text,
					'entities' => [
						[
							'entity' => 'wit$location',
							'value' => 'Birmingham',
							'start' => 17,
							'end' => 27,
						],
					],
				],
			],
			(new NaturalLanguage\ValidatedSamples(
				new NaturalLanguage\Wit($this->token)
			))->all(1, 0, ['wit$location'], ['Birmingham'])
		);
		Assert::noError(
			function() use ($samples, $text) {
				$samples->delete([$text]);
			}
		);
	}
}

(new ValidatedSamples)->run();
