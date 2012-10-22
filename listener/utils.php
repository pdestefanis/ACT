<?php
	class Utils {
		
		/*
		 * Find the element in a multi dim array and return it
		 * the return variable may be an array as well
		 */
		public function findArrayElement (&$array, &$element, &$a) {
			if (is_array($array) && count($array) != 0 ) {
				foreach ($array as $key => $value) {
					if ($key === $element) { 
						$a = $value;
					} else {
						$this->findArrayElement ($value, $element, $a); 
					}
				}
			} 	
		}
		
		/*
		 Working with XML. Usage:
		$xml=xml2ary(file_get_contents('1.xml'));
		$link=&$xml['ddd']['_c'];
		$link['twomore']=$link['onemore'];
		// ins2ary(); // dot not insert a link, and arrays with links inside!
		echo ary2xml($xml);
		*/
	
		// XML to Array
		function xmlToArray(&$string) {
			$parser = xml_parser_create();
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parse_into_struct($parser, $string, $vals, $index);
			xml_parser_free($parser);
	
			$mnary=array();
			$ary=&$mnary;
			foreach ($vals as $r) {
				$t=$r['tag'];
				if ($r['type']=='open') {
					if (isset($ary[$t])) {
						if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
						$cv=&$ary[$t][count($ary[$t])-1];
					} else $cv=&$ary[$t];
					if (isset($r['attributes'])) {
						foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;
					}
					$cv['_c']=array();
					$cv['_c']['_p']=&$ary;
					$ary=&$cv['_c'];
	
				} elseif ($r['type']=='complete') {
					if (isset($ary[$t])) { // same as open
						if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
						$cv=&$ary[$t][count($ary[$t])-1];
					} else $cv=&$ary[$t];
					if (isset($r['attributes'])) {
						foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;
					}
					$cv['_v']=(isset($r['value']) ? $r['value'] : '');
	
				} elseif ($r['type']=='close') {
					$ary=&$ary['_p'];
				}
			}
	
			$this->del_p($mnary);
			return $mnary;
		}
	
		// _Internal: Remove recursion in result array
		private function del_p(&$ary) {
			foreach ($ary as $k=>$v) {
				if ($k==='_p') unset($ary[$k]);
				elseif (is_array($ary[$k])) $this->del_p($ary[$k]);
			}
		}
	
		// Array to XML
		function arrayToXML($cary, $d=0, $forcetag='') {
			$res=array();
			foreach ($cary as $tag=>$r) {
				if (isset($r[0])) {
					$res[]=arrayToXML($r, $d, $tag);
				} else {
					if ($forcetag) $tag=$forcetag;
					$sp=str_repeat("\t", $d);
					$res[]="$sp<$tag";
					if (isset($r['_a'])) {
						foreach ($r['_a'] as $at=>$av) $res[]=" $at=\"$av\"";
					}
					$res[]=">".((isset($r['_c'])) ? "\n" : '');
					if (isset($r['_c'])) $res[]=arrayToXML($r['_c'], $d+1);
					elseif (isset($r['_v'])) $res[]=$r['_v'];
					$res[]=(isset($r['_c']) ? $sp : '')."</$tag>\n";
				}
	
			}
			return implode('', $res);
		}
	
		// Insert element into array
		function insertIntoArray(&$ary, $element, $pos) {
			$ar1=array_slice($ary, 0, $pos); $ar1[]=$element;
			$ary=array_merge($ar1, array_slice($ary, $pos));
		}
	
	}

?>