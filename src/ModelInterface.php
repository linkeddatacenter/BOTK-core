<?php
namespace BOTK;

Interface ModelInterface 
{
	
	public function asArray();
	public function getOptions();
	public function asTurtle();	
	public function getTripleCount();
	public function __toString();
	
}