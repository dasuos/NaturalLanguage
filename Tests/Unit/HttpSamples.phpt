<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage\Integration;

use Tester\Assert;
use Dasuos\NaturalLanguage;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 * @phpVersion > 7.1
 */

final class HttpSamples extends \Tester\TestCase {

	public function testValidatingSingleSampleAndPositioningEntity() {
		Assert::same(
			[
				'method' => 'POST',
				'endpoint' => 'samples',
				'body' => [
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
				],
			],
			(new NaturalLanguage\HttpSamples(
				new NaturalLanguage\FakeWit
			))->validate(
				new NaturalLanguage\PositioningSample(
					'I want to fly to London',
					['entity' => 'intent', 'value' => 'flight_request'],
					['entity' => 'wit$location', 'value' => 'London']
				)
			)
		);
	}

	public function testValidatingSingleSampleAndPositioningCompositeEntity() {
		Assert::same(
			[
				'method' => 'POST',
				'endpoint' => 'samples',
				'body' => [
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
				],
			],
			(new NaturalLanguage\HttpSamples(
				new NaturalLanguage\FakeWit
			))->validate(
				new NaturalLanguage\PositioningSample(
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
				)
			)
		);
	}

	public function testValidatingManySamplesAndCalculatingPositionOfEntities() {
		Assert::same(
			[
				'method' => 'POST',
				'endpoint' => 'samples',
				'body' => [
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
					[
						'text' => 'I want to fly to Amsterdam',
						'entities' => [
							['entity' => 'intent', 'value' => 'flight_request'],
							[
								'entity' => 'wit$location',
								'value' => 'Amsterdam',
								'start' => 17,
								'end' => 26,
							],
						],
					],
				],
			],
			(new NaturalLanguage\HttpSamples(
				new NaturalLanguage\FakeWit
			))->validate(
				new NaturalLanguage\PositioningSample(
					'I want to fly to London',
					['entity' => 'intent', 'value' => 'flight_request'],
					['entity' => 'wit$location', 'value' => 'London']
				),
				new NaturalLanguage\PositioningSample(
					'I want to fly to Amsterdam',
					['entity' => 'intent', 'value' => 'flight_request'],
					['entity' => 'wit$location', 'value' => 'Amsterdam']
				)
			)
		);
	}

	public function testReturningFilteredSamples() {
		Assert::same(
			[
				'method' => 'GET',
				'endpoint' => 'samples?limit=10&offset=0&entity_ids=wit%24location&entity_values=London',
				'body' => [],
			],
			(new NaturalLanguage\HttpSamples(
				new NaturalLanguage\FakeWit
			))->all(10, 0, ['wit$location'], ['London'])
		);
	}

	public function testReturningAllSamples() {
		Assert::same(
			[
				'method' => 'GET',
				'endpoint' => 'samples?limit=10&offset=5',
				'body' => [],
			],
			(new NaturalLanguage\HttpSamples(
				new NaturalLanguage\FakeWit
			))->all(10, 5)
		);
	}

	public function testReturningFilteredSamplesWithValuesWithoutEntities() {
		Assert::same(
			[
				'method' => 'GET',
				'endpoint' => 'samples?limit=10&offset=0',
				'body' => [],
			],
			(new NaturalLanguage\HttpSamples(
				new NaturalLanguage\FakeWit
			))->all(10, 0, [], ['London'])
		);
	}

	public function testDeletingSamples() {
		Assert::same(
			[
				'method' => 'DELETE',
				'endpoint' => 'samples',
				'body' => [
					['text' => 'I want to fly to London'],
					['text' => 'I want to fly to Prague'],
				],
			],
			(new NaturalLanguage\HttpSamples(
				new NaturalLanguage\FakeWit
			))->delete('I want to fly to London', 'I want to fly to Prague')
		);
	}
}

(new HttpSamples)->run();
