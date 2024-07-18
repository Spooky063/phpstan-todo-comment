<?php

declare(strict_types=1);

namespace gastier\PhpstanTodoComment;

use gastier\PhpstanTodoComment\utils\CommentMatcher;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Node>
 */
final class TodoCommentRule implements Rule
{

    private const PATTERN = <<<'REGEXP'
    {
        (?P<tag>TODO|FIXME|OPTIMIZE)
        (?P<comment>(?:(?!\*+/).)*)
    }ix
    REGEXP;

    public function getNodeType(): string
    {
        return Node::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];

        $comments = CommentMatcher::matchComments($node, self::PATTERN);

        foreach ($comments as $comment => $match) {
            $todoTag = $match->getTag();
            $todoText = $match->getComment();

            $newLines = substr_count($comment->getText(), "\n", 0, $todoTag['line']);
            $messageLine = $comment->getStartLine() + $newLines;

            $errorMessage = "{$todoTag['name']} comment was found into your codebase";
            if (!empty($todoText)) {
                $errorMessage .= " with message: " . rtrim($todoText, '.');
            }
            $errorMessage .= ".";

            $errBuilder = RuleErrorBuilder::message($errorMessage)
                ->line($messageLine)
                ->identifier('Comment.Todo');

            $errors[] = $errBuilder->build();
        }

        return $errors;
    }

}