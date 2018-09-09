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

	public function testEditingEntityProperties() {
		Assert::same(
			[
				'method' => 'PUT',
				'endpoint' => 'entities/favorite_city',
				'body' => 	[
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
				[
					'doc' => 'Pizza types',
					'lookups' => ['keywords'],
					'values' => [
						[
							'value' => 'Prosciutto',
							'expressions' => ['Prosciutto', 'Ham'],
						],
					],
				]
			)
		);
	}
}

(new HttpEntity)->run();
