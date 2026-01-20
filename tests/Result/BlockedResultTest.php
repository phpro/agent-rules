<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests\Result;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Phpro\AgentRules\Result\BlockedResult;
use Symfony\AI\Agent\Toolbox\Source\Source;

#[CoversClass(BlockedResult::class)]
final class BlockedResultTest extends TestCase
{
    #[Test]
    public function it_returns_blocked_status(): void
    {
        $result = new BlockedResult('insufficient_permissions', 'Access denied');

        static::assertSame('blocked', $result->getStatus());
    }

    #[Test]
    public function it_includes_reason_and_message_in_json_serialization(): void
    {
        $result = new BlockedResult('insufficient_permissions', 'You do not have permission to access this resource');

        $serialized = $result->jsonSerialize();

        static::assertSame('blocked', $serialized['status']);
        static::assertSame('insufficient_permissions', $serialized['reason']);
        static::assertSame('You do not have permission to access this resource', $serialized['message']);
    }

    #[Test]
    public function it_returns_empty_source_map(): void
    {
        $result = new BlockedResult('test_reason', 'Test message');

        $sources = $result->sources()->getSources();

        static::assertCount(0, $sources);
    }

    #[Test]
    public function it_can_add_sources_to_result(): void
    {
        $result = new BlockedResult('test_reason', 'Test message');
        $source = new Source('test-source', 'ref', 'content');

        $result->addSources($source);

        $sources = $result->sources()->getSources();
        static::assertCount(1, $sources);
    }
}
