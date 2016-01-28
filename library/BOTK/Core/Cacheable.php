<?php
namespace BOTK\Core;

interface Cacheable
{
    /**
     * @return  a strting compatible with ETag caching header that
     * uniquely identyfy the obhect.
     * a simple implementaticon could be:
     *  return md5(serialize($this))
     */
    public function getETag();
    
    
    /**
     * @return a DateTime object that represents when
     *         the object was modified
     */
    public function getLastModifiedDate();
    
}
