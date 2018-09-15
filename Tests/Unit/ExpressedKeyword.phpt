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

final class ExpressedKeyword extends \Tester\TestCase {

	public function testReturningStructureWithDifferentExpressions() {
		Assert::same(
			[
				'value' => 'Prosciutto',
				'expressions' => ['Prosciutto', 'Ham', 'Meat'],
			],
			(new NaturalLanguage\ExpressedKeyword(
				'Prosciutto',
				['Ham', 'Meat']
			))->structure()
		);
	}

	public function testReturningStructureWithRepeatingExpressions() {
		Assert::same(
			[
				'value' => 'Quattro Formaggi',
				'expressions' => ['Quattro Formaggi', 'Cheese'],
			],
			(new NaturalLanguage\ExpressedKeyword(
				'Quattro Formaggi',
				[
					'Quattro Formaggi',
					'Quattro Formaggi',
					'Cheese',
					'Cheese',
				]
			))->structure()
		);
	}

	public function testReturningStructureWithoutExpressions() {
		Assert::same(
			[
				'value' => 'Vegetarian',
				'expressions' => ['Vegetarian'],
			],
			(new NaturalLanguage\ExpressedKeyword('Vegetarian'))->structure()
		);
	}

	public function testReturningStructureWithExpressionsAndMetadata() {
		Assert::same(
			[
				'value' => 'Vegetarian',
				'expressions' => ['Vegetarian', 'Vegetable'],
				'metadata' => 'foobar123',
			],
			(new NaturalLanguage\ExpressedKeyword(
				'Vegetarian',
				['Vegetable'],
				'foobar123'
			))->structure()
		);
	}

	public function testReturningStructureWithoutExpressionsWithMetadata() {
		Assert::same(
			[
				'value' => 'Vegetarian',
				'expressions' => ['Vegetarian'],
				'metadata' => 'foobar123',
			],
			(new NaturalLanguage\ExpressedKeyword(
				'Vegetarian',
				[],
				'foobar123'
			))->structure()
		);
	}
}

(new ExpressedKeyword())->run();
