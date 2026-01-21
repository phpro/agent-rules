<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests\Result;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Phpro\AgentRules\Result\BlockedResult;
use Phpro\AgentRules\Result\ResultInterface;

#[CoversClass(BlockedResult::class)]
final class BlockedResultTestCase extends AbstractResultTestCase
{
    #[\Override]
    protected function createResult(): ResultInterface
    {
        return new BlockedResult('test_reason', 'Test message');
    }

    #[\Override]
    protected function expectedStatus(): string
    {
        return 'blocked';
    }

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
}
