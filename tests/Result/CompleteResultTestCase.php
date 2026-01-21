<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests\Result;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Phpro\AgentRules\Result\CompleteResult;
use Phpro\AgentRules\Result\ResultInterface;

#[CoversClass(CompleteResult::class)]
final class CompleteResultTestCase extends AbstractResultTestCase
{
    #[\Override]
    protected function createResult(): ResultInterface
    {
        return new CompleteResult('Task completed');
    }

    #[\Override]
    protected function expectedStatus(): string
    {
        return 'complete';
    }

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
}
