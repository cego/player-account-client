<?php

namespace Cego\PlayerAccount\Enums;

/**
 * Class Site
 * Enumeration of supported sites
 */
class Sites
{
    const SPILNU = 'spilnu';
    const LYCKOST = 'lyckost';

    const URLS = [
        self::SPILNU  => 'spilnu.dk',
        self::LYCKOST => 'lyckost.se',
    ];
}
