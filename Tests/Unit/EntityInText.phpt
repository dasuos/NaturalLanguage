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

final class EntityInText extends \Tester\TestCase {

	public function testReturningStructureWithCalculatedPositionInText() {
		Assert::same(
			[
				'entity' => 'pizza',
				'value' => 'cheese pizza',
				'start' => 9,
				'end' => 21,
			],
			(new NaturalLanguage\EntityInText(
				'pizza',
				'cheese pizza'
			))->structure('I want a cheese pizza')
		);
	}

	public function testReturningStructureWithGivenPositionInText() {
		Assert::same(
			[
				'entity' => 'pizza_type',
				'value' => 'spinach',
				'start' => 9,
				'end' => 16,
			],
			(new NaturalLanguage\EntityInText(
				'pizza_type',
				'spinach',
				9,
				16
			))->structure('I want a spinach pizza and spinach spaghetti')
		);
	}

	public function testThrowingOnMoreOccurrencesInTextWithoutGivenPosition() {
		Assert::exception(
			static function() {
				(new NaturalLanguage\EntityInText(
					'pizza_type',
					'spinach'
				))->structure('I want a spinach pizza and spinach spaghetti');
			},
			\UnexpectedValueException::class,
			"Entity 'spinach' must occur exactly once in the text 'I want a spinach pizza and spinach spaghetti'"
		);
	}

	public function testThrowingOnNoOccurrencesInTextWithoutGivenPosition() {
		Assert::exception(
			static function() {
				(new NaturalLanguage\EntityInText(
					'pizza_type',
					'spinach'
				))->structure('I want a cheese pizza and nothing else');
			},
			\UnexpectedValueException::class,
			"Entity 'spinach' must occur exactly once in the text 'I want a cheese pizza and nothing else'"
		);
	}
}

(new EntityInText)->run();
