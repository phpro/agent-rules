<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests\Result;

use PHPUnit\Framework\TestCase;
use Phpro\AgentRules\Result\ResultInterface;
use Phpro\AgentRules\Source\Source;

abstract class AbstractResultTestCase extends TestCase
{
    abstract protected function createResult(): ResultInterface;

    abstract protected function expectedStatus(): string;

    public function test_it_returns_empty_source_map(): void
    {
        $result = $this->createResult();

        $sources = $result->sources()->sources();

        static::assertCount(0, $sources);
    }

    public function test_it_can_add_sources_to_result(): void
    {
        $result = $this->createResult();
        $source = new Source('Documentation', 'https://docs.example.com', 'See the docs for more info');

        $result->sources()->add($source);

        $sources = $result->sources()->sources();
        static::assertCount(1, $sources);
        static::assertSame('Documentation', $sources[0]->name);
        static::assertSame('https://docs.example.com', $sources[0]->reference);
        static::assertSame('See the docs for more info', $sources[0]->content);
    }

    public function test_it_includes_sources_in_json_serialization(): void
    {
        $result = $this->createResult();
        $result->sources()->add(
            new Source('Docs', 'https://docs.example.com', 'See docs'),
            new Source('Help', 'https://help.example.com', 'Need help')
        );

        $json = json_encode($result, JSON_THROW_ON_ERROR);
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        static::assertArrayHasKey('sources', $data);
        static::assertCount(2, $data['sources']);
        static::assertSame('Docs', $data['sources'][0]['name']);
        static::assertSame('https://docs.example.com', $data['sources'][0]['reference']);
        static::assertSame('See docs', $data['sources'][0]['content']);
        static::assertSame('Help', $data['sources'][1]['name']);
    }

    public function test_it_can_add_multiple_sources_at_once(): void
    {
        $result = $this->createResult();

        $returnValue = $result->addSources(
            new Source('API', 'https://api.example.com', 'API docs'),
            new Source('Guide', 'https://guide.example.com', 'User guide'),
            new Source('FAQ', 'https://faq.example.com', 'Frequently asked')
        );

        static::assertSame($result, $returnValue);
        static::assertCount(3, $result->sources()->sources());
    }

    public function test_it_includes_status_in_json_serialization(): void
    {
        $result = $this->createResult();

        $serialized = $result->jsonSerialize();

        static::assertSame($this->expectedStatus(), $serialized['status']);
    }
}
