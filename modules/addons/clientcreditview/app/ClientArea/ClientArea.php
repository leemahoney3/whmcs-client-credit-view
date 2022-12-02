<?php

namespace LMTech\Addons\ClientCreditView\ClientArea;

use LMTech\Addons\ClientCreditView\Config\Config;
use LMTech\Addons\ClientCreditView\Models\Credit;
use LMTech\Addons\ClientCreditView\Helpers\PaginationHelper;

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

class ClientArea {

    public static function output($vars) {

        $links              = null;
        $clientId           = (int) $_SESSION['uid'];
        $currencyData       = getCurrency($clientId);
        
        $credits            = Credit::where('clientid', $clientId)->orderBy('date', 'desc')->get();
        $totalCredit        = formatCurrency($credits->where('amount', '>', '0')->sum('amount'), $currencyData['id']);
        $usedCredit         = formatCurrency(abs($credits->where('amount', '<', '0')->sum('amount')), $currencyData['id']);
        $remainingCredit    = formatCurrency($credits->sum('amount'), $currencyData['id']);

        if (Config::get('enablePagination')) {

            $getCredits = new PaginationHelper('p', ['clientid' => $clientId], Config::get('paginationLimit') ?: 1, Credit::class, [Config::get('sortField'), Config::get('sortOrder')]);
            $allCredits = $getCredits->data();
            $links      = $getCredits->links();

        } else {
            
            $allCredits = Credit::where('clientid', $clientId)->orderBy(Config::get('sortField'), Config::get('sortOrder'))->get();
            
        }

        return [
            'pagetitle'     => 'Account Credit',
            'breadcrumb'    => ['index.php?m=clientcreditview'=>'Account Credit'],
            'templatefile'  => 'clientarea',
            'requirelogin'  => true,
            'forcessl'      => false,
            'vars'          => [
                'currency'          => $currencyData['prefix'],
                'credits'           => $allCredits,
                'links'             => $links,
                'totalCredit'       => $totalCredit,
                'usedCredit'        => $usedCredit,
                'remainingCredit'   => $remainingCredit,
            ],
        ];

    }

}
