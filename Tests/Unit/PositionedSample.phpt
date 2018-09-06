<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage\Integration;

use Tester\Assert;
use Dasuos\NaturalLanguage\TestCase;
use Dasuos\NaturalLanguage;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 * @phpVersion > 7.1
 */

final class PositionedSample extends \Tester\TestCase {

	use TestCase\AccessToken;

	public function testReturningSampleStructureWithIntentOnly() {
		Assert::same(
			[
				'text' => 'I want to fly to London',
				'entities' => [
					['entity' => 'intent', 'value' => 'flight_request'],
				],
			],
			(new NaturalLanguage\PositionedSample(
				'I want to fly to London',
				['entity' => 'intent', 'value' => 'flight_request']
			))->structure()

		);
	}

	public function testReturningSampleStructureAndPositioningEntity() {
		Assert::same(
			[
				'text' => 'I want to fly to London',
				'entities' => [
					['entity' => 'intent', 'value' => 'flight_request'],
					[
						'entity' => 'wit$location',
						'value' => 'London',
						'start' => 17,
						'end' => 23,
					],
				],
			],
			(new NaturalLanguage\PositionedSample(
				'I want to fly to London',
				['entity' => 'intent', 'value' => 'flight_request'],
				['entity' => 'wit$location', 'value' => 'London']
			))->structure()

		);
	}

	public function testReturningSampleStructureAndPositioningCompositeEntity() {
		Assert::same(
			[
				'text' => 'I want a blue ford',
				'entities' => [
					[
						'entity' => 'car',
						'value' => 'blue ford',
						'start' => 9,
						'end' => 18,
						[
							'entity' => 'color',
							'value' => 'blue',
							'start' => 0,
							'end' => 4,
						],
						[
							'entity' => 'model',
							'value' => 'ford',
							'start' => 5,
							'end' => 9,
						],
					],
				],
			],
			(new NaturalLanguage\PositionedSample(
				'I want a blue ford',
				[
					'entity' => 'car',
					'value' => 'blue ford',
					'subentities' => [
						[
							'entity' => 'color',
							'value' => 'blue',
						],
						[
							'entity' => 'model',
							'value' => 'ford',
						],
					],
				]
			))->structure()
		);
	}
}

(new PositionedSample)->run();
