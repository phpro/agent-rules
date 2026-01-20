<?php

declare(strict_types=1);


namespace Phpro\AgentRules\Result;

use Symfony\AI\Agent\Toolbox\Source\SourceMap;

interface ResultInterface extends \JsonSerializable
{
    public function getStatus(): string;

    public function sources(): SourceMap;
}
