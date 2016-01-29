<?php
namespace BOTK\Core\Representations;

use Bitworking\Mimeparse;
use BOTK\Core\WebLink;
use BOTK\Core\Http;


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


    /*************************************************************************
     *  Default Serializers
     *************************************************************************/


    /**
     * Serializ an Web link in htm head section
     * 
     * @link http://datatracker.ietf.org/doc/rfc4287 chapter 4.2.7
     */
    public static function htmlWebLinkSerializer(\BOTK\Core\WebLink $link) {
        $tag = '<link href="'.$link->href.'"';
        foreach (array('rel', 'type','hreflang') as $attribute) {
            if( $value = $link->$attribute) $tag .= " $attribute='$value'";
        }
        $tag .= ' />'; 
        
        return $tag;       
    }


    /**
     * Helper to render a nice view of Web link as html fragment
     * 
     */
    public static function htmlWebLinks(array $links) {
        $html = "<dl>\n";
         $alternateLinks = array();
         foreach($links as $link ){
            $linkName = $link->rel?$link->rel:'Link';
            $href=$link->href;
            if(0===strcasecmp($linkName,'alternate')){
                $type = $link->type?$link->type:'unspecfified type';
                // manage antipatter to make alternate format ccallable from browser
                $glue=(false===strpos($href, '?'))?'?':'&amp';
                $forceOutput=$glue.'_output='.urlencode($type);
                $alternateLinks[] = "<a href='$href{$forceOutput}'>$type</a> ";
            } else {
                $html .= "<dt>$linkName:</dt><dd><a href='$href'>$href</a></dd>\n";
            }
         }
         if (count($alternateLinks)){
            $html .= "<dt>alternate:</dt><dd>".implode(', ',$alternateLinks)."</dd>\n"; 
         }
        $html .= "</dl>\n";
        
        return $html;
    }
    
    
    /**
     * if $useCustomFields use an xml representation for fields.. Not yet standarized in html5
     * 
     * This render use html5 semantic tagging.
     */
    public static function htmlSerializer($data, 
            $meta = null,
            $title  = null,         
            $header = null,
            $footer = null,
            $dataIsHtmlFragment=null) {
        //set defaults
        if (is_null($meta)) {$meta=array();}
        if (is_null($title)) {$title= is_object($data)?get_class($data):gettype($data);}
        if (is_null($header)) {$header="<h1>$title</h1>";}
        if (is_null($footer)) {$footer='';}
        if (is_null($dataIsHtmlFragment)) {$dataIsHtmlFragment=false;}
         
        // initilalize metadata
        
        $metadata = array();       
        if (is_string($meta) && ($meta=trim($meta))){
            $metadata[] = "<link rel='stylesheet' type='text/css' href='$meta'/>";
        } elseif( is_array($metadata)){
            $metadata = $meta;
        }
         
               
        // prepare hyperlinks
        $links = Http::getResponseLinks();
        foreach ($links as $link) {
            $metadata[] = self::htmlWebLinkSerializer($link);
        }
        $navLinks = empty($links)?'':static::htmlWebLinks($links);
        
        // prepare head    
        $head = implode("\n",$metadata);
            
       // Prepare data content
        $text = is_scalar($data)
            ? ((string)$data)
            : var_export($data, true);          
        $htmlDataRepresentation = $dataIsHtmlFragment?$text:htmlspecialchars($text);
         
        return is_null($meta)  // if meta is null render just data as html fragment
            ?  $htmlDataRepresentation
            : ( "<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>$title</title>
        $head
    </head>
    <body>
        <header>
            $header
            <nav>
               <div id=botkHyperlinks>
                $navLinks
               </div>
            </nav>
        </header>
<!-- main tag contains a php html encoded representation of public data --> 
<!-- the class property contains the name of the data model from whom public data are extracted -->       
$htmlDataRepresentation
        <footer>
            $footer
        </footer>
    </body>
</html>
"
            );
    }


    /**
     * an super simple template engine!
     * 
     */
    public static function templateSerializer($data, $template) {
        if(ob_start()){
            @require $template;
            $result = ob_get_contents();
            ob_end_clean();
        }
        return true;
    }    

}
