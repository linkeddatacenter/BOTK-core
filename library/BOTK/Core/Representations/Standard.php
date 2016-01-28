<?php
namespace BOTK\Core\Representations;

use BOTK\Core\Http;
use BOTK\Core\WebLinks;


/*
 * Supports the default content management behaviour for botk resource defining a
 * set of basic renderer (html,text,json,php) for generic data formats  
 * 
 * N.B redefine $priority to change the default rendering choice when  a request does not
 * specify a preferred media in content negotiation.
 */
class Standard extends AbstractContentNegotiationPolicy
{    
    protected static $renderers = array(
        'application/json'          => 'jsonRenderer',
        'application/xml'           => 'xmlStandardRenderer', 
        'text/html'                 => 'htmlRenderer',
        'application/x-php'         => 'serialphpRenderer',
        'text/x-php'                => 'phpRenderer',
        'text/plain'                => 'plaintextRenderer',
    );
    
    protected static $parsers = array(
        'application/json'          => 'jsonParser',
        'application/standard+xml'  => 'xmlParser', 
        'application/x-php'         => 'serialphpParser',
    ); 

    /**
     * Allow to personalize xml header in xmlStandardRenderer
     */
    public static $xmlProcessingInstruction = array(
        '<?xml version="1.0" encoding="UTF8"?>',
    );
     
    /**
     * Allow htmlRenderer to personalize html headers
     */
    public static $htmlMetadata = array(
        '<link rel="stylesheet" type="text/css" href="http://ontology.it/tools/bofw/v4/css/doc.css" />'
    );

    /**
     * If using htmlTemplateRenderer allow you to define the template path
     */
    public static $htmlTemplate = 'templates/html_template.php';

    
    

    /*************************************************************************
     *  Standard Renderers
     *************************************************************************/

    /** 
     * if data does not support saveJSON,  serializes data structure using 
     * json_serialize() standard php function
     */
    public static function jsonRenderer($data)
    {
        static::setContentType('application/json');
        // in php 5.4 you can use JsonSerializable to customize json_encode 
        return json_encode($data);
    }


    /**
     * if data does not support saveXML, create a valid xml using xmlSerializer(). 
     * If defined  static::$xmlCSS and static::$xmlXSLT vars, use them  to drive serializer. 
     */
    public static function xmlStandardRenderer($data)
    {
        static::setContentType('application/standard+xml');
        return static::xmlSerializer(static::getResourceState($data), static::$xmlProcessingInstruction);
    } 


    /**
     * if data does not support __toHml,  serializes data structure using htmlSerializer(). 
     * If  use static::$htmlMetadata static array , use it to drive serializer.
     */
    public static function htmlRenderer($data)
    {
        static::setContentType('text/html');
        $isHtmlFragment=false;
        if( is_object($data) ){
            if (method_exists($data,'__toHtml')) {
                $resourceState =  $data->__toHtml();
                $isHtmlFragment=true;
            } else {
                $resourceState = static::getResourceState($data);
            }
            $title = get_class($data);
        } else {
            $resourceState = $data;
            $title = gettype($data);
        }
        
        return static::htmlSerializer( $resourceState, static::$htmlMetadata, $title, null, null, $isHtmlFragment);
    }

    
    /**
     * Method, use textSerializer(). Nothe that __toString is used at serializin level
     * in this case.
     */
    public static function plaintextRenderer($data)
    {
        static::setContentType('text/plain');
        return (is_object($data) && method_exists($data,'__toString'))
            ? (string) $data
            : var_export(static::getResourceState($data), true);
    }     
     


    /*
     * This implement "code on demand" RESTful requirement for php
     */
    public static function serialphpRenderer($data)
    {
        static::setContentType('application/x-php');
        return serialize(static::getResourceState($data));
    }


    /*
     * This implement "code on demand" RESTful requirement for php
     */
    public static function phpRenderer($data)
    {
        static::setContentType('text/x-php');
        return var_export(static::getResourceState($data), true);
    }


    /**
     * ia very simple template engine to  reneder html using a custom php script. 
     * The template is in the file pointed by Standard::$htmlTemplate that defaults to 'templates/html_template.php'.
     */
    public static function htmlTemplateRenderer($data)
    {
        static::setContentType('text/html');
        return static::templateSerializer($data, static::$htmlTemplate);
    }



    /*************************************************************************
     *  Standard Serializers
     *************************************************************************/

     
     
    /**
     * Serialize data structure using Xmlon class.
     * 
     */
    public static function xmlSerializer($data, array $processingInstructions=array(),$rootElement=null)
    {
        if (is_null($rootElement)) $rootElement = 'data';
        $encoder = new \Paranoiq\Xmlon\XmlonEncoder; // for xml serializing
        $encoder->addXmlHeader = false;
        return implode("\n",$processingInstructions)."\n".$encoder->encode($data,$rootElement);
    }
 

    /**
     * Serializ an Web link in htm head section
     * 
     * @link http://datatracker.ietf.org/doc/rfc4287 chapter 4.2.7
     */
    public static function htmlWebLinkSerializer(\BOTK\Core\WebLink $link)
    {
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
    public static function htmlWebLinks(array $links)
    {
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
            $type  = null,         
            $header = null,
            $footer = null,
            $dataIsHtmlFragment=null)
    {
        //set defaults
        if (is_null($meta)) {$meta=array();}
        if (is_null($type)) {$type= is_object($data)?get_class($data):gettype($data);}
        if (is_null($header)) {$header="<h1>$type</h1>";}
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
        <title>$type response</title>
        $head
    <style type='text/css'>
        main {
            font-family: monospace;
            white-space: pre;
        }
    </style>
    </head>
    <body>
        <header>
            $header
            <nav>
               <div id=botkHyperlinks>
                $navLinks
               </div>
            <nav>
        </header>
<!-- main tag contains a php html encoded representation of public data --> 
<!-- the class property contains the name of the data model from whom public data are extracted -->       
<main class='$type'>$htmlDataRepresentation</main>
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
    public static function templateSerializer($data, $template)
    {
        if(ob_start()){
            @require $template;
            $result = ob_get_contents();
            ob_end_clean();
        }
        return $result;
    }    

}
