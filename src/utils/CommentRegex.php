<?php

declare(strict_types=1);

namespace gastier\PhpstanTodoComment\utils;

final class CommentRegex
{
    /** @var array{name: string, line: int} $tag */
    private array $tag;

    private string $comment;

    /**
     * @param array<array-key, string|int> $match
     */
    public function addTag(array $match): void
    {
        $this->tag = [
            'name' => trim((string) $match[0]),
            'line' => (int) $match[1],
        ];
    }

    /**
     * @param array<array-key, string|int> $comment
     */
    public function addComment(array $comment): void
    {
        $this->comment = trim((string) $comment[0]);
    }

    /** @return array{name: string, line: int} */
    public function getTag()
    {
        return $this->tag;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
}