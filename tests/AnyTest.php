<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Phpro\AgentRules\Any;
use Phpro\AgentRules\RuleEvaluation;
use Phpro\AgentRules\RuleInterface;

#[CoversClass(Any::class)]
final class AnyTest extends TestCase
{
    #[Test]
    public function it_can_return_its_name(): void
    {
        $rule = $this->createStub(RuleInterface::class);
        $rule->method('name')->willReturn('rule1');
        $rule->method('dependencies')->willReturn([]);

        $any = new Any('test-any', [$rule], []);

        static::assertSame('test-any', $any->name());
    }

    #[Test]
    public function it_can_return_its_dependencies(): void
    {
        $rule = $this->createStub(RuleInterface::class);
        $rule->method('name')->willReturn('rule1');
        $rule->method('dependencies')->willReturn([]);

        $dependencies = ['dep1', 'dep2'];
        $any = new Any('test-any', [$rule], $dependencies);

        static::assertSame($dependencies, $any->dependencies());
    }

    #[Test]
    public function it_returns_first_passing_rule_evaluation(): void
    {
        $rule1 = $this->createMock(RuleInterface::class);
        $rule1->method('name')->willReturn('rule1');
        $rule1->method('dependencies')->willReturn([]);
        $rule1->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn(RuleEvaluation::respond(
                new \Phpro\AgentRules\Result\BlockedResult('test', 'blocked')
            ));

        $rule2 = $this->createMock(RuleInterface::class);
        $rule2->method('name')->willReturn('rule2');
        $rule2->method('dependencies')->willReturn([]);
        $passEvaluation = RuleEvaluation::pass();
        $rule2->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn($passEvaluation);

        $rule3 = $this->createMock(RuleInterface::class);
        $rule3->method('name')->willReturn('rule3');
        $rule3->method('dependencies')->willReturn([]);
        $rule3->expects($this->never())
            ->method('check');

        $any = new Any('test-any', [$rule1, $rule2, $rule3], []);
        $result = $any->check('subject');

        static::assertSame($passEvaluation, $result);
        static::assertTrue($result->isPass());
    }

    #[Test]
    public function it_returns_first_evaluation_when_all_fail(): void
    {
        $firstEvaluation = RuleEvaluation::respond(
            new \Phpro\AgentRules\Result\BlockedResult('first', 'blocked first')
        );

        $rule1 = $this->createMock(RuleInterface::class);
        $rule1->method('name')->willReturn('rule1');
        $rule1->method('dependencies')->willReturn([]);
        $rule1->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn($firstEvaluation);

        $rule2 = $this->createMock(RuleInterface::class);
        $rule2->method('name')->willReturn('rule2');
        $rule2->method('dependencies')->willReturn([]);
        $rule2->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn(RuleEvaluation::respond(
                new \Phpro\AgentRules\Result\BlockedResult('second', 'blocked second')
            ));

        $any = new Any('test-any', [$rule1, $rule2], []);
        $result = $any->check('subject');

        static::assertSame($firstEvaluation, $result);
        static::assertFalse($result->isPass());
    }

    #[Test]
    public function it_throws_exception_when_no_rules_provided(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Any rule engine must have at least one rule to evaluate.');

        $any = new Any('test-any', [], []);
        $any->check('subject');
    }
}
