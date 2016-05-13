<?php
namespace TLDInfo\Serialization;

class SerializeData extends \BaseController implements \JsonSerializable{

	public function __construct(array $array) {
		$this->array = $array;
	}
	public function jsonSerialize() {
		return $this->array;
	}
		
	/* need to move this out */
	public function updateDbFromJson($json) {
		$array = json_decode($json);
		$tld = $array[0];
		$query = "UPDATE tld_info SET ?=? WHERE TLD like ?";
		foreach($array as $k=>$v) {
			$update = DB::update($query, array($k, $v, $tld));
			if($update == 1) {
				return json_encode(['success' => 'True']);
			}	
			else {
				return json_encode(['error' => 'Unable to update data, please refer to logs']);
			}
		}
	}	
}

