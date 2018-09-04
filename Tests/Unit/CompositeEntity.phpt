<?php
declare(strict_types = 1);

namespace Dasuos\NaturalLanguage\Unit;

use Tester\Assert;
use Dasuos\NaturalLanguage;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 * @phpVersion > 7.1
 */

final class CompositeEntity extends \Tester\TestCase {

	public function testReturningValidCompositeEntityStructure() {
		Assert::same(
			[
				'entity' => 'pizza',
				'value' => 'cheese pizza',
				'start' => 9,
				'end' => 21,
				'subentities' => [
					[
						'entity' => 'pizza_type',
						'value' => 'cheese',
						'start' => 9,
						'end' => 15,
					],
					[
						'entity' => 'food',
						'value' => 'pizza',
						'start' => 16,
						'end' => 21,
					]
				]
			],
			(new NaturalLanguage\CompositeEntity(
				new NaturalLanguage\FakeEntity(
					[
						'entity' => 'pizza',
						'value' => 'cheese pizza',
						'start' => 9,
						'end' => 21,
					]
				),
				new NaturalLanguage\FakeEntity(
					[
						'entity' => 'pizza_type',
						'value' => 'cheese',
						'start' => 9,
						'end' => 15,
					]
				),
				new NaturalLanguage\FakeEntity(
					[
						'entity' => 'food',
						'value' => 'pizza',
						'start' => 16,
						'end' => 21,
					]
				)
			))->structure('I want a cheese pizza')
		);
	}

	public function testReturningCompositeEntityStructureWithCalculatedSubentities() {
		Assert::same(
			[
				'entity' => 'pizza',
				'value' => 'cheese pizza',
				'start' => 9,
				'end' => 21,
				'subentities' => [
					[
						'entity' => 'pizza_type',
						'value' => 'cheese',
						'start' => 9,
						'end' => 15,
					],
					[
						'entity' => 'food',
						'value' => 'pizza',
						'start' => 16,
						'end' => 21,
					]
				]
			],
			(new NaturalLanguage\CompositeEntity(
				new NaturalLanguage\EntityInText('pizza', 'cheese pizza'),
				new NaturalLanguage\EntityInText('pizza_type', 'cheese'),
				new NaturalLanguage\EntityInText('food', 'pizza')
			))->structure('I want a cheese pizza')
		);
	}
}

(new CompositeEntity)->run();
