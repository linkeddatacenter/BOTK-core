<?php
namespace BOTK;

Interface ModelInterface 
{
	public static function fromArray(array $data);
	public static function fromStdObject( \stdClass $data);
	
	public static function getVocabularies();
	public static function getTurtleHeader($base=null);
	public function getUri();
	public function getOptions();
	public function getTripleCount();
	public function getDroppedFields();
	
	public function asStdObject();
	public function asArray();
	public function asTurtleFragment();	
	public function asLinkedData();	
	public function asString();	
	public function __toString();
}