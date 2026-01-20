<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests\Result;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Phpro\AgentRules\Result\IncompleteResult;
use Phpro\AgentRules\Source\Source;

#[CoversClass(IncompleteResult::class)]
final class IncompleteResultTest extends TestCase
{
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

    #[Test]
    public function it_returns_empty_source_map(): void
    {
        $result = new IncompleteResult('email', 'Email is required');

        $sources = $result->sources()->sources();

        static::assertCount(0, $sources);
    }

    #[Test]
    public function it_can_add_sources_to_result(): void
    {
        $result = new IncompleteResult('email', 'Email is required');
        $source = new Source('Form Requirements', 'https://forms.example.com', 'Email field must be filled');

        $result->sources()->add($source);

        $sources = $result->sources()->sources();
        static::assertCount(1, $sources);
        static::assertSame('Form Requirements', $sources[0]->name);
        static::assertSame('https://forms.example.com', $sources[0]->reference);
        static::assertSame('Email field must be filled', $sources[0]->content);
    }
}
