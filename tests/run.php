<?php

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';

use Pam\Native\Image\ImageCachePolicy;
use Pam\Native\Image\ImageContentMode;
use Pam\Native\Image\NativeImage;
use Pam\Native\UI\CustomView;

$tests = 0;
$assert = static function (bool $condition, string $message) use (&$tests): void { $tests++; if (!$condition) { throw new RuntimeException($message); } };
$image = NativeImage::make('https://cdn.example.test/poster.webp')->contentMode(ImageContentMode::Cover)->cachePolicy(ImageCachePolicy::MemoryAndDisk)->crossfade();
$assert($image->toElement()::class === CustomView::class, 'Image must render as a native cached view.');
$assert($image !== $image->contentMode(ImageContentMode::Center), 'Builder must remain immutable.');
foreach (['', 'http://example.test/image.png'] as $source) {
    try { NativeImage::make($source); throw new RuntimeException('Invalid source accepted.'); }
    catch (InvalidArgumentException) { $assert(true, 'Expected source rejection.'); }
}
echo "PASS cached native image contract\n{$tests} assertions, 0 failures\n";
