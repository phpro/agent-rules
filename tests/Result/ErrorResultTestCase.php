<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests\Result;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Phpro\AgentRules\Result\ErrorResult;
use Phpro\AgentRules\Result\ResultInterface;

#[CoversClass(ErrorResult::class)]
final class ErrorResultTestCase extends AbstractResultTestCase
{
    #[\Override]
    protected function createResult(): ResultInterface
    {
        return new ErrorResult('Error occurred', 'Fix the issue');
    }

    #[\Override]
    protected function expectedStatus(): string
    {
        return 'error';
    }

    #[Test]
    public function it_returns_error_status(): void
    {
        $result = new ErrorResult('Something went wrong', 'Please try again');

        static::assertSame('error', $result->getStatus());
    }

    #[Test]
    public function it_includes_message_and_resolution_in_json_serialization(): void
    {
        $result = new ErrorResult(
            'Database connection failed',
            'Check your database credentials and try again'
        );

        $serialized = $result->jsonSerialize();

        static::assertSame('error', $serialized['status']);
        static::assertSame('Database connection failed', $serialized['message']);
        static::assertSame('Check your database credentials and try again', $serialized['resolution']);
    }
}
