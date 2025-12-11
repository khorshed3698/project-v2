<?php

use Carbon\Carbon;

function getDocUniqueId($length)
{
    if (function_exists('random_bytes')) {
        $bytes = random_bytes(ceil($length/2));
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(ceil($length/2));
    } else {
        $bytes = uniqid();
    }

    return strtoupper(substr(bin2hex($bytes), 0, $length));
}

function getPdfFilePath($modulePrefix, $app_id)
{
    $directory = 'certificate/';
    $directoryYear = $directory . date('Y');
    $directoryYearMonth = $directory . date('Y/m');

    if (!file_exists($directoryYearMonth)) {

        mkdir($directoryYearMonth, 0777, true);
        $directoryYearMonthIndex = fopen($directoryYearMonth . '/index.html', 'w') or die('Cannot create file.');
        fclose($directoryYearMonthIndex);

        if (!file_exists($directoryYear.'/index.html')) {
            $directoryYearIndex = fopen($directoryYear . '/index.html', 'w') or die('Cannot create file.');
            fclose($directoryYearIndex);

            if (!file_exists($directory.'/index.html')) {
                $directoryIndex = fopen($directory . '/index.html', 'w') or die('Cannot create file.');
                fclose($directoryIndex);
            }
        }
    }

    return $directoryYearMonth . "/" . $modulePrefix . '_' . Carbon::now()->timestamp . '_' . \App\Libraries\Encryption::encodeId($app_id) . '.pdf';
}

