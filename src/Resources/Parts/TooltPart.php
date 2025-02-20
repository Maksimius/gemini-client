<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Parts;

use JsonSerializable;

use function json_encode;

class ToolPart implements PartInterface, JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly string $result,
    ) {
    }

    /**
     * @return array{
     *     text: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return ['text' => $this->text];
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
}
