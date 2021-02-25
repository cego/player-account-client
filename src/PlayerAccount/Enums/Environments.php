<?php

namespace Cego\PlayerAccount\Enums;

/**
 * Class Environment
 * Enumeration of supported environments
 */
class Environments
{
    const TESTING = 'testing';
    const LOCAL = 'local';
    const STAGE = 'stage';
    const PRODUCTION = 'production';

    const ALL = [
        self::TESTING,
        self::LOCAL,
        self::STAGE,
        self::PRODUCTION,
    ];
}
