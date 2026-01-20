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
}
