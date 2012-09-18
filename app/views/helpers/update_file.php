<?php
class UpdateFileHelper extends Helper {
//public $json;
	function addFileHeader() {
		return "{\"markers\": [";
	}
	function addPointHeader ($num, $data, $parent) {
		$fileData = "";
		if ($num == 0)
			$fileData .=  "{" . "";
		else
			$fileData .=  ",{" . "";
		$fileData .=  "\"point\": {\"latitude\":\"" . $data['locationLatitude'] . "\",\"longitude\":\"" . $data['locationLongitude']. "\"},"  . "";
		$fileData .=  "\"html\": \"<p><strong><a href=locations/view/" .  $data['id'] . ">" . $data['name'] . " (" . $data['shortname'] .")" . "</a></strong></p><br/>";
		if ($parent['shortname'] != $data['shortname']) //only print parent when not the root facility
			$fileData .=  "<p>" . __("Reports To: ",true) . $parent['name'] . " (" . $parent['shortname'] .")" . "</p><br/>";
	
		return $fileData;
    }

     public function addDrugsHeader() {
		//prepare header
		$fileData =  "<div class=infowindow><p><table><tr><td>Item</td>";
		$fileData .=  "<th>Units Remaining</th>";
		$fileData .=  "</tr> ";
		return $fileData;

    }
    public function addDrugsData($data, $alarm) {
		$class = "";
		if ($alarm)
			$class = "alert";			
		
		$fileData = "<tr>";
		$fileData .= "<th class=" .$class . ">" . $data['item']['dname'] . "</th>";
		$fileData .= "<td class=" .$class . ">" . $data['st']['quantity_after'] . "</td>";
		$fileData .= "</tr>";

		return $fileData;
	}

	public function addDrugsFooter($globalAlarm, $graphURL = null) {
		$html = "</table></p>";
		if ($globalAlarm)
			$html .= '<p><a href =alerts/triggeredAlerts class=alert>Alert</a></p><br/>';
			if ($graphURL != NULL)
				$html .=  '<p><img src=' . $graphURL . ' width=350px height=175px ></p>' ;
			$html .=  '</div>';
		return $html;
    }
	public function addKitsHeader() {
		//prepare header
		$fileData =  "<div class=infowindow><p><table><tr><td></td>";
		$fileData .=  "<th>" . __('Kits', true) . "</th>";
		$fileData .=  "</tr> ";
		return $fileData;

    }
    public function addKitsData($data, $alarm) {
		$class = "";
		if ($alarm)
			$class = "alert";			
		$fileData = "<tr>";
		$fileData .= "<th class=" .$class . ">" . __('Current Stock', true) . "</th>";
		$fileData .= "<td class=" .$class . ">" . $data['Assigned']['sum'] . "</td>";
		$fileData .= "</tr>";
		$fileData .= "<tr>";
		$fileData .= "<th class=" .$class . ">" . __('Provided to Patients', true) . "</th>";
		$fileData .= "<td class=" .$class . ">" . $data['At Patient']['sum'] . "</td>";
		$fileData .= "</tr>";
		$fileData .= "<tr>";
		$fileData .= "<th class=" .$class . ">" . __('Discarded', true) . "</th>";
		$fileData .= "<td class=" .$class . ">" . $data['Expired']['sum'] . "</td>";
		$fileData .= "</tr>";

		return $fileData;
	}

	public function addKitsFooter($globalAlarm, $graphURL = null) {
		$html = "</table></p>";
		if ($globalAlarm)
			$html .= '<p><a href =alerts/triggeredAlerts class=alert>Alert</a></p><br/>';
			if ($graphURL != NULL)
				$html .=  '<p><img src=' . $graphURL . ' width=350px height=175px ></p>' ;
			$html .=  '</div>';
		return $html;
    }
	function addCloseQuote(){
		return "\",";
	}
	
    function addPointFooter($globalAlarm, $empty) {
		//all icons generated from 
		//http://gmaps-utility-library.googlecode.com/svn/trunk/mapiconmaker/1.1/examples/markericonoptions-wizard.html
		if ($globalAlarm)
			$html = "\"markerImage\":\"img/star-red.png\" }";
		else if ($empty) // case where no items have been reported 
			$html = "\"markerImage\":\"img/star-grey.png\" }";
		else
			$html = "\"markerImage\":\"img/star-blue.png\" }";
		return $html;
    }
    function addFileFooter () {
	    return "] }";
    }
}
?>