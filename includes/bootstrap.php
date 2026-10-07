<?php

define('SMARTWILLS_APP_ROOT', dirname(__DIR__));

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function smartwills_require_login(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        exit;
    }
}

function smartwills_asset_base(): string
{
    static $assetBase = null;

    if ($assetBase !== null) {
        return $assetBase;
    }

    $assetBase = '/assets';

    if (isset($_SERVER['SCRIPT_NAME'])) {
        $currentScript = $_SERVER['SCRIPT_NAME'];
        if (str_contains($currentScript, '/clients/')) {
            $assetBase = '../assets';
        } elseif (str_contains($currentScript, '/education/')) {
            $assetBase = '../assets';
        }
    }

    return $assetBase;
}

function smartwills_page_meta(string $page, string $title, array $styles = [], array $scripts = []): array
{
    return [
        'activePage' => $page,
        'pageTitle' => $title,
        'pageStyles' => $styles,
        'pageScripts' => $scripts,
        'assetBase' => smartwills_asset_base(),
    ];
}
