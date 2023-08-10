<?php
/**
 * Concord CRM - https://www.concordcrm.com
 *
 * @version   1.2.1
 *
 * @link      Releases - https://www.concordcrm.com/releases
 * @link      Terms Of Service - https://www.concordcrm.com/terms
 *
 * @copyright Copyright (c) 2022-2023 KONKORD DIGITAL
 */

namespace Modules\Core\Placeholders;

use JsonSerializable;
use Mustache_Engine;
use Mustache_Exception_SyntaxException;

class Collection implements JsonSerializable
{
    /**
     * Parsed placeholders cache.
     */
    protected array $parsed = [];

    /**
     * Create new Collection instance.
     */
    public function __construct(protected array $placeholders)
    {
    }

    /**
     * Get all of the collection placeholders.
     */
    public function all(): array
    {
        return $this->placeholders;
    }

    /**
     * Parse all the placeholders with their formatted values.
     */
    public function parse(?string $contentType = 'html'): array
    {
        $cacheKey = $contentType ?? 'general';

        if (! array_key_exists($cacheKey, $this->parsed)) {
            $this->parsed[$cacheKey] = $this->performFormatting($contentType);
        }

        return $this->parsed[$cacheKey];
    }

    /**
     * Remove placeholder from the collection.
     */
    public function forget(string|array $tagName): static
    {
        $this->placeholders = collect($this->placeholders)->reject(
            fn ($placeholder) => in_array($placeholder->tag, (array) $tagName)
        )->values()->all();

        $this->parsed = [];

        return $this;
    }

    /**
     * Add new placeholder to the collection.
     */
    public function push(Placeholder|array $placeholders): static
    {
        $this->placeholders = array_merge(
            $this->placeholders,
            is_array($placeholders) ? $placeholders : func_get_args()
        );

        $this->parsed = [];

        return $this;
    }

    /**
     * Replace the placeholders to the given template.
     *
     * @param  string  $template
     * @param  array  $placeholders
     * @return string
     */
    public function render($template, array $placeholders = null)
    {
        try {
            return (new Mustache_Engine())->render($template, $placeholders ?? $this->parse());
        } catch (Mustache_Exception_SyntaxException) {
            return $template;
        }
    }

    /**
     * Perform formatting on the placeholders
     */
    protected function performFormatting(?string $contentType): array
    {
        return collect($this->placeholders)->mapWithKeys(
            fn (Placeholder $placeholder) => [$placeholder->tag => $placeholder->format($contentType)]
        )->undot()->all();
    }

    /**
     * jsonSerialize
     */
    public function jsonSerialize(): array
    {
        return $this->placeholders;
    }
}
