<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Phpro\AgentRules\RuleEngine;
use Phpro\AgentRules\RuleEvaluation;
use Phpro\AgentRules\RuleInterface;

#[CoversClass(RuleEngine::class)]
final class RuleEngineTest extends TestCase
{
    #[Test]
    public function it_evaluates_to_pass_when_all_rules_pass(): void
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

        $engine = new RuleEngine($rule1, $rule2);
        $result = $engine->evaluate('subject');

        static::assertTrue($result->isPass());
    }

    #[Test]
    public function it_evaluates_to_first_failure_when_any_rule_fails(): void
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

        $engine = new RuleEngine($rule1, $rule2, $rule3);
        $result = $engine->evaluate('subject');

        static::assertSame($failedEvaluation, $result);
        static::assertFalse($result->isPass());
    }

    #[Test]
    public function it_can_be_created_from_iterable(): void
    {
        $rule = $this->createMock(RuleInterface::class);
        $rule->method('name')->willReturn('rule1');
        $rule->method('dependencies')->willReturn([]);
        $rule->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn(RuleEvaluation::pass());

        $engine = RuleEngine::fromIterable([$rule]);
        $result = $engine->evaluate('subject');

        static::assertTrue($result->isPass());
    }

    #[Test]
    public function it_evaluates_to_pass_with_no_rules(): void
    {
        $engine = new RuleEngine();
        $result = $engine->evaluate('subject');

        static::assertTrue($result->isPass());
    }
}
