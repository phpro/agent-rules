<?php

declare(strict_types=1);


namespace Phpro\AgentRules;

/**
 * @template T
 */
final readonly class RuleEngine
{
    /**
     * @var RuleInterface<T>
     */
    private RuleInterface $rules;

    /**
     * @no-named-arguments
     * @param list<RuleInterface<T>> $rules
     *
     * @throws \InvalidArgumentException When a rule depends on an unknown rule.
     */
    public function __construct(
        RuleInterface ... $rules
    ) {
        $this->rules = new Sequence('rule_engine', $rules, []);
    }

    /**
     * @template TA
     *
     * @param iterable<int, RuleInterface<TA>> $rules
     *
     * @return self<TA>
     *
     * @throws \InvalidArgumentException When a rule depends on an unknown rule.
     */
    public static function fromIterable(iterable $rules): self
    {
        return new self(...$rules);
    }

    public function evaluate(mixed $subject): RuleEvaluation
    {
        return $this->rules->check($subject);
    }
}
