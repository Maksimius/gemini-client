<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Parts;

use JsonSerializable;

use function json_encode;

class FunctionCallPart implements PartInterface, JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly array $args,
    ) {
    }

    /**
     * @return array{
     *     text: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            "functionCall" => [
                "name" => $this->name,
                "args" => $this->args,
            ]
        ];
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
}
