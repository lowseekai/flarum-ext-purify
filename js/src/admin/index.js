/*
 * This file is part of justoverclock/flarum-ext-purify.
 *
 * Copyright (c) 2021 Marco Colia.
 * https://flarum.it
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import app from 'flarum/admin/app';

app.initializers.add('justoverclock/flarum-ext-purify', () => {
  app.registry
    .for('justoverclock-purify')
    .registerSetting({
      setting: 'justoverclock-purify.badWordsList',
      name: 'badWordsList',
      type: 'text',
      label: app.translator.trans('flarum-ext-purify.admin.badWords'),
      help: app.translator.trans('flarum-ext-purify.admin.additemdesc'),
      placeholder: 'word1,word2,word3',
    })
    .registerSetting({
      setting: 'justoverclock-purify.AlsoEmail',
      label: app.translator.trans('flarum-ext-purify.admin.hidemail'),
      type: 'boolean',
    })
    .registerSetting({
      setting: 'justoverclock-purify.CustomRegexp',
      label: app.translator.trans('flarum-ext-purify.admin.customreg'),
      type: 'boolean',
    })
    .registerSetting({
      setting: 'justoverclock-purify.regexcustom',
      name: 'regexcustom',
      type: 'text',
      label: app.translator.trans('flarum-ext-purify.admin.customregexp'),
      help: app.translator.trans('flarum-ext-purify.admin.customregexpdesc'),
      placeholder: 'a custom regex (e.g. #\\((.*?)\\)#)',
    });
});
