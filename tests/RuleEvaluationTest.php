<?php

declare(strict_types=1);

namespace Phpro\AgentRules\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Phpro\AgentRules\Result\BlockedResult;
use Phpro\AgentRules\RuleEvaluation;

#[CoversClass(RuleEvaluation::class)]
final class RuleEvaluationTest extends TestCase
{
    #[Test]
    public function it_can_create_passing_evaluation(): void
    {
        $evaluation = RuleEvaluation::pass();

        static::assertTrue($evaluation->isPass());
        static::assertNull($evaluation->result);
    }

    #[Test]
    public function it_can_create_failing_evaluation(): void
    {
        $result = new BlockedResult('test', 'blocked');
        $evaluation = RuleEvaluation::respond($result);

        static::assertFalse($evaluation->isPass());
        static::assertSame($result, $evaluation->result);
    }

    #[Test]
    public function it_returns_true_for_is_pass_when_result_is_null(): void
    {
        $evaluation = RuleEvaluation::pass();

        static::assertTrue($evaluation->isPass());
    }

    #[Test]
    public function it_returns_false_for_is_pass_when_result_is_not_null(): void
    {
        $result = new BlockedResult('test', 'blocked');
        $evaluation = RuleEvaluation::respond($result);

        static::assertFalse($evaluation->isPass());
    }
}
