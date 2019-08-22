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

final class HttpEntity extends \Tester\TestCase {

	public function testReturningEntityProperties() {
		Assert::same(
			[
				'method' => 'GET',
				'endpoint' => 'entities/favorite_city',
				'body' => [],
			],
			(new NaturalLanguage\HttpEntity(
				new NaturalLanguage\FakeWit,
				'favorite_city'
			))->properties()
		);
	}

	public function testEditingEntityPropertiesWithSingleKeyword() {
		Assert::same(
			[
				'method' => 'PUT',
				'endpoint' => 'entities/favorite_city',
				'body' => [
					'doc' => 'Pizza types',
					'lookups' => ['keywords'],
					'values' => [
						[
							'value' => 'Prosciutto',
							'expressions' => ['Prosciutto', 'Ham']
						],
					],
				],
			],
			(new NaturalLanguage\HttpEntity(
				new NaturalLanguage\FakeWit,
				'favorite_city'
			))->edit(
				'Pizza types',
				['keywords'],
				new NaturalLanguage\ExpressedKeyword('Prosciutto', ['Ham'])
			)
		);
	}

	public function testEditingEntityPropertiesWithMultipleKeywords() {
		Assert::same(
			[
				'method' => 'PUT',
				'endpoint' => 'entities/favorite_city',
				'body' => [
					'doc' => 'Pizza types',
					'lookups' => ['keywords'],
					'values' => [
						[
							'value' => 'Prosciutto',
							'expressions' => ['Prosciutto', 'Ham'],
						],
						[
							'value' => 'Quattro Formaggi',
							'expressions' => ['Quattro Formaggi', 'Cheese'],
							'metadata' => 'food_125',
						],
					],
				],
			],
			(new NaturalLanguage\HttpEntity(
				new NaturalLanguage\FakeWit,
				'favorite_city'
			))->edit(
				'Pizza types',
				['keywords'],
				new NaturalLanguage\ExpressedKeyword('Prosciutto', ['Ham']),
				new NaturalLanguage\ExpressedKeyword(
					'Quattro Formaggi',
					['Cheese'],
					'food_125'
				)
			)
		);
	}

	public function testEditingEntityPropertiesWithoutKeywords() {
		Assert::same(
			[
				'method' => 'PUT',
				'endpoint' => 'entities/favorite_city',
				'body' => [
					'doc' => 'Pizza types',
					'lookups' => ['keywords'],
				],
			],
			(new NaturalLanguage\HttpEntity(
				new NaturalLanguage\FakeWit,
				'favorite_city'
			))->edit(
				'Pizza types',
				['keywords']
			)
		);
	}

	public function testThrowingOnInvalidLookupStrategy() {
		Assert::exception(
			function() {
				(new NaturalLanguage\HttpEntity(
					new NaturalLanguage\FakeWit,
					'favorite_city'
				))->edit(
					'Pizza types',
					['invalid'],
					new NaturalLanguage\ExpressedKeyword('Prosciutto', ['Ham'])
				);
			},
			\UnexpectedValueException::class,
			"Invalid entity lookup strategy 'invalid'"
		);
	}
}

(new HttpEntity())->run();
