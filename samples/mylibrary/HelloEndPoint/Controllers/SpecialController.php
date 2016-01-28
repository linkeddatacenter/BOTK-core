<?php
namespace mylibrary\HelloEndPoint\Controllers;

use BOTK\Core\Controller;

final class SpecialController extends Controller
{
    public function get($to = 'World', $andTo='')
    {
        $result ="SpecialController says a special hello to $to";
        if($andTo){
            $result = $result  . " and to $andTo";
        }
        return $result.'!!!';
    }
}
