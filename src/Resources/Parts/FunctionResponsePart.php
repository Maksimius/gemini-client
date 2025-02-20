<?php

namespace GeminiAPI\Resources\Parts;

use JsonSerializable;

use function json_encode;

class FunctionResponsePart implements PartInterface, JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly array|string $result,
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
            'functionResponse' => [
                'name' => $this->name,
                'response' => [
                    'result' => $this->result,
                ],]
        ];
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
}
