<?php

namespace TLDInfo\Data;

class DataController {

	public function __construct() {
        	$this->value = \Route::current()->getParameter('value');
	}

	private function setDataSource() {
		$this->datasource = \DB::select('select * from tld_info where TLD like ?', array($this->value));
	//	$this->datasource = ["Type" => "Truck"];
		return $this->datasource;
	}
	public function getDataSource() {
		return $this->setDataSource();	
	}
	
        public function getSerializedData($results) {
                $data =  new \TLDInfo\Serialization\SerializeData($results);
                return $data;
       }
}
