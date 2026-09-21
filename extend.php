<?php

/*
 * This file is part of justoverclock/flarum-ext-purify.
 *
 * Copyright (c) 2021 Marco Colia.
 * https://flarum.it
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Justoverclock\Purify;

use Flarum\Extend;
use Justoverclock\Purify\Listeners\CustomPurifier;
use Justoverclock\Purify\Listeners\ObscureBadWords;
use Justoverclock\Purify\Listeners\ObscureEmail;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),
    new Extend\Locales(__DIR__.'/resources/locale'),
    (new Extend\Event())
        ->subscribe(ObscureBadWords::class),
    (new Extend\Event())
        ->subscribe(ObscureEmail::class),
    (new Extend\Event())
        ->subscribe(CustomPurifier::class),
];
