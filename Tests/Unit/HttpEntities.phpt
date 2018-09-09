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

final class HttpEntities extends \Tester\TestCase {

	public function testCreatingNewEntityWithDoc() {
		Assert::same(
			[
				'method' => 'POST',
				'endpoint' => 'entities',
				'body' => [
					'id' => 'favorite_city',
					'doc' => 'A city that I like',
				],
			],
			(new NaturalLanguage\HttpEntities(
				new NaturalLanguage\FakeWit
			))->create('favorite_city', 'A city that I like')
		);
	}

	public function testCreatingNewEntityWithoutDoc() {
		Assert::same(
			[
				'method' => 'POST',
				'endpoint' => 'entities',
				'body' => ['id' => 'favorite_city'],
			],
			(new NaturalLanguage\HttpEntities(
				new NaturalLanguage\FakeWit
			))->create('favorite_city')
		);
	}

	public function testReturningAllEntities() {
		Assert::same(
			['method' => 'GET', 'endpoint' => 'entities', 'body' => []],
			(new NaturalLanguage\HttpEntities(
				new NaturalLanguage\FakeWit
			))->all()
		);
	}

	public function testDeletingEntityWithoutRole() {
		Assert::same(
			[
				'method' => 'DELETE',
				'endpoint' => 'entities/favorite_city',
				'body' => [],
			],
			(new NaturalLanguage\HttpEntities(
				new NaturalLanguage\FakeWit
			))->delete('favorite_city')
		);
	}

	public function testDeletingEntityWithRole() {
		Assert::same(
			[
				'method' => 'DELETE',
				'endpoint' => 'entities/flight:destination',
				'body' => [],
			],
			(new NaturalLanguage\HttpEntities(
				new NaturalLanguage\FakeWit
			))->delete('flight', 'destination')
		);
	}
}

(new HttpEntities)->run();
