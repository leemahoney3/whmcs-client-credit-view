<?php

namespace LMTech\Addons\ClientCreditView\Config;

use WHMCS\Module\Addon\Setting;

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

class Config {

    public static function populate() {
        
        return [
            'name'          => 'Client Credit View',
            'description'   => 'Allows clients to view their credit balance as well as previous credit transactions on their account.',
            'version'       => '1.0.0',
            'author'        => '<a href="https://leemahoney.dev">Lee Mahoney</a>',
            'fields'        => [
                'showInSidebar'         => [
                    'FriendlyName'  => 'Show Sidebar Widget',
                    'Description'   => 'Shows the clients credit in the sidebar on the main client area page',
                    'Type'          => 'yesno',
                    'Default'       => 'yes',
                ],
                'showInNav'             => [
                    'FriendlyName'  => 'Show Billing Menu Entry',
                    'Description'   => 'Shows a link to the module in the Billing dropdown menu in the client area',
                    'Type'          => 'yesno',
                    'Default'       => 'yes',
                ],
                'enablePagination'      => [
                    'FriendlyName'  => 'Enable Pagination',
                    'Description'   => 'Paginate the list of credit history entries shown in the client area module (Recommended)',
                    'Type'          => 'yesno',
                    'Default'       => 'yes',
                ],
                'paginationLimit'       => [
                    'FriendlyName'  => 'Pagination Limit',
                    'Description'   => 'How many items to show on each page',
                    'Type'          => 'text',
                    'Size'          => '25',
                    'Default'       => '10',
                ],
                'sortOrder'             => [
                    'FriendlyName'  => 'Sort Order',
                    'Description'   => 'Whether to sort the credit history list in ascending or descending order',
                    'Type'          => 'dropdown',
                    'Options'           => [
                        'desc'       => 'Descending',
                        'asc'      => 'Ascending',
                    ],
                    'Default'       => 'desc',
                ],
                'sortField' => [
                    'FriendlyName'  => 'Sort Field',
                    'Description'   => 'Database field you wish to sort by',
                    'Type'          => 'dropdown',
                    'Options'           => [
                        'id'        => 'ID',
                        'date'      => 'Date',
                        'amount'    => 'Amount',
                    ],
                    'Default'       => 'date',
                ]
            ],
        ];
    
    }

    public static function get($setting) {

        return Setting::where('module', 'clientcreditview')->where('setting', $setting)->value('value');

    }

}