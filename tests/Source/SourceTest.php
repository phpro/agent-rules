<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests\Source;

use Phpro\AgentRules\Source\Source;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Source::class)]
final class SourceTest extends TestCase
{
    #[Test]
    public function it_can_be_constructed(): void
    {
        $source = new Source(
            name: 'Documentation',
            reference: 'https://docs.example.com',
            content: 'This is the content',
        );

        self::assertSame('Documentation', $source->name);
        self::assertSame('https://docs.example.com', $source->reference);
        self::assertSame('This is the content', $source->content);
    }

    #[Test]
    public function it_can_be_json_serialized(): void
    {
        $source = new Source(
            name: 'API Reference',
            reference: 'https://api.example.com',
            content: 'API documentation content',
        );

        $json = json_encode($source, JSON_THROW_ON_ERROR);

        self::assertJsonStringEqualsJsonString(
            '{"name":"API Reference","reference":"https://api.example.com","content":"API documentation content"}',
            $json
        );
    }
}
