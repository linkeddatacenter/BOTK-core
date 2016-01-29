<?php
namespace BOTK\Core\Representations;



/*
 * Supports the default content management behaviour for botk resource defining a
 * set of basic renderer (html,text,json,php) for generic data formats  
 * 
 * N.B redefine $priority to change the default rendering choice when  a request does not
 * specify a preferred media in content negotiation.
 */
class Standard extends AbstractContentNegotiationPolicy {
	    
    protected static $renderers = array(
        'application/json'          => 'jsonRenderer',
        //'application/xml'           => 'xmlStandardRenderer', 
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
        '<link rel="stylesheet" type="text/css" href="http://linkeddata.center/resources/v4/css/doc.css" />'
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
    public static function jsonRenderer($data) {
        static::setContentType('application/json');
        // in php 5.4 you can use JsonSerializable to customize json_encode 
        return json_encode($data);
    }


    /**
     * if data does not support __toHml,  serializes data structure using htmlSerializer(). 
     * If  use static::$htmlMetadata static array , use it to drive serializer.
     */
    public static function htmlRenderer($data) {
        static::setContentType('text/html');
        $isHtmlFragment=false;
		$metadata = array(
				'title'			=> null,
				'htmlMetadata'	=> static::$htmlMetadata,
				'header'		=> null,
				'footer'		=> null,
		);			
        if( is_object($data) ){
            if (method_exists($data,'__toHtml')) {
                $resourceState =  $data->__toHtml();
                $isHtmlFragment=true;
            } else {
                $resourceState = static::getResourceState($data);
            }
			if (method_exists($data,'__metadata')){
				$metadata= array_merge($metadata, $data->__metadata());
			} else {
				$metadata['title'] = get_class($data);
			}
        } else {
            $resourceState = $data;
        }
     
        return static::htmlSerializer( $resourceState, $metadata['htmlMetadata'], $metadata['title'], $metadata['header'], $metadata['footer'], $isHtmlFragment);
    }

    
    /**
     * Method, use textSerializer(). Nothe that __toString is used at serializin level
     * in this case.
     */
    public static function plaintextRenderer($data) {
        static::setContentType('text/plain');
        return (is_object($data) && method_exists($data,'__toString'))
            ? (string) $data
            : var_export(static::getResourceState($data), true);
    }     
     


    /*
     * This implement "code on demand" RESTful requirement for php
     */
    public static function serialphpRenderer($data) {
        static::setContentType('application/x-php');
        return serialize(static::getResourceState($data));
    }


    /*
     * This implement "code on demand" RESTful requirement for php
     */
    public static function phpRenderer($data) {
        static::setContentType('text/x-php');
        return var_export(static::getResourceState($data), true);
    }


    /**
     * ia very simple template engine to  reneder html using a custom php script. 
     * The template is in the file pointed by Standard::$htmlTemplate that defaults to 'templates/html_template.php'.
     */
    public static function htmlTemplateRenderer($data) {
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
    public static function xmlSerializer($data, array $processingInstructions=array(),$rootElement=null) {
        if (is_null($rootElement)) $rootElement = 'data';
        $encoder = new \Paranoiq\Xmlon\XmlonEncoder; // for xml serializing
        $encoder->addXmlHeader = false;
        return implode("\n",$processingInstructions)."\n".$encoder->encode($data,$rootElement);
    }  

}
