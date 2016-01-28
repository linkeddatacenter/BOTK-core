<?php 
$loader =require '../vendor/autoload.php';

use BOTK\Core\EndPoint;
final class EndPointStub extends EndPoint 
{
    protected function setRoutes()
    {
        $this->get('/', 'root');
        $this->get('/testpath', 'path');
        $this->get('/testpar/*', function ($par1) {
            return $par1;
        });
    }
}

date_default_timezone_set('UTC');
