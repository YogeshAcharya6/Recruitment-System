<?php
class Config
{
	private $arrConfig;
	private $isAnArray = false;	
	
	private $searchKey;
	private $searchValue;
	
	public function setsearchKey($searchKey)
	{
		echo $this->searchKey = $searchKey;
	}
	
	public function getsearchkey(){ return $this->searchKey; }
	
	public function setsearchValue($searchValue)
	{
		echo $this->searchValue = $searchValue;
	}
	
	public function getsearchValue(){ return $this->searchValue; }
	
	public function __construct($arrConfiguration) {
		try {
			$this->isAnArray = is_array($arrConfiguration);
			if ($this->isAnArray) {
				foreach ($arrConfiguration as $vtop) {
					if (is_array($vtop)) {
						$this->arrConfig[$vtop['searchkey']] = $vtop['searchvalue'];
					}
				}
			} else {
				throw new Exception ("Error:Err001 Not an array create Config " . " " .$e->getMessage());
			}
		} catch (Exception $e) {
			throw new Exception ("Error:Err001 Cannot create Config " . " " .$e->getMessage());
		}
	}
	
	public function __get($setting) {
		return $this->arrConfig[$setting];
	}

	public function __set($setting, $value) {
		$this->arrConfig[$setting] = $value;
	}
	
	public function getSetting($setting) {
		return $this->arrConfig[$setting];
	}
	
	public function getIsAnArray() {
		return ($this->isAnArray);
	}

	public function cntProperties() {
		return count($this->arrConfig);
	}
	
	public static function loadConfiguration($db)
	{
		try
		{
			$sqlConfigure = "select searchkey, searchvalue 
							from tblconfigure where isActive = 1 
							ORDER BY searchkey";
				
			$stmt = $db->prepare($sqlConfigure);
			$stmt->execute();
			$ArrConfigDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $ArrConfigDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlConfigure. " " .$e->getMessage());
		}
	}
	public function Edit($db, $searchkey, $searchvalue)
	{
		try
		{
			$sqlConfigure = "Update tblconfigure set searchValue = ? where searchKey = ?"; 
			$stmt = $db->prepare($sqlConfigure);
			$stmt->bindParam(1, $searchvalue, PDO::PARAM_STR,64);
			$stmt->bindParam(2, $searchkey , PDO::PARAM_STR,64); 
			
			if($stmt->execute())
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlConfigure. " " .$e->getMessage());
		}
	}
	public function getAttachedFileType($db){
		try
		{
			$searchKey = "AttachedFileType";
			$sqlConfig = "SELECT * FROM `tblconfigure` WHERE `searchKey` = ?";
			$stmt = $db->prepare($sqlConfig);
			$stmt->bindParam(1, $searchKey, PDO::PARAM_STR,64);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$attchResult = $result[0]['searchValue'];
			$attchResult = "|" . str_replace(",","|",$attchResult) . "|";
			$attchResult = str_replace(" ","",$attchResult);
			return $attchResult;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlConfig. " " .$e->getMessage());
		}
	}
}
?>
