<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests\Result;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Phpro\AgentRules\Result\IncompleteResult;
use Phpro\AgentRules\Result\ResultInterface;

#[CoversClass(IncompleteResult::class)]
final class IncompleteResultTestCase extends AbstractResultTestCase
{
    #[\Override]
    protected function createResult(): ResultInterface
    {
        return new IncompleteResult('email', 'Email is required');
    }

    #[\Override]
    protected function expectedStatus(): string
    {
        return 'incomplete';
    }

    #[Test]
    public function it_returns_incomplete_status(): void
    {
        $result = new IncompleteResult('email', 'Email is required');

        static::assertSame('incomplete', $result->getStatus());
    }

    #[Test]
    public function it_includes_missing_field_and_message_in_json_serialization(): void
    {
        $result = new IncompleteResult('email', 'Please provide your email address');

        $serialized = $result->jsonSerialize();

        static::assertSame('incomplete', $serialized['status']);
        static::assertSame('email', $serialized['missing']);
        static::assertSame('Please provide your email address', $serialized['message']);
    }
}
