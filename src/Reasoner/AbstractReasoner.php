<?php
/*
* Copyright (c) 2019 LinkedData.Center. All rights reserved.
*/
namespace BOTK\Reasoner;

abstract class AbstractReasoner  
{
    
    protected $inputStream=STDIN;
    protected $outputStream=STDOUT;
    protected $graphName='urn:botk:reasoner:graph';
    protected $property='urn:botk:reasoner:infers';
    
    public function setInputStream( $handle )
    {
        $this->inputStream = $handle;
        return $this ;
    }
    
    public function setOutputStream( $handle )
    {
        $this->outputStream = $handle;
        return $this ;
    }
    
    public function setGraphName( $graphName )
    {
        $this->graphName = $graphName;
        return $this ;
    }
    
    
    public function setProperty( $property )
    {
        $this->property = $property;
        return $this ;
    }
    
	
    abstract public function run();

}