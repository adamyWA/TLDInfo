<?php
namespace TLDInfo\Data;

class DataPrep extends \BaseController {	
		
	public function readableJson(\TLDInfo\Serialization\SerializeData $serialized) {
		$array = $serialized->jsonSerialize();
		
		
		if(!isset($array['error'])) {
				$array['Type'] = $array['TLD_Type'];
				unset($array['TLD_Type']);
				$array['Available SLDs'] = $array['Reg_Levels'];
				unset($array['Reg_Levels']);
				$array['Special Requirements'] = $array['Special_Requirements'];
				unset($array['Special_Requirements']);
				$array['Character Limit'] = $array['Name_Length'];
				unset($array['Name_Length']);
				$array['DNS Servers'] = $array['DNS'];
				unset($array['DNS']);
				$array['Registration Term'] = $array['Term'];
				unset($array['Term']);
				$array['Whois Privacy Allowed'] = $array['Whois_Privacy'];
				unset($array['Whois_Privacy']);
				$array['Registrar Lock'] = $array['Registrar_Lock'];
				unset($array['Registrar_Lock']);
				$array['DNSSEC'] = $array['Reseller_Managed_DNSSEC'];
				unset($array['Reseller_Managed_DNSSEC']);
				$array['IDN Support'] = $array['IDNs_Available'];
				unset($array['IDNs_Available']);	
				$array['Autorenew Only'] = $array['Auto_Renew_By_Default'];
				unset($array['Auto_Renew_By_Default']);
				$array['Must be set to Autorenew'] = $array['Change_Renew_ExpireSetting'];
				unset($array['Change_Renew_ExpireSetting']);
				$array['Autorenewed On'] = $array['Auto_Renew_Date'];
				unset($array['Auto_Renew_Date']);
				$array['Grace Period'] = $array['Grace_Period'];
				unset($array['Grace_Period']);
				$array['Available In Test'] = $array['Available_In_Test'];
				unset($array['Available_In_Test']);
				$array['Additional Details'] = $array['Additional_Details'];
				unset($array['Additional_Details']);
				unset($array['tld_id']);	
			

		}
			
		return json_encode($array);
	}	
	
}
