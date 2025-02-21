<?php

declare(strict_types=1);

namespace GeminiAPI\Resources\Parts;

use JsonSerializable;

use function json_encode;

class FunctionPart implements PartInterface, JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $parametersType,
        public readonly array $parametersproPerties,
        public readonly array $required,
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
            "name" => $this->name,
            "description" => $this->description,
            "parameters" => [
                "type" => $this->parametersType,
                "properties" => $this->parametersproPerties,
                "required" => $this->required,
            ],
        ];
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
}
