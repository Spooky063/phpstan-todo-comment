<?php

declare(strict_types=1);

namespace gastier\PhpstanTodoComment\Tests;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\TestDox;
use gastier\PhpstanTodoComment\TodoCommentRule;

/**
 * @extends RuleTestCase<TodoCommentRule>
 * @internal
 */
final class TodoCommentRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new TodoCommentRule();
    }

    #[TestDox('It catch the todo comments')]
    public function testRuleTodo(): void
    {
        $this->analyse([__DIR__ . '/data/example.php'], [
            [
                'TODO comment was found into your codebase with message: Rewrite this function.',
                3,
            ],
            [
                'TODO comment was found into your codebase with message: implement method.',
                8,
            ],
            [
                'TODO comment was found into your codebase with message: implement method.',
                14,
            ],
            [
                'Todo comment was found into your codebase.',
                20,
            ],
            [
                'FIXME comment was found into your codebase.',
                23,
            ],
            [
                'OPTIMIZE comment was found into your codebase.',
                24,
            ],
            [
                'FIXME comment was found into your codebase with message: An issue was created for this part.',
                27,
            ],
            [
                'OPTIMIZE comment was found into your codebase with message: Update the deprecated way to do.',
                31,
            ],
        ]);
    }
}