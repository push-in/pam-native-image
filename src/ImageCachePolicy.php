<?php

declare(strict_types=1);

namespace Pam\Native\Image;

enum ImageCachePolicy: int
{
    case MemoryAndDisk = 1;
    case Memory = 2;
    case Disk = 3;
    case Disabled = 4;
}
