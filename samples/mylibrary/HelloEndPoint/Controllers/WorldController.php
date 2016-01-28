<?php
namespace mylibrary\HelloEndPoint\Controllers;

use BOTK\Core\Controller;

final class WorldController extends Controller
{
    public function get($to = 'World')
    {
        return "WorldController says hello $to!";
    }


    public function post($to = 'World')
    {
        return "WorldController says hello $to! Thanks to posting me";
    }


    public function put($to = 'World')
    {
        return "WorldController says hello $to! Thanks to putting me";
    }


    public function delete($to = 'World')
    {
        return "WorldController says hello $to! Thanks to deleting  me";
    }


    public function head($to = 'World')
    {
        return "WorldController says hello $to! Thanks to heading  me";
    }


    public function options($to = 'World')
    {
        return "WorldController says hello $to! Thanks to optioning  me";
    }

}
