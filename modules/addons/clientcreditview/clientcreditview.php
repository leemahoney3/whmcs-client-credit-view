<?php

use LMTech\Addons\ClientCreditView\Config\Config;
use LMTech\Addons\ClientCreditView\ClientArea\ClientArea;

/**
 * WHMCS Client Credit View
 *
 * Allows clients to view their credit balance as well as previous
 * credit transactions on their account.
 *
 * @package    WHMCS
 * @author     Lee Mahoney <lee@leemahoney.dev>
 * @copyright  Copyright (c) Lee Mahoney 2022
 * @license    MIT License
 * @version    1.0.0
 * @link       https://leemahoney.dev
 */

if (!defined("WHMCS")) {
    exit("This file cannot be accessed directly");
}

require_once __DIR__ . '/vendor/autoload.php';

function clientcreditview_config() {
    return Config::populate();
}

function clientcreditview_clientarea($vars) {
    return ClientArea::output($vars);
}