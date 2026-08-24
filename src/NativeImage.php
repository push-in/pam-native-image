<?php

declare(strict_types=1);

namespace Pam\Native\Image;

use Closure;
use InvalidArgumentException;
use Pam\Native\Element;
use Pam\Native\Internal\Wire;
use Pam\Native\Renderable;
use Pam\Native\Style;
use Pam\Native\UI\CustomView;

final class NativeImage implements Renderable
{
    /** @var array<string, string|int|float|bool> */
    private array $properties;
    private ?Closure $handler = null;
    private ?Style $style = null;

    private function __construct(string $source)
    {
        self::assertSource($source);
        $this->properties = ['source' => $source, 'contentMode' => 1, 'cachePolicy' => 1, 'crossfadeMillis' => 120];
    }

    public static function make(string $source): self { return new self($source); }
    public function contentMode(ImageContentMode $mode): self { return $this->with('contentMode', $mode->value); }
    public function cachePolicy(ImageCachePolicy $policy): self { return $this->with('cachePolicy', $policy->value); }
    public function crossfade(int $milliseconds = 120): self { return $this->with('crossfadeMillis', max(0, min(1_000, $milliseconds))); }
    public function placeholder(string $source): self { self::assertSource($source); return $this->with('placeholder', $source); }
    public function style(Style $style): self { $copy = clone $this; $copy->style = $style; return $copy; }

    /** @param Closure(ImageEventKind, array<string, string|int|float|bool>): void $handler */
    public function onEvent(Closure $handler): self { $copy = clone $this; $copy->handler = $handler; return $copy; }

    public function toElement(): Element
    {
        $view = CustomView::make('image.cached', $this->properties);
        if ($this->style !== null) {
            $view = $view->style($this->style);
        }
        $handler = $this->handler;
        return $handler === null ? $view : $view->onNativeEvent(static function (string $payload) use ($handler): void {
            $values = Wire::decodeMap($payload);
            $handler(ImageEventKind::tryFrom((int) ($values['event'] ?? 2)) ?? ImageEventKind::Failed, $values);
        });
    }

    private function with(string $key, string|int|float|bool $value): self { $copy = clone $this; $copy->properties[$key] = $value; return $copy; }

    private static function assertSource(string $source): void
    {
        if ($source === '' || strlen($source) > 8192 || str_contains($source, "\0") || (str_contains($source, '://') && !str_starts_with($source, 'https://'))) {
            throw new InvalidArgumentException('Image sources must be HTTPS URLs or relative sandbox paths.');
        }
    }
}
