<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Phpro\AgentRules\RuleInterface;
use Phpro\AgentRules\RulesAnalyzer;

#[CoversClass(RulesAnalyzer::class)]
final class RulesAnalyzerTest extends TestCase
{
    #[Test]
    public function it_resolves_rules_in_topological_order(): void
    {
        $rule1 = $this->createStub(RuleInterface::class);
        $rule1->method('name')->willReturn('rule1');
        $rule1->method('dependencies')->willReturn([]);

        $rule2 = $this->createStub(RuleInterface::class);
        $rule2->method('name')->willReturn('rule2');
        $rule2->method('dependencies')->willReturn(['rule1']);

        $rule3 = $this->createStub(RuleInterface::class);
        $rule3->method('name')->willReturn('rule3');
        $rule3->method('dependencies')->willReturn(['rule1', 'rule2']);

        $resolved = RulesAnalyzer::resolve([$rule3, $rule1, $rule2]);

        static::assertCount(3, $resolved);
        static::assertSame('rule1', $resolved[0]->name());
        static::assertSame('rule2', $resolved[1]->name());
        static::assertSame('rule3', $resolved[2]->name());
    }

    #[Test]
    public function it_throws_exception_for_unknown_dependency(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Rule 'rule1' depends on unknown rule 'unknown'");

        $rule1 = $this->createStub(RuleInterface::class);
        $rule1->method('name')->willReturn('rule1');
        $rule1->method('dependencies')->willReturn(['unknown']);

        RulesAnalyzer::resolve([$rule1]);
    }

    #[Test]
    public function it_throws_exception_for_cyclic_dependency(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cyclic dependency detected among rules.');

        $rule1 = $this->createStub(RuleInterface::class);
        $rule1->method('name')->willReturn('rule1');
        $rule1->method('dependencies')->willReturn(['rule2']);

        $rule2 = $this->createStub(RuleInterface::class);
        $rule2->method('name')->willReturn('rule2');
        $rule2->method('dependencies')->willReturn(['rule1']);

        RulesAnalyzer::resolve([$rule1, $rule2]);
    }

    #[Test]
    public function it_handles_empty_rule_list(): void
    {
        $resolved = RulesAnalyzer::resolve([]);

        static::assertSame([], $resolved);
    }

    #[Test]
    public function it_handles_rules_with_no_dependencies(): void
    {
        $rule1 = $this->createStub(RuleInterface::class);
        $rule1->method('name')->willReturn('rule1');
        $rule1->method('dependencies')->willReturn([]);

        $rule2 = $this->createStub(RuleInterface::class);
        $rule2->method('name')->willReturn('rule2');
        $rule2->method('dependencies')->willReturn([]);

        $resolved = RulesAnalyzer::resolve([$rule1, $rule2]);

        static::assertCount(2, $resolved);
    }
}
