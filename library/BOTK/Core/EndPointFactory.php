<?php
namespace BOTK\Core;

use BOTK\Core\Models\HttpProblem;

class EndPointFactory
{
    /**
     * Create and Endpoint and setup auto virtualhost
     */
    public static function make($endPointClass=null,$virtualhost=null)
    {
        $baseEndPointClassName = '\\BOTK\\Core\\EndPoint';
        if (!$endPointClass) $endPointClass = $baseEndPointClassName;

        if($endPointClass!=$baseEndPointClassName
            && !is_subclass_of($endPointClass, $baseEndPointClassName)){
            throw new HttpErrorException( HttpProblem::factory( 
                500, 'Unable to create endpoint', "$endPointClass is not and EndPoint class"));
        }
        
        return new $endPointClass($virtualhost);
    }
    
    public static function factory($virtualhost=null)
    {
        return new static($virtualhost);
    }
}
