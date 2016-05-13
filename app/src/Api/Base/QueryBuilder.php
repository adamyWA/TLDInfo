<?php
namespace eNomApi;
class QueryBuilder {
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

