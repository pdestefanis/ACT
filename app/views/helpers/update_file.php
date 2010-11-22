<?php
class UpdateFileHelper extends Helper {
//public $json;
	function addFileHeader()
	{
		return "{\"markers\": [\n";
	}
	function addPointHeader ($num, $data)
	{
		$fileData = "";
		if ($num == 0)
			$fileData .=  "{" . "\n";
		else
			$fileData .=  ",{" . "\n";
		$fileData .=  "\"point\": {\"latitude\":\"" . $data['locationLatitude'] . "\",\"longitude\":\"" . $data['locationLongitude']. "\"},"  . "\n";
		$fileData .=  "\"html\": \"<p><strong>" . $data['name'] . "</strong></p>";
		return $fileData;
    }

     public function addDrugsHeader()
    {
		//prepare header
		$fileData =  "<p><table><tr><td>Drug</td> ";
		$fileData .=  "<th>Units Remaining</th>";
		$fileData .=  "</tr> ";
		return $fileData;

    }
    public function addDrugsData($data)
    {
		$fileData = "<tr><th>" . $data['drugs']['dname'] . "</th>";
		$fileData .= "<td>" . $data['st']['quantity'] . "</td>";
		$fileData .= "</tr>";

		return $fileData;
	}

	public function addDrugsFooter()
    {
		//closing table for html
		return "</table></p> <p>&nbsp;</p>";
    }
	public function addTreatmentsHeader()
    {
    	//prepare header
		$fileData = "<p><table><tr><td>Treatment</td> ";
		$fileData .= "<th># People</th>";
		$fileData .= "</tr> ";
		return $fileData;
    }
	public function addTreatmentsData($data)
    {
		$fileData =  "<tr><th>" . $data['treatments']['dname'] . "</th>";
		$fileData .=  "<td>" . $data['st']['quantity'] . "</td>";
		$fileData .=  "</tr>";
		return $fileData;

    }
    function addTreatmentsFooter()
	{
			return "</table></p> <p>&nbsp;</p>";
    }
	function addCloseQuote(){
		return "\",\n";
	}
	
    function addPointFooter()
    {
		return "\"markerImage\":\"http://google-maps-icons.googlecode.com/files/hospital.png\" \n}\n";
    }
    function addFileFooter ()
	{
	    return "] }";
    }
}
?>