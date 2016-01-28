<?php
namespace BOTK\Core\Representations;

use Bitworking\Mimeparse;
use BOTK\Core\WebLink;

/*
 * Exposes an index function that returns an array ('mime-type'=> rendering
 * function)
 * that states available representation (i.e. rendering processess) for a data
 * resource.
 *
 * Renderers functions must be declared as static
 *
 * You can delete, override dafault methods and/or adding new ones
 * just deriving a new class and defining the setIndex function.
 *
 * Index order allows determine the priority when request does not specify a
 * preferred
 * content negotiation.
 */
abstract class AbstractContentNegotiationPolicy
{
   // redefine this to change the list of supported response representation
    protected static $renderers = array();
    protected static $parsers = array();
    protected static $translators = array();
    
    /**
     * Remove from index uncallable functions after normalizing them
     *
     */
    protected static function getPolicies($index)
    {
        $result = array();
        $thisClassName= get_called_class(); // late binging from PHP > 5.3
        foreach ($index as $key=>$function) {
            if (!is_callable($function)) {
                // use late binding classname scope scope
                $function = array($thisClassName, $function);
            }
            // try again to see if now is callable...
            if (is_callable($function)) $result[$key] = $function;
        }

        return $result;
    }


    public static function renderers()
    {
        return static::getPolicies(static::$renderers);
    }


    public static function parsers()
    {
        return static::getPolicies(static::$parsers);
    }


    public static function translators()
    {
        return  static::getPolicies(static::$translators);
    }


    /**
     * This helper is called when you need directly rendering a data structure
     * according http accept header request.
     * It is used in error management to render errors.
     */
    public static function render($data, array $renderers)
    {
        $header = array_key_exists('HTTP_ACCEPT', $_SERVER)?$_SERVER['HTTP_ACCEPT']:false;

        $best_match = $header?Mimeparse::bestMatch(array_keys($renderers), $header):reset($renderers);

        $renderer = $best_match?$renderers[$best_match]:'json_encode';
        //fallback to json
        return call_user_func($renderer, $data);
    }


    /*************************************************************************
     *  Helpers
     *************************************************************************/

    /**
     * An helper to extract from data just visible variables
     */
    public static function getResourceState($data)
    {
        return is_object($data)?get_object_vars($data):$data;
    }


    /**
     * An helper to setup content type and alternate web link headers
     */
    public static function setContentType($contentType, $policyClass = null)
    {
        if (is_null($policyClass)) $policyClass = get_called_class();
        header('Content-Type: ' . $contentType);
        $selfUri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
        if ($selfUri && is_subclass_of($policyClass, __CLASS__)) {
            $altenatives = array_keys($policyClass::$renderers);
            header(WebLink::factory($selfUri)->rel('self')->httpSerializer(),false);
            foreach ($altenatives as $type) {
                if ($contentType != $type) {
                    header(WebLink::factory($selfUri)->rel('alternate')->type($type)->httpSerializer(),false);
                }
            }
        }
    }


}
