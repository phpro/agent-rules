<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Phpro\AgentRules\RuleEvaluation;
use Phpro\AgentRules\RuleInterface;
use Phpro\AgentRules\Sequence;

#[CoversClass(Sequence::class)]
final class SequenceTest extends TestCase
{
    #[Test]
    public function it_can_return_its_name(): void
    {
        $rule = $this->createStub(RuleInterface::class);
        $rule->method('name')->willReturn('rule1');
        $rule->method('dependencies')->willReturn([]);

        $sequence = new Sequence('test-sequence', [$rule], []);

        static::assertSame('test-sequence', $sequence->name());
    }

    #[Test]
    public function it_can_return_its_dependencies(): void
    {
        $rule = $this->createStub(RuleInterface::class);
        $rule->method('name')->willReturn('rule1');
        $rule->method('dependencies')->willReturn([]);

        $dependencies = ['dep1', 'dep2'];
        $sequence = new Sequence('test-sequence', [$rule], $dependencies);

        static::assertSame($dependencies, $sequence->dependencies());
    }

    #[Test]
    public function it_returns_pass_when_all_rules_pass(): void
    {
        $rule1 = $this->createMock(RuleInterface::class);
        $rule1->method('name')->willReturn('rule1');
        $rule1->method('dependencies')->willReturn([]);
        $rule1->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn(RuleEvaluation::pass());

        $rule2 = $this->createMock(RuleInterface::class);
        $rule2->method('name')->willReturn('rule2');
        $rule2->method('dependencies')->willReturn([]);
        $rule2->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn(RuleEvaluation::pass());

        $sequence = new Sequence('test-sequence', [$rule1, $rule2], []);
        $result = $sequence->check('subject');

        static::assertTrue($result->isPass());
    }

    #[Test]
    public function it_returns_first_failure_when_any_rule_fails(): void
    {
        $rule1 = $this->createMock(RuleInterface::class);
        $rule1->method('name')->willReturn('rule1');
        $rule1->method('dependencies')->willReturn([]);
        $rule1->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn(RuleEvaluation::pass());

        $failedEvaluation = RuleEvaluation::respond(
            new \Phpro\AgentRules\Result\BlockedResult('test', 'blocked')
        );

        $rule2 = $this->createMock(RuleInterface::class);
        $rule2->method('name')->willReturn('rule2');
        $rule2->method('dependencies')->willReturn([]);
        $rule2->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn($failedEvaluation);

        $rule3 = $this->createMock(RuleInterface::class);
        $rule3->method('name')->willReturn('rule3');
        $rule3->method('dependencies')->willReturn([]);
        $rule3->expects($this->never())
            ->method('check');

        $sequence = new Sequence('test-sequence', [$rule1, $rule2, $rule3], []);
        $result = $sequence->check('subject');

        static::assertSame($failedEvaluation, $result);
        static::assertFalse($result->isPass());
    }

    #[Test]
    public function it_returns_pass_for_empty_rule_list(): void
    {
        $sequence = new Sequence('test-sequence', [], []);
        $result = $sequence->check('subject');

        static::assertTrue($result->isPass());
    }
}
