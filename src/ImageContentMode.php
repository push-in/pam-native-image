<?php

declare(strict_types=1);

namespace Pam\Native\Image;

enum ImageContentMode: int
{
    case Contain = 1;
    case Cover = 2;
    case Fill = 3;
    case Center = 4;
}
