<?php
namespace BOTK;

Interface FactsFactoryInterface {
	public function removeEmpty( array $data );
	public function factualize( array $rawData );
	public function generateLinkedDataHeader();
	public function generateLinkedDataFooter();
	public function addToTripleCounter( $triplesCount);
	public function getTripleCount();
	public function addError($error);
	public function tooManyErrors();
	public function acceptable( $rawdata);
}