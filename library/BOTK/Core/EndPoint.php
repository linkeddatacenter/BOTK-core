<?php
namespace BOTK\Core;

use \InvalidArgumentException;
use Respect\Rest\Router,
    Respect\Rest\Request;
use BOTK\Core\Models\HttpProblem,
    BOTK\Core\Representations\Error;



/**
 * This class extends Respect\Rest\Router just changing some  defaults and adding
 * an antipattern to override accept methods from  URL request
 * Include the apiDoc structure BOTK and the Permission authBasic.
 * See http://apidocjs.com/ for more info.
 */
  
/**
 * @apiDefineStructure BOTK
 *
 * @apiDescription 
 * Supports HTTP content negotiation for html, json, xml, txt representation.
 * Error representation is based on [ietf specs](http://tools.ietf.org/html/draft-nottingham-http-problem-04).
 * 
 * @apiParam {Url encoded Mime type} [_output] allow overriding of content negotiation i.e. `_output=application%2Fjson`
 * 
 * 
 * @apiError problemType An absolute URI that identifies the problem type.
 * @apiError title Human-readable summary of the problem.
 * @apiError detail AAn human readable explanation specific to this occurrence of the problem.
 * @apiError problemInstance An absolute URI that identifies this occurrence of the problem.
 * @apiError httpStatus The HTTP status code ([RFC2616], Section 6)
 * 
 * @apiErrorExample Error 404 example 
 *	{
 *		problemType: "http://dbpedia.org/resource/Category:HTTP_status_codes"
 *		title: "Resource not found"
 *		detail: "Resource report 5441392469671 not found."
 *		problemInstance: "http://dbpedia.org/resource/HTTP_404"
 *		httpStatus: 404
 *	}
 */
 
/**
 * @apiDefinePermission authBasic Access rights needed. 
 * You need to provide credential to use this api (http Basic autentication).
 */

class EndPoint extends Router
{
    protected $endPointCatalog=array();
    protected $endPointPath=null;   //lazy initialization
    protected $endPointName=null;    
        
     // Hooks to build resusable end-poins
    protected function setRoutes() {}

    
    //this allow to reuse other endpoints routing logic
    public function mountEndPoint($name, $endPointClassName)
    {
        $this->endPointCatalog[$name] = $endPointClassName;
        
        return $this;
    }


    /*
     * EndPointPath path is the portion of an URI Request that prefix the End Point Name
     * This can be auto detected even if URL rewite occurs.
     */
    public function getEndPointPath()
    {
        // lazy creation
        if (is_null($this->endPointPath)) {
            // ----------------------------------------
            // Auto Detect End Application Path
            // ----------------------------------------
            $scriptName = $_SERVER['SCRIPT_NAME'];
            $scriptDir = dirname($scriptName);
            $requestURI = $_SERVER['REQUEST_URI'];
            if (0 === strpos($requestURI, $scriptName)) {
                // No url rewriting:
                // $_SERVER['REQUEST_URI']:     /service/index.php/hello/to/world?query_string#fragment
                // $_SERVER['SCRIPT_NAME']:     /service/index.php
                //
                // $endPointPath:            /service/index.php
                $endPointPath = $scriptName;
            } elseif (0 === strpos($requestURI, $scriptDir)) {
                // Simple url rewiting occurred:
                // $_SERVER['REQUEST_URI']:     /service/hello/to/world?query_string#fragment
                // $_SERVER['SCRIPT_NAME']:     /service/index.php
                //
                // $endPointPath:            /service
                $endPointPath = dirname($scriptName);
           } elseif (!empty($_SERVER['PATH_INFO']) && $pos = strpos($requestURI,$_SERVER['PATH_INFO']))  {
                // Complex rewriting roule. Try extract application path using PATH_INFO variable
                // $_SERVER['REQUEST_URI']:     /service/v1/hello/to/world?query_string#fragment
                // $_SERVER['SCRIPT_NAME']:     /serviceV1/index.php
                // $_SERVER['PATH_INFO']:       /hello/to/world
                //
                // $endPointPath:            /service/v1
                $endPointPath = substr($requestURI,0,$pos);
            } else {
                throw new HttpErrorException( HttpProblem::factory(
                    404,'End-point not found',"Unable autodetecting application path: URI=$requestURI."));
            }
            $this->endPointPath = $endPointPath;
        }
        return $this->endPointPath;
    }


    /*
     * An End Point Name is a portion of a http Resource request that identify a router
     */
    public function getEndPointName()
    {
        // lazy creation
        if (is_null($this->endPointName)) {
            $endPointPath = $this->getEndPointPath();
            $pathinfo = substr($_SERVER['REQUEST_URI'], strlen($endPointPath));
            $haystack = $pathinfo . '/';
            $this->endPointName = preg_match('@/([a-z0-9]+)[/?#]@', $haystack, $matches) 
                ? $matches[1] 
                : '';
        }
        return $this->endPointName;
    }
    
   

    public function __construct($virtualhost = null)
    {
        if (is_null($virtualhost)) $virtualhost=$this->getEndPointPath();
        
        // Anti-Pattens
        // allow ?_output=MIMETYPE on the URI to override HTTP accept header requests.
        if (isset($_REQUEST['_output'])) {
            $_SERVER['HTTP_ACCEPT'] = $_REQUEST['_output'];
        }
        
        parent::__construct($virtualhost);

        // Hooks to create routes
        $this->setRoutes();
        // set botk defaults: you can'nt modyfi this
        $this->methodOverriding = true;
        $this->isAutoDispatched = false;
 
        return $this;
    }


    /**
     * Redefine run taking into account mountend end points
     */
    public function run(Request $request=null)
    {
        $endPointName = $this->getEndPointName();
        $applicationPath = $this->getEndPointPath(); 
        
        $routerClass = ($endPointName && isset($this->endPointCatalog[$endPointName]))
            ? $this->endPointCatalog[$endPointName]
            : ''; 

        $virtualhost = empty($endPointName)?$applicationPath:($applicationPath.'/'.$endPointName);
       
        if ( !$routerClass ) {
            $result = parent::run($request); //fall back to local application routing
        } else {
            // now we test that $routerclass is a valid end_point
            $myClass = get_class();
            if ($routerClass ==$myClass || is_subclass_of($routerClass, $myClass)){
                // Create new end-point
                $endpoint = new $routerClass($virtualhost);
                $result = $endpoint->run();
            } else {
                throw new HttpErrorException(HttpProblem::factory( 
                    500, 'Invalid endpoint', $routerClass.' end point class is not a subClass of '. $myClass));                
            }
        }
        
        //now prepare an error report for humans when something went wrong
        //Warning this trigger is called only in php >5.4. Otherwhise just empty content is printed
        //(but original status is preserved)
        if (empty($result) && ($errorCode=Http::getHttpResponseCode()) >= 400) {
           $result = ErrorManager::getInstance()->serializeHttpProblem(new HttpProblem($errorCode));              
        }
        return $result;
    }

    /*************************************************************************
     * Caching control. 
     * 
     * use this to allow  "resource representation caching", both at rute level:
     * $route->through($this->representationCachingProcessor())
     * or at endpoint level:entation caching
     * $this->always('through',$this->representationCachingProcessor())
     * 
     * See allo cachin at controller level (i.e. caching at resource level)
     *************************************************************************/
     
    public function representationCachingProcessor($method=Caching::NO)
    {
        $cachingClass = '\\BOTK\\Core\\Caching';
        
        if ( !method_exists($cachingClass, $method)) $method =Caching::NO; 
        return array( $cachingClass, $method);
    }

}
