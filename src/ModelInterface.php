<?php
namespace BOTK;

Interface ModelInterface 
{
	public function getOptions();
	public function getVocabulary();
	public function setVocabulary($prefix,$ns);
	public function unsetVocabulary($prefix);
	public function asArray();
	public function asTurtle();	
	public function getTurtleHeader($base=null);
	public function getTripleCount();
	public function __toString();
}