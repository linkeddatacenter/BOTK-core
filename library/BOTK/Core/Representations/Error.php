<?php
namespace BOTK\Core\Representations;

use BOTK\Core\Http,
    BOTK\Core\WebLinks,
    BOTK\Core\Models\HttpProblem;


/*
 * Supports the default content management behaviour for botk resource defining a
 * set of basic renderer (html,text,json,php) for generic data formats  
 * 
 * N.B redefine $priority to change the default rendering choice when  a request does not
 * specify a preferred media in content negotiation.
 */
class Error extends Standard
{
    protected static $renderers = array(
        'application/api-problem+json'  => 'jsonErrorRenderer',
        'application/json'              => 'jsonErrorRenderer',
        'application/api-problem+xml'   => 'xmlErrorRenderer', 
        'application/xml'               => 'xmlErrorRenderer',
        'text/html'                     => 'htmlErrorRenderer',
        'application/x-php'             => 'serialphpRenderer',
        'text/x-php'                    => 'phpRenderer',
        'text/plain'                    => 'plaintextRenderer',
    );

    /**
     * Redefine json renderer specializing it for HttpProblem data model
     * 
     * @link https://tools.ietf.org/html/draft-nottingham-http-problem-04
     */
    public static function jsonErrorRenderer(HttpProblem $data)
    {
        static::setContentType('application/api-problem+json');
        header('Content-Language: en');
        // in php 5.4 you can use JsonSerializable to customize json_encode 
        return json_encode($data);
    }


    /**
     * Redefine xml serializer specializing it for HttpProblem data model
     * 
     * @link https://tools.ietf.org/html/draft-nottingham-http-problem-04
     */
    public static function xmlErrorRenderer(HttpProblem $error)
    {
        static::setContentType('application/api-problem+xml');
        
        $result = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $result.= "<problem>\n";
        if($error->problemInstance) $result.= "\t<describedBy>".htmlspecialchars($error->problemInstance)."</describedBy>\n";
        if($error->title)           $result.= "\t<title>".htmlspecialchars($error->title)."</title>\n";
        if($error->detail)          $result.= "\t<detail>".htmlspecialchars($error->detail)."</detail>\n";
        if($error->problemType)     $result.= "\t<supportId>".htmlspecialchars($error->problemType)."</supportId>\n";
        if($error->httpStatus)      $result.= "\t<httpStatus>$error->httpStatus</httpStatus>\n";
        $result.= "</problem>\n";
        
        return $result;
    }
    
    /**
     * Redefine html serializer specializing it for HttpProblem data model
     */
    public static function htmlErrorRenderer(HttpProblem $error)
    {
        static::setContentType('Content-Type: text/html');
        // Reuse standard html serializer default management
        $metadata = static::$htmlMetadata;
        if (is_string($metadata)){
            $metadata = array("<link rel='stylesheet' type='text/css' href='$metadata'/>");
        } 
        
        $head = implode("\n",$metadata);
        $title =htmlspecialchars("$error->httpStatus $error->title");
        if($error->problemInstance) {
            $pi = htmlspecialchars($error->problemInstance);
            $problemInstance = "<a href='$pi'>$pi</a>";
        } else  {
            $problemInstance = 'unknown';
        }

        if($error->problemType) {
            $pt = htmlspecialchars($error->problemType);
            $problemType = "<a href='$pt'>$pt</a>";
        } else {
            $problemType = 'unknown';
        }
        return "<!DOCTYPE html>
<html>
    <head>
        <title>$title</title>
        $head
    </head>
    <body>
        <header><h1>$title</h1></header>
        <main><details>
            <summary>More info</summary>
            <dl>
                <dt>Details:</dt><dd><pre>".htmlspecialchars($error->detail)."</pre></dd>
                <dt>See also:</dt><dd>$problemInstance</dd>
                <dt>Error type:</dt><dd>$problemType</dd>
            </dl>
        </details><main>
    </body>
</html>
";
    }

}
