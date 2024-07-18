<?php

declare(strict_types=1);

namespace gastier\PhpstanTodoComment\utils;

use PhpParser\Comment;
use PhpParser\Node;
use PHPStan\Node\VirtualNode;
use RuntimeException;

final class CommentMatcher
{
    /**
     * @return iterable<Comment, CommentRegex>
     */
    public static function matchComments(Node $node, string $pattern): iterable
    {
        if (
            $node instanceof VirtualNode
            || $node instanceof Node\Expr
        ) {
            return [];
        }

        foreach ($node->getComments() as $comment) {
            $text = $comment->getText();

            if (
                false === preg_match($pattern, $text, $matches, PREG_OFFSET_CAPTURE)
                || 0 === count($matches)
            ) {
                if (PREG_NO_ERROR !== preg_last_error()) {
                    throw new RuntimeException('Error in PCRE: '. preg_last_error_msg());
                }

                continue;
            }

            $commentRegex = new CommentRegex();
            $commentRegex->addTag($matches['tag'] ?? []);
            $commentRegex->addComment($matches['comment'] ?? []);

            yield $comment => $commentRegex;
        }
    }
}