<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests\Result;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Phpro\AgentRules\Result\CompleteResult;
use Phpro\AgentRules\Source\Source;

#[CoversClass(CompleteResult::class)]
final class CompleteResultTest extends TestCase
{
    #[Test]
    public function it_returns_complete_status(): void
    {
        $result = new CompleteResult('Task completed');

        static::assertSame('complete', $result->getStatus());
    }

    #[Test]
    public function it_includes_message_in_json_serialization(): void
    {
        $result = new CompleteResult('Task completed successfully');

        $serialized = $result->jsonSerialize();

        static::assertSame('complete', $serialized['status']);
        static::assertSame('Task completed successfully', $serialized['message']);
    }

    #[Test]
    public function it_returns_empty_source_map(): void
    {
        $result = new CompleteResult('Task completed');

        $sources = $result->sources()->sources();

        static::assertCount(0, $sources);
    }

    #[Test]
    public function it_can_add_sources_to_result(): void
    {
        $result = new CompleteResult('Task completed');
        $source = new Source('Documentation', 'https://docs.example.com', 'See the docs for more info');

        $result->sources()->add($source);

        $sources = $result->sources()->sources();
        static::assertCount(1, $sources);
        static::assertSame('Documentation', $sources[0]->name);
        static::assertSame('https://docs.example.com', $sources[0]->reference);
        static::assertSame('See the docs for more info', $sources[0]->content);
    }
}
