<?php

use WHMCS\View\Menu\Item as MenuItem;
use WHMCS\View\Menu\Item as SidebarItem;

use LMTech\Addons\ClientCreditView\Config\Config;
use LMTech\Addons\ClientCreditView\Models\Credit;

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

function clientcreditview_primary_navbar(MenuItem $primaryNavbar) {

    if (!Config::get('showInNav')) {
        return;
    }

    if (!is_null($primaryNavbar->getChild('Billing'))) {

        $primaryNavbar->getChild('Billing')
        ->addChild('Account Credit')
        ->setUri('index.php?m=clientcreditview')
        ->setOrder(90);

    }

}

function clientcreditview_primary_sidebar(SidebarItem $primarySidebar) {
    
    $clientId = (int) $_SESSION['uid'];

    if (!Config::get('showInSidebar') || empty($clientId) || $_SERVER['REQUEST_URI'] != '/clientarea.php') {
        return;
    }

    $currencyData   = getCurrency($clientId);
    $creditBalance  = formatCurrency(Credit::where('clientid', $clientId)->orderBy('date', 'desc')->get()->sum('amount'), $currencyData['id']);

    $html = '
        <div style="text-align: center;">
            <h3>' . $creditBalance . '</h3>
            <br />
            <a href="index.php?m=clientcreditview" class="btn btn-primary">Manage Credits</a>
        </div>
    ';

    $output = $primarySidebar->addChild('Credit Balance', ['order' => 1, 'icon' => 'fa-wallet']);
    $output->setBodyHtml($html);
    
}

add_hook('ClientAreaPrimaryNavbar', 1, 'clientcreditview_primary_navbar');
add_hook('ClientAreaPrimarySidebar', 2, 'clientcreditview_primary_sidebar');