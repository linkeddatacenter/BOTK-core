<?php
namespace BOTK;

Interface ModelInterface 
{
	public function getUri();
	public function getOptions();
	public function getVocabularies();
	public function setVocabulary($prefix,$ns);
	public function unsetVocabulary($prefix);
	public function asArray();
	public function asTurtle();	
	public function getTurtleHeader($base=null);
	public function getTripleCount();
	public function getDroppedFields();
	public function __toString();
}