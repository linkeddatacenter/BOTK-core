<?php
namespace BOTK;

Interface ModelInterface 
{
	public static function getVocabularies();
	public static function getTurtleHeader($base=null);
	public function getUri();
	public function getOptions();
	public function asArray();
	public function asTurtle();	
	public function getTripleCount();
	public function getDroppedFields();
	public function __toString();
}