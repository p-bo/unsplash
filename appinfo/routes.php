<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\AppInfo;

$application = new Application();
/** @var $this \OC\Route\CachingRouter */
$application->registerRoutes($this, [
    'routes'    => [
        ['name' => 'admin_settings#set', 'url' => '/settings/admin/set', 'verb' => 'POST'],
        ['name' => 'personal_settings#set', 'url' => '/settings/personal/set', 'verb' => 'POST'],
        ['name' => 'Image#getCurentBackgroundImage', 'url' => '/getCurrentImage', 'verb' => 'GET'],
        ['name' => 'Css#getLoginCss', 'url' => '/login.css', 'verb' => 'GET'],
        ['name' => 'Css#getHeaderCss', 'url' => '/header.css', 'verb' => 'GET'],
    ]
]);