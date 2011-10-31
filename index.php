<?php

/**
 * Copyright 2011 Felix Ostrowski, hbz
 *
 * This file is part of PhresnelApp.
 *
 * PhresnelApp is free software: you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option)
 * any later version.
 *
 * PhresnelApp is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License
 * for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with PhresnelApp.  If not, see <http://www.gnu.org/licenses/>.
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');

require_once('lib/phresnel/Phresnel.php');
require_once('lib/web/web.php');
require_once('lib/KLogger/src/KLogger.php');

// Controller classes
require_once('controllers/GenericInclude.php');
require_once('controllers/GenericRedirect.php');
require_once('controllers/GenericDisplay.php');
require_once('controllers/GenericEdit.php');
require_once('controllers/GenericList.php');

require_once('app/PhresnelApp.php');

$log = KLogger::instance("/tmp");
Phresnel::init("file://" . dirname(__FILE__) . "/conf/conf.ttl", $log);

$app = new PhresnelApp();
$app->dispatch();
