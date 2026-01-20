<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests\Result;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Phpro\AgentRules\Result\ErrorResult;
use Symfony\AI\Agent\Toolbox\Source\Source;

#[CoversClass(ErrorResult::class)]
final class ErrorResultTest extends TestCase
{
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

    #[Test]
    public function it_returns_empty_source_map(): void
    {
        $result = new ErrorResult('Error occurred', 'Fix the issue');

        $sources = $result->sources()->getSources();

        static::assertCount(0, $sources);
    }

    #[Test]
    public function it_can_add_sources_to_result(): void
    {
        $result = new ErrorResult('Error occurred', 'Fix the issue');
        $source = new Source('test-source', 'ref', 'content');

        $result->addSources($source);

        $sources = $result->sources()->getSources();
        static::assertCount(1, $sources);
    }
}
