# PAM Native Image

## Start here

Install PAM, create a native project, and add only the image capability:

```bash
curl --proto '=https' --proto-redir '=https' --tlsv1.2 --connect-timeout 15 --max-time 60 --max-filesize 1048576 -fsSL https://github.com/push-in/pam/releases/latest/download/install.sh | sh
pam init my-app --template native
cd my-app
pam composer require pushinbr/pam-native-image
pam doctor --fix
```

```php
return NativeImage::make('https://cdn.example.com/poster.webp')
    ->contentMode(ImageContentMode::Cover)
    ->cachePolicy(ImageCachePolicy::MemoryAndDisk)
    ->crossfade(120);
```

The decoder, resizing, cancellation, memory/disk caching, and recycled-view safety stay native through Coil 3.3 on Android and SDWebImage 5.21 on iOS. It is a horizontal primitive and never installs a feed or application template.

Sources must be HTTPS or sandbox-relative. Always provide semantic image descriptions in the surrounding PAM UI surface, test low-memory eviction and offline behavior, and run `pam production certify` before release.
