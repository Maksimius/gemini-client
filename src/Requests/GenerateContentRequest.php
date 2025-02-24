<?php

declare(strict_types=1);

namespace GeminiAPI\Requests;

use GeminiAPI\Enums\InternalTool;
use GeminiAPI\Enums\ModelName;
use GeminiAPI\GenerationConfig;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\Parts\FunctionPart;
use GeminiAPI\SafetySetting;
use GeminiAPI\Traits\ArrayTypeValidator;
use GeminiAPI\Traits\ModelNameToString;
use Illuminate\Support\Arr;
use JsonSerializable;

use function json_encode;

class GenerateContentRequest implements JsonSerializable, RequestInterface
{
    use ArrayTypeValidator;
    use ModelNameToString;

    /**
     * @param ModelName|string $modelName
     * @param Content[] $contents
     * @param SafetySetting[] $safetySettings
     * @param GenerationConfig|null $generationConfig
     * @param ?Content $systemInstruction
     * @param ?Array $functionDeclarations
     */
    public function __construct(
        public readonly ModelName|string $modelName,
        public readonly array $contents,
        public readonly array $safetySettings = [],
        public readonly ?GenerationConfig $generationConfig = null,
        public readonly ?Content $systemInstruction = null,
        public readonly ?array $functionDeclarations = null,
        public readonly ?array $tools = null,
    ) {
        $this->ensureArrayOfType($this->contents, Content::class);
        $this->ensureArrayOfType($this->safetySettings, SafetySetting::class);
    }

    public function getOperation(): string
    {
        return "{$this->modelNameToString($this->modelName)}:generateContent";
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getHttpPayload(): string
    {
        return (string) $this;
    }

    /**
     * @return array{
     *     model: string,
     *     contents: Content[],
     *     safetySettings?: SafetySetting[],
     *     generationConfig?: GenerationConfig,
     *     systemInstruction?: Content,
     * }
     */
    public function jsonSerialize(): array
    {
        $arr = [
            'model' => $this->modelNameToString($this->modelName),
            'contents' => $this->contents,
        ];

        if (!empty($this->safetySettings)) {
            $arr['safetySettings'] = $this->safetySettings;
        }

        if ($this->generationConfig) {
            $arr['generationConfig'] = $this->generationConfig;
        }

        if ($this->systemInstruction) {
            $arr['systemInstruction'] = $this->systemInstruction;
        }

        if ($this->functionDeclarations) {
            $arr['tools']['functionDeclarations'] = $this->functionDeclarations;
            if (is_array($this->functionDeclarations)) {
                /** @var FunctionPart $el */
                $arr['toolConfig'] = [
                    'functionCallingConfig' => [
                        'mode' => 'AUTO', // 'AUTO', 'ANY'
                        //'allowedFunctionNames' =>  array_map(fn($el) => $el->name, $this->functionDeclarations),
                    ],
                ];
            }
        }

        if ($this->tools) {
            foreach ($this->tools as $tool) {
                /** @var InternalTool $tool */
                $arr['tools'][][$tool->value] = (object)[];
            }
        }

        return $arr;
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
}
