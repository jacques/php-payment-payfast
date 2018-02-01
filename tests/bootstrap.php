<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/*
 * @author    Jacques Marneweck <jacques@siberia.co.za>
 * @copyright 2018 Jacques Marneweck.
 */

require_once __DIR__.'/../vendor/autoload.php';

\VCR\VCR::configure()
    ->enableRequestMatchers(['method', 'url', 'host'])
    ->setStorage('json');
\VCR\VCR::turnOn();
