<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests\Source;

use Phpro\AgentRules\Source\Source;
use Phpro\AgentRules\Source\SourceMap;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SourceMap::class)]
final class SourceMapTest extends TestCase
{
    #[Test]
    public function it_can_be_constructed_empty(): void
    {
        $map = new SourceMap();

        self::assertSame([], $map->sources());
    }

    #[Test]
    public function it_can_be_constructed_with_sources(): void
    {
        $source1 = new Source('Title 1', 'https://example.com/1', 'Content 1');
        $source2 = new Source('Title 2', 'https://example.com/2', 'Content 2');

        $map = new SourceMap($source1, $source2);

        self::assertCount(2, $map->sources());
        self::assertSame([$source1, $source2], $map->sources());
    }

    #[Test]
    public function it_can_add_sources(): void
    {
        $map = new SourceMap();
        $source1 = new Source('Title 1', 'https://example.com/1', 'Content 1');
        $source2 = new Source('Title 2', 'https://example.com/2', 'Content 2');

        $result = $map->add($source1, $source2);

        self::assertSame($map, $result);
        self::assertCount(2, $map->sources());
        self::assertSame([$source1, $source2], $map->sources());
    }

    #[Test]
    public function it_maintains_order_when_adding_sources(): void
    {
        $source1 = new Source('First', 'https://example.com/1', 'Content 1');
        $source2 = new Source('Second', 'https://example.com/2', 'Content 2');
        $source3 = new Source('Third', 'https://example.com/3', 'Content 3');

        $map = new SourceMap($source1);
        $map->add($source2, $source3);

        $sources = $map->sources();
        self::assertSame('First', $sources[0]->name);
        self::assertSame('Second', $sources[1]->name);
        self::assertSame('Third', $sources[2]->name);
    }

    #[Test]
    public function it_is_iterable(): void
    {
        $source1 = new Source('First', 'https://example.com/1', 'Content 1');
        $source2 = new Source('Second', 'https://example.com/2', 'Content 2');

        $map = new SourceMap($source1, $source2);

        $names = [];
        foreach ($map as $source) {
            $names[] = $source->name;
        }

        self::assertSame(['First', 'Second'], $names);
    }

    #[Test]
    public function it_is_countable(): void
    {
        $map = new SourceMap();
        self::assertCount(0, $map);

        $map->add(
            new Source('First', 'https://example.com/1', 'Content 1'),
            new Source('Second', 'https://example.com/2', 'Content 2')
        );

        self::assertCount(2, $map);
    }
}
