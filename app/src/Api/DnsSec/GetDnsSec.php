<?php

class ConnectToApi {
        const API_URL = 'https://reseller.enom.com/interface.asp?';

        public function __construct($uid,$pw,$params=null) {
                $this->uid = $uid;
                $this->pw = $pw;
                $this->params = $params;
        }

        protected function assembleURL() { //set back to private after testing is done
                $string = '';
                foreach($this->params as $k=>$v) {
                        $string = $string . '&' . $k . '=' . $v;
                }
                $url = self::API_URL . "uid=$this->uid&pw=$this->pw" . $string . "&responsetype=xml";
                return $url;
        }
        protected function getResults() {
                $results = file_get_contents($this->assembleURL());
                $xml = simplexml_load_string($results);
                return $xml;
        }

}

class SetDnsSec extends ConnectToApi{
	private function parseBind($string) {
		$sld = '';
		$tld = '';
		$string = trim($string);
		$output = preg_replace('/\s+/', ' ',$string);
		$array = explode(' ', $output);
		for($i=0;$i<count($array);++$i) {
			$array[$i] = htmlspecialchars($array[$i]);
		}
		$domain = rtrim($array[0], '.');
		$count = substr_count($domain, '.');
		$domArray = explode('.', $domain);
		switch($count) {
			case 1:
				$this->params['sld'] = $domArray[0];
				$this->params['tld'] = $domArray[1];
				break;
			case 2:
				$this->params['sld'] = $domArray[0];
				$this->params['tld'] = $domArray[1] . '.' . $array[2];
				break;
		}
		$this->params['keytag'] = $array[4];
		$this->params['alg'] = $array[5];
		$this->params['digesttype'] = $array[6];
		$this->params['digest'] = preg_replace('/\s+/', '', $array[7]);
		$this->params['digest'] = strtoupper($this->params['digest']);
		if(isset($array[8])) {
			$this->params['maxsiglife'] = $array[8];
		}	
	}
	private function setXml() {
		return $this->getResults();
	}
	public function addRecord() {
		if($_SERVER["REQUEST_METHOD"] == 'POST') {
			if(isset($_POST['loginID']) && isset($_POST['password']) && isset($_POST['bind'])) {
				$this->parseBind($_POST['bind']);
				$this->params['command'] = 'adddnssec';
				$xml = $this->setXml();
				if(isset($xml->errors)) {
					return strval($xml->errors->Err1);
				} 
				if($xml->Success == 'False'){
					return strval($xml->DnsSecData->Result->ResponseMessage);
				}
				else { return $xml; }
			}
		}
		return "Missing Parameters";
	}
	public function getRecord() {
		$this->params['command'] = 'getdnssec';
		if(isset($_POST['loginID']) && isset($_POST['password']) ){
			$domain = $_POST['domainname'];
              		$count = substr_count($domain, '.');
              		$domArray = explode('.', $domain);
              		switch($count) {
                        case 1:
                                $this->params['sld'] = $domArray[0];
                                $this->params['tld'] = $domArray[1];
                                break;
                        case 2:
                                $this->params['sld'] = $domArray[0];
                                $this->params['tld'] = $domArray[1] . '.' . $array[2];
                                break;
                }	
			$xml = $this->setXml();
                        if(isset($xml->errors)) {
                                return strval($xml->errors->Err1);
                        }

			$record = $xml->DnsSecData->KeyData;
			$array = array('alg' => strval($record->Algorithm), 'digest' => strval($record->Digest), 'digesttype' => strval($record->DigestType), 'keytag' => strval($record->KeyTag));
			return $array;
		}
	}
}



?>
<!DOCTYPE html>
<html>
<head>
<title>DNSSEC</title>
<link href="https://bootswatch.com/spacelab/bootstrap.min.css" type="text/css" rel="stylesheet">
</head>
<body style="background:grey">
<br><br>
<div class='container'><div class='col-lg-1'></div><div class='col-lg-10' style="background:white; border-radius: 2%;-webkit-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);">

<?php 
if($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['adddnssec'])) {
	$url = new SetDnsSec($_POST['loginID'], $_POST['password']);
	$call = $url->addRecord();
	if(is_string($call)) {
		echo "<p class='alert alert-danger'>" . $call . "</p>";
	} else { echo "<p class='alert alert-success'>Success</p>"; }
}
?>
<h2>AddDNSSec</h2>
<form method="post" action="dnssec.php" id="the-form" name="the-form">
<label for="loginID">LoginID</label>
<input type="text" class='form-control' id="loginID" name="loginID"></input>
<br>
<label for="password" id="password">Password</label>
<input type="password" class='form-control' id="password" name="password"</input>
<br>
<label for="bind">BIND</label>
<input type="textarea" class='form-control' id="bind" name="bind"></input>
<br>
<input type="hidden" value="adddnsssec" name="adddnssec"></input>
</form>
<button class="btn btn-default" type="submit" form="the-form">Add DS Record</button>
<h2>GetDNSSec</h2>
<br><br>
<form method="post" action="dnssec.php" id="get-form" name="get-form">
<label for="loginID">LoginID</label>
<input type="text" class='form-control' id="loginID" name="loginID"></input>
<br>
<label for="password" id="password">Password</label>
<input type="password" class='form-control' id="password" name="password"</input>
<br>
<input type="hidden" value="getdnssec" name="getdnssec">
<label for="domainname">Domain Name:</label>
<input type ="text" class='form-control' id='domainname' name="domainname">
<br>
<button class="btn btn-default" type="submit" form="get-form">Get DS Record</button>
<?php
if($_SERVER["REQUEST_METHOD"] == 'POST' &&isset($_POST['getdnssec'])) {
        $url = new SetDnsSec($_POST['loginID'], $_POST['password']);
        $call = $url->getRecord();
        if(is_string($call)) {
                 echo "<p class='alert alert-danger'>" . $call . "</p>";
        }
	else {
		echo "<div class='table'><table class='table table-striped table-hover'>
  <thead>
    <tr>
      <th>Digest</th>
      <th>DigestType</th>
      <th>Algorithm</th>
      <th>KeyTag</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>" . $call['digest'] . "</td>
      <td>" . $call['digesttype']. "</td>
      <td>" . $call['alg'] . "</td>
      <td>" . $call['keytag']. "</td>
    </tr>
  </tbody>
</table></div> ";
	}
}
?>

</form>
<br><br>
<div class="form-group">
  <label class="control-label">External DS Record Lookup</label>
  <div class="input-group">
    <span class="input-group-addon">Domain</span>
    <input type="text" class="form-control" id="ds-search">
    <span class="input-group-btn">
      <button class="btn btn-success" type="button" id="lookup">Lookup</button>
    </span>
  </div><div class="results"></div>
	<br>
</div></div><div class='col-lg-1'></div></div>
<script src="https://code.jquery.com/jquery-1.11.3.min.js" type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
	$('#lookup').click(function() {
		var request =  $.ajax({
        method: "GET",
                url: 'http://api.statdns.com/' + $('#ds-search').val() + '/ds',
        dataType: "json"
    });
    request.done(function(data) {
		$('.results').empty();
		if(data.answer != null) {
		$('.results').append('<p class="alert alert-info">' + data.answer[0].rdata + '</p>');
	} else { $('.results').append('<p class="alert alert-danger">No record found, possibly due to propagation. Please verify manually.</p>');
}
 
	});
request.fail(function(data) {
	$('.results').empty();
 $('.results').append('<p class="alert alert-danger">No record found, possibly due to propagation. Please verify manually.</p>');	
})
	});
        
});
</script>
</body>

</html>
