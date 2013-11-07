<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();

date_default_timezone_set('Europe/Amsterdam');


define('DOC_ROOT',         __DIR__);
define('APP_PATH',         DOC_ROOT.'/app');
define('CONTROLLERS_PATH', APP_PATH.'/controllers');
define('MODELS_PATH',      APP_PATH.'/models');
define('VIEWS_PATH',       APP_PATH.'/views');

define('LIB_PATH',         DOC_ROOT.'/lib');
define('APPLIB_PATH',      APP_PATH.'/lib');


require APP_PATH.'/config/config.php';
require APP_PATH.'/config/routing.php';

require LIB_PATH.'/functions.php';

require DOC_ROOT.'/core/Model.php';
require DOC_ROOT.'/core/View.php';
require DOC_ROOT.'/core/Controller.php';
require DOC_ROOT.'/core/ErrorHandler.php';
require DOC_ROOT.'/core/AutoLoader.php';
require DOC_ROOT.'/core/Dispatcher.php';


$DB = new PDO('mysql:dbname='.$DBData.';host='.$DBHost, $DBUser, $DBPass,
         array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$autoLoader = new AutoLoader();
$autoLoader->registerType(MODELS_PATH, 'Model');
$autoLoader->registerType(CONTROLLERS_PATH, 'Controller');
$autoLoader->register();


$errorHandler = new ErrorHandler();
$errorHandler->registerHandlers();


$dispatcher = new Dispatcher($DB, $Routing);
$dispatcher->dispatch();
