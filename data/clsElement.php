<?php
class Element
{
	private $idElementType;
	private $elementType;
	private $idElement;
	private $elementTypeId;
	private $elementDesc;

	public function setidElementType($idElementType)
	{
		$this->idElementType = $idElementType;
	}
	
	public function getidElementType(){ return $this->idElementType; } 
	
	
	public function setidElement($idElement)
	{
		$this->idElement = $idElement;
	}
	
	public function getidElement(){ return $this->idElement; } 	
	
	public function setelementTypeId($elementTypeId)
	{
		$this->elementTypeId = $elementTypeId;
	}
	
	public function getelementTypeId(){ return $this->elementTypeId; } 	
	
	public function setelementDesc($elementDesc)
	{
		$this->elementDesc = $elementDesc;
	}
	
	public function getelementDesc(){ return $this->elementDesc; } 
	
	public function setelementType($elementType)
	{
		$this->elementType = $elementType;
	}
	
	public function getelementType(){ return $this->elementType; } 
	
	public function getElementDtls(){
		$ArrElementDtls = array();
		$ArrElementDtls['idElementType'] =$this->idElementType;
		$ArrElementDtls['elementType'] = $this->elementType ;
		return $ArrElementDtls;
	}
	
	public function getElementList(){
		$ArrElementDtls = array();
		$ArrElementDtls['idElement'] =$this->idElement;
		$ArrElementDtls['elementTypeId'] = $this->elementTypeId ;
		$ArrElementDtls['elementDesc'] = $this->elementDesc ;
		return $ArrElementDtls;
	}
	
	public function Save($db)
	{
		try
		{
			$cnt = 0;
			$sqlElementSelect = "select idElement, elementTypeId, elementDesc from tblelements where elementDesc = ?";
			$stmt = $db->prepare($sqlElementSelect);
			$stmt->bindParam(1,$this->elementDesc, PDO::PARAM_STR);
			$stmt->execute();
			while ($result = $stmt->fetch()) {
				$cnt++;
			}
			if($cnt > 0)
			{
				return 0;	
			}
			else
			{
				try
				{
					$sqlElement = "INSERT INTO tblelements( elementTypeId , elementDesc) VALUES (?,?)";
					$pstmt = $db->prepare($sqlElement);
					$pstmt->bindParam(1,$this->elementTypeId , PDO::PARAM_INT);
					$pstmt->bindParam(2,$this->elementDesc , PDO::PARAM_STR);
					$pstmt->execute();	
					return 1;
				} 
				catch(Exception $e) 
				{
					throw new Exception ('Error:Err002' . $sqlElement . " " . $e->getMessage());
				}	
			}
		} 
		catch(Exception $e) 
		{
			
			throw new Exception ('Error:Err003' . $sqlElementSelect . " " . $e->getMessage());
		}	
	}  
	
	
}


?>