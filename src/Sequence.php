<?php

declare(strict_types=1);


namespace Phpro\AgentRules;

/**
 * @template T
 *
 * @implements RuleInterface<T>
 */
final readonly class Sequence implements RuleInterface
{

    /**
     * @var list<RuleInterface<T>>
     */
    private array $rules;

    /**
     * @param string $name
     * @param iterable<RuleInterface<T>> $rules
     * @param list<string> $dependencies
     */
    public function __construct(
        private string $name,
        array $rules,
        private array $dependencies,
    ) {
        $this->rules = RulesAnalyzer::resolve($rules);
    }

    #[\Override]
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return list<string>
     */
    #[\Override]
    public function dependencies(): array
    {
        return $this->dependencies;
    }

    #[\Override]
    public function check(mixed $subject): RuleEvaluation
    {
        foreach ($this->rules as $rule) {
            $evaluation = $rule->check($subject);
            if (!$evaluation->isPass()) {
                return $evaluation;
            }
        }

        return RuleEvaluation::pass();
    }
}
