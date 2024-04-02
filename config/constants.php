<?php
/**
 *
 * MLS Script
 *
 */

define('__MLSC_HOST__', 'http://lights.lan');
define('DEV_SYSTEM_URL', __MLSC_HOST__.'/api/system/devices');
define('DEV_SETTINGS_URL', __MLSC_HOST__.'/api/settings/device');
define('DEV_UDP_URL', __MLSC_HOST__.'/api/settings/device/output-type');
define('DEV_EFFECT_URL', __MLSC_HOST__.'/api/settings/effect');
define('DEV_ACTIVE_EFFECT', __MLSC_HOST__.'/api/effect/active');
define('DEV_EFFECTS_LIST', __MLSC_HOST__.'/api/resources/effects');
define('DEV_RESOURCE_COLORS', __MLSC_HOST__.'/api/resources/colors');
define('DEV_RESOURCE_GRADIENTS', __MLSC_HOST__.'/api/resources/gradients');

const PHP_DBL                   = \PHP_EOL.\PHP_EOL;
