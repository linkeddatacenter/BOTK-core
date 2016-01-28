<?php
namespace mylibrary\HelloEndPoint\models;


class About
{
	public $title  			= 'Hello numbered Worlds.';
	public $version 		= 'First hello version.';
	public $numbers			= array(1,2,3,4);
	
	public function sum(){
		return array_sum($this->numbers);
	}
	
	public function counter(){
		return count($this->numbers);
	}
}
