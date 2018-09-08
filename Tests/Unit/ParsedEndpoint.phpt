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

final class ParsedEndpoint extends \Tester\TestCase {

	public function testReturningResourceWithoutQuery() {
		Assert::same(
			'/resource',
			(new NaturalLanguage\ParsedEndpoint(
				'/resource'
			))->reference()
		);
	}

	public function testReturningResourceWithMultipleParametersInQuery() {
		Assert::same(
			'/resource?foo=bar&bar=foo',
			(new NaturalLanguage\ParsedEndpoint(
				'/resource',
				['foo' => 'bar', 'bar' => 'foo']
			))->reference()
		);
	}

	public function testReturningResourceWithSingleParameterInQuery() {
		Assert::same(
			'/resource?foo=bar',
			(new NaturalLanguage\ParsedEndpoint(
				'/resource',
				['foo' => 'bar']
			))->reference()
		);
	}
}

(new ParsedEndpoint)->run();
