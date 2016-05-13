<?php
namespace TLDInfo\Json;

class JsonApi extends \BaseController{
	
	public function getJsonFromUri($value) {
		$controller = new \TLDInfo\Data\DataController;
		$frontend = new \TLDInfo\Data\DataPrep;
                $search = new \TLDInfo\Data\SearchData('TLD', $controller->value);
		return $frontend->readableJson($controller->getSerializedData($search->search()));
	}
}
	



