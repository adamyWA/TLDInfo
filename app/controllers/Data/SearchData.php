<?php

namespace TLDInfo\Data;

class SearchData extends \BaseController {

	public function __construct($array_key, $search){
		$this->array_key = $array_key;
		$this->search = $search;
		$controller = new DataController;
		$datasource = $controller->getDataSource();
		if(isset($datasource[0])) {
			$this->datasource = (array)$datasource[0];
		}
		else {
			$this->datasource = (array)$datasource;
		}
	}

	public function search() {
		if(!empty($this->datasource) && $this->datasource[$this->array_key] == $this->search) {
			return $this->datasource;
		}
		return ["error" => "No results found"];

	}
}	
		
