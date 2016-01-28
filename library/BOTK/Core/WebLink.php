<?php
namespace BOTK\Core;
/**
 * a Web link representation
 * 
 * @link http://tools.ietf.org/html/rfc5988
 */
 
class WebLink
{
     // Consider thes as readonly vars
     public $href='', 
            $rel=null,
            $type=null,
            $title=null,
            $hreflang=null,
            $media;
     
     public static function factory($href=null)
     {
         if (is_null($href)) $href = '';
         $link = new static();
         
         return $link->href($href);
     }
     
     public function href($href)
     {
         $this->href = $href;
         return $this;
     }
     
     public function rel($rel)
     {
         $this->rel = $rel;
         return $this;
     }
     
     public function type($type)
     {
         $this->type = $type;
         return $this;
     }    
     
     public function title($title)
     {
         $this->title = $title;
         return $this;
     } 
     
     public function media($media)
     {
         $this->media = $media;
         return $this;
     } 
     
     public function hreflang($hreflang)
     {
         $this->hreflang = $hreflang;
         return $this;
     } 
     
     /**
      * Link serialization for http header
      */           
     private function getTargetAttributes()
     {
         $fields = get_object_vars($this);
         unset($fields['href']);
         return($fields);
     } 
               
     public function httpSerializer()
     {
         $s = "Link: <$this->href>";
         foreach ( $this->getTargetAttributes() as $name => $val){
            if ($val) $s .= "; $name=\"$val\"";
         }
         return $s;
     }
     
     /**
      * Poor man parsing... just reliable for parsing httpSerialized links. Only double quoting allowed.
      * attributes supported...
      */
     public function httpParse($s)
     {
         if (preg_match('/Link:\s*<(.*)>/', $s,$matches)){
             $link = static::factory($matches[1]);
             foreach ( $this->getTargetAttributes() as $name => $val){
                 if(preg_match('/'.$name.'\s*=\s*"([^"]*)"/', $s,$matches)){
                    $link->$name(trim($matches[1]));
                 }
             }
         } else {
             $link = false;
         }
         
         return $link;
     }
}
