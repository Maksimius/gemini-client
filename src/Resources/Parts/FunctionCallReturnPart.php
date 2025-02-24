<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Parts;

use GeminiAPI\Enums\Role;
use JsonSerializable;

use function json_encode;

class FunctionCallReturnPart implements PartInterface, JsonSerializable
{
    public function __construct(
        public readonly FunctionCallPart $functionCall,
    ) {
    }

    /**
     * @return array{
     *     text: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->functionCall->jsonSerialize();
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
}
