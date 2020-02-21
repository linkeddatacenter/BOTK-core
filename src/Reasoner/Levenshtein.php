<?php
/*
 * Copyright (c) 2019 LinkedData.Center. All rights reserved.
 */
namespace BOTK\Reasoner;

/**
 * input sensitivity: an acceptable rate for changes (0-1)
 * Input stream specification:
 *   The input stream is divided in two part, the first part contains, one for row,
 *   a couple of object Uri,  string
 *   then there is an empty line
 *   the second part contains, one for row, a couple of an subject uri and a token to be searched in the string
 *   
 * Output stream:
 *   rules to insert in a graphName, RDF statements that links subject and object having  
 *   the minimun Levenshtein distance evaluated on string tokens
 */
class Levenshtein extends AbstractReasoner
{
    protected $sensitivity=0.2;
   
    public function setSensitivity( float $sensitivity )
    {
        assert( $sentistivity >= 0 && $sentistivity <=1 );
        
        $this->sensitivity = $sensitivity;
        return $this ;
    }
      
 		
    /**
	 * targets must be an hash of tokenized strings
     */
	protected function findClosestUriToToken( $token, array $targets) 
	{
	    $tokenLength=strlen($token);
	    $absSensitivity= round($tokenLength * $this->sensitivity);
	    $minLength=max( 1, $tokenLength - $absSensitivity);
	    $maxLength=$tokenLength + $absSensitivity;
	    $closestTarget = null;
	    $shortest = -1;
	    foreach($targets as $targetUri=>$targetTokens){
	        if( $shortest === 0 ) break; // a perfect match found.
	        foreach ($targetTokens as $targetToken ){
	            $targetTokenLength=strlen($targetToken);
	            if( ($targetTokenLength >= $minLength) && ($targetTokenLength <= $maxLength)) {
	                $lev = levenshtein($token, $targetToken);
	                if ($lev <= $shortest || $shortest < 0) {
	                    $closestTarget  = $targetUri;
	                    $shortest = $lev;
	                }
	                if( $shortest === 0 ) break; // a perfect match found.
	            }
	        }
	    }
	    
	    if( $closestTarget && ($shortest <= $absSensitivity) ){
	        return $closestTarget;
	    } else {
	        return false;
	    }
	}
	
	
	public function run ()
	{
	    // analyze first input stream part: read targets
	    // only tokens with length > sensitivity are considered.
	    $targets=[];
	    
	    while (($data=fgetcsv($this->inputStream)) && isset($data[1])) {
	        assert( !empty($data[0]) && !empty($data[1]) ) ;
	        
	        $subjectUri=$data[0];
	        $string=$data[1];
	        
	        // tokenize string
	        $tokenizedString=[];
	        $tok= strtok($string, ' ');
	        while ($tok !== false) {
	            // ignore too short tokens
	            if( floor(strlen($tok)*$this->sensitivity)>0) { $tokenizedString[]=strtolower($tok);}
                $tok = strtok(' ');
	        }
	        
	        if( !empty($tokenizedString) ) { $targets[$subjectUri]=$tokenizedString;}
	    }
	    
	    //analyze second input stream part: token to be searched in targets
	    fwrite($this->outputStream, "INSERT DATA { GRAPH <$this->graphName> {\n");
	    
	    while (($data = fgetcsv($this->inputStream)) !== FALSE) {
	       
	        assert(!empty($data[0]) && !empty($data[1]) ) ;
	        
	        list ($uri, $token)=$data;
	        if( $closestUri=$this->findClosestUriToToken( strtolower($token), $targets) ){
	            fprintf($this->outputStream, "<%s> <%s> <%s>.\n", $closestUri, $this->property, $uri);
	        }
	        
	    }
	    fwrite($this->outputStream, "}}");	    
	}
}