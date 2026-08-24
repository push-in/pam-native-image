<!-- pam:product-page:start -->
<div align="center">

# PAM Native Image

**Fast images, predictable memory, native scrolling.**

Load, resize, cache, cancel, and recycle remote or sandboxed images through mature native pipelines—without bundling a feed framework.

[![Latest version](https://img.shields.io/packagist/v/pushinbr/pam-native-image?style=flat-square&label=stable)](https://packagist.org/packages/pushinbr/pam-native-image)
[![CI](https://img.shields.io/github/actions/workflow/status/push-in/pam-native-image/ci.yml?branch=main&style=flat-square&label=CI)](https://github.com/push-in/pam-native-image/actions)
![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?style=flat-square&logo=php&logoColor=white)
![Android](https://img.shields.io/badge/Android-API%2026%2B-3DDC84?style=flat-square&logo=android&logoColor=white)
![iOS](https://img.shields.io/badge/iOS-15%2B-000000?style=flat-square&logo=apple&logoColor=white)

**[Documentation](https://push-in.github.io/pam-docs/native/overview/) · [Quick start](#quick-start) · [What you can build](#what-you-can-build) · [PAM ecosystem](https://push-in.github.io/pam-docs/ecosystem/) · [Issues](https://github.com/push-in/pam-native-image/issues)**

</div>

---

## Why PAM Native Image

Load, resize, cache, cancel, and recycle remote or sandboxed images through mature native pipelines—without bundling a feed framework. The public API is strictly typed for PHP 8.5; expensive or frame-sensitive work stays in Rust or the platform SDK instead of crossing the application boundary every frame.

| | |
| --- | --- |
| **Best for** | A focused capability you can add to any PAM Native application |
| **Native path** | Coil 3 · SDWebImage |
| **Application model** | Composer package + generated native integration |
| **Design rule** | Independent module; no feed, vertical, or application template bundled |

## What you can build

- Poster walls and media catalogs
- Product grids, avatars, and social timelines
- Offline-aware, memory-bounded image surfaces

## Quick start

Already have a PAM Native project? Add only this capability:

```bash
pam composer require pushinbr/pam-native-image
pam doctor --fix
```

New to PAM? Follow the **[five-minute PAM Native setup](https://push-in.github.io/pam-docs/native/overview/)** once, then return here. Your application stays a normal Composer project with a committed lockfile.
<!-- pam:product-page:end -->

## See it in action

```php
return NativeImage::make('https://cdn.example.com/poster.webp')
    ->contentMode(ImageContentMode::Cover)
    ->cachePolicy(ImageCachePolicy::MemoryAndDisk)
    ->crossfade(120);
```

The decoder, resizing, cancellation, memory/disk caching, and recycled-view safety stay native through Coil 3.3 on Android and SDWebImage 5.21 on iOS. It is a horizontal primitive and never installs a feed or application template.

Sources must be HTTPS or sandbox-relative. Always provide semantic image descriptions in the surrounding PAM UI surface, test low-memory eviction and offline behavior, and run `pam production certify` before release.
