<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'authAdmin'     => \App\Filters\AuthAdmin::class,
        'authUser'      => \App\Filters\AuthUser::class,
    ];

    public array $globals = [
        'before' => [
            'csrf',
            // FIX: invalidchars & secureheaders di-comment karena bikin error.
            // 'invalidchars',
        ],
        'after'  => [
            'toolbar',
            // 'secureheaders',
        ],
    ];

    public array $methods = [];
    public array $filters = [];
}