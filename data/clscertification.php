<?php
Class Certification
{		
	private $idCertification;	
	private $Certification;
	
	public function getobjCertificationDtls( $db )
	{
		try
		{
			$sqlCertification = "select idCertification, Certification from  dimcertification ORDER BY Certification ASC";
			$stmt = $db->prepare($sqlCertification);
			$stmt->execute();
			$ArrsqlCertificationDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return  $ArrsqlCertificationDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlCertification. " " .$e->getMessage());
		}
	}
	
}
?>	