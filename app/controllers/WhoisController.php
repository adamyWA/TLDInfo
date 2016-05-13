<?php 

class WhoisController extends BaseController {

	protected function getResults() {
		$regex = "/Registrant Name:\s+(.+)\s+Registrant Organization:/";
		$domainsArray = $_POST['domains'];
		$domains = explode(PHP_EOL, $domainsArray);
		foreach($domains as $domain) {
			$Parser = new Novutec\WhoisParser\Parser();
			$result = $Parser->lookup($domain);
			if($result->parsedContacts === true) {
				$results[$domain] = strtoupper($result->contacts->owner[0]->name);
			}
			if($result->parsedContacts === false) {
				$array = $result->toArray();
				$raw = $array['rawdata'][1];
				$match= preg_match($regex, $raw, $matches);
				$results[$domain] = strtoupper($matches[1]);
			}
			
		}
	
		return $results;
		
	}
	protected function compareRegName() {
		$results = $this->getResults();
		$unique = array_unique($results);

		foreach($unique as $name) {
			$hit[$name] = array_keys($results, $name);
		}
		return $hit;
	}
	public function showResults() {
		foreach($this->compareRegName() as $k=>$v) {
			echo "<strong>Registrant Name: " . "</strong><em>$k</em>" . '<br>';
			foreach($v as $domainName) {
				echo $domainName . '<br>'; 
			}
			echo '<br><br><br>';
		}
	}

}
