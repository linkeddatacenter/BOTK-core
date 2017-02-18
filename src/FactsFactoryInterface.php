<?php
namespace BOTK;

Interface FactsFactoryInterface {
	public function factualize($rawData);
	public function generateLinkedDataHeader();
	public function generateLinkedDataFooter();
	public function addToCounter($counter,$val=1);
	public function getCounters();
}