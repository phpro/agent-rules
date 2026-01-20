<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Phpro\AgentRules\Either;
use Phpro\AgentRules\RuleEvaluation;
use Phpro\AgentRules\RuleInterface;

#[CoversClass(Either::class)]
final class EitherTest extends TestCase
{
    #[Test]
    public function it_can_return_its_name(): void
    {
        $left = $this->createStub(RuleInterface::class);
        $right = $this->createStub(RuleInterface::class);

        $either = new Either('test-either', $left, $right, []);

        static::assertSame('test-either', $either->name());
    }

    #[Test]
    public function it_can_return_its_dependencies(): void
    {
        $left = $this->createStub(RuleInterface::class);
        $right = $this->createStub(RuleInterface::class);

        $dependencies = ['dep1', 'dep2'];
        $either = new Either('test-either', $left, $right, $dependencies);

        static::assertSame($dependencies, $either->dependencies());
    }

    #[Test]
    public function it_returns_left_evaluation_when_left_passes(): void
    {
        $leftEvaluation = RuleEvaluation::pass();

        $left = $this->createMock(RuleInterface::class);
        $left->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn($leftEvaluation);

        $right = $this->createMock(RuleInterface::class);
        $right->expects($this->never())
            ->method('check');

        $either = new Either('test-either', $left, $right, []);
        $result = $either->check('subject');

        static::assertSame($leftEvaluation, $result);
        static::assertTrue($result->isPass());
    }

    #[Test]
    public function it_returns_right_evaluation_when_left_fails_and_right_passes(): void
    {
        $leftEvaluation = RuleEvaluation::respond(
            new \Phpro\AgentRules\Result\BlockedResult('test', 'blocked')
        );
        $rightEvaluation = RuleEvaluation::pass();

        $left = $this->createMock(RuleInterface::class);
        $left->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn($leftEvaluation);

        $right = $this->createMock(RuleInterface::class);
        $right->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn($rightEvaluation);

        $either = new Either('test-either', $left, $right, []);
        $result = $either->check('subject');

        static::assertSame($rightEvaluation, $result);
        static::assertTrue($result->isPass());
    }

    #[Test]
    public function it_returns_left_evaluation_when_both_fail(): void
    {
        $leftEvaluation = RuleEvaluation::respond(
            new \Phpro\AgentRules\Result\BlockedResult('left', 'blocked left')
        );
        $rightEvaluation = RuleEvaluation::respond(
            new \Phpro\AgentRules\Result\BlockedResult('right', 'blocked right')
        );

        $left = $this->createMock(RuleInterface::class);
        $left->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn($leftEvaluation);

        $right = $this->createMock(RuleInterface::class);
        $right->expects($this->once())
            ->method('check')
            ->with('subject')
            ->willReturn($rightEvaluation);

        $either = new Either('test-either', $left, $right, []);
        $result = $either->check('subject');

        static::assertSame($leftEvaluation, $result);
        static::assertFalse($result->isPass());
    }
}
