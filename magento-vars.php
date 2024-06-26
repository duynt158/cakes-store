<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Enable, adjust and copy this code for each store you run
 *
 * Store #0, default one
 *
 * if (isHttpHost("example.com")) {
 *    $_SERVER["MAGE_RUN_CODE"] = "default";
 *    $_SERVER["MAGE_RUN_TYPE"] = "store";
 * }
 *
 * @param string $host
 * @return bool
 */
// function isHttpHost(string $host)
// {
//     if (!isset($_SERVER['HTTP_HOST'])) {
//         return false;
//     }
//     return $_SERVER['HTTP_HOST'] === $host;
// }


function isHttpHost($host)
{
    if (!isset($_SERVER['HTTP_HOST'])) {
        return false;
    }
    return $_SERVER['HTTP_HOST'] ===  $host;
}

if (isHttpHost("ipad.mcstaging.cakebox.com")) {
    $_SERVER["MAGE_RUN_CODE"] = "ipad";
    $_SERVER["MAGE_RUN_TYPE"] = "website";
} elseif (isHttpHost("mcstaging.cakebox.com")) {
    $_SERVER["MAGE_RUN_CODE"] = "base";
    $_SERVER["MAGE_RUN_TYPE"] = "website";
} elseif (isHttpHost("ipad.cakebox.com")) {
    $_SERVER["MAGE_RUN_CODE"] = "ipad";
    $_SERVER["MAGE_RUN_TYPE"] = "website";
} elseif (isHttpHost("www.cakebox.com")) {
    $_SERVER["MAGE_RUN_CODE"] = "base";
    $_SERVER["MAGE_RUN_TYPE"] = "website";
}
