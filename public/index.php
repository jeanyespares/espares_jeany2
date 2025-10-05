<?php
// Bootstrap proxy for environments that set the document root to /public
// This file simply forwards execution to the project's front controller.
$root = dirname(__DIR__);
// prevent accidental direct access guards in included files
if (!defined('PREVENT_DIRECT_ACCESS')) define('PREVENT_DIRECT_ACCESS', true);
require $root . '/index.php';
