<?php
/**
 * see sample doc in index.html
 */
$loader = require '../vendor/autoload.php';
$loader->add('mylibrary\\', __DIR__);    // local end-points inclusion by composer autoloder


echo BOTK\Core\EndPointFactory::make('\\mylibrary\\HelloEndPoint\\Router')->run();

