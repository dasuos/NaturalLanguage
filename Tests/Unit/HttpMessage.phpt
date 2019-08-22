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

final class HttpMessage extends \Tester\TestCase {

	public function testReturningMeaningOfSentence()
	{
		Assert::same(
			[
				'method' => 'GET',
				'endpoint' => 'message?q=I%20want%20a%20blue%20ford',
				'body' => [],
			],
			(new NaturalLanguage\HttpMessage(
				new NaturalLanguage\FakeWit
			))->meaning('I want a blue ford')
		);
	}
}

(new HttpMessage())->run();
