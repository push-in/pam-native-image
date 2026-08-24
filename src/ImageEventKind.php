<?php

declare(strict_types=1);

namespace Pam\Native\Image;

enum ImageEventKind: int
{
    case Loaded = 1;
    case Failed = 2;
}
