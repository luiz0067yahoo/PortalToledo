<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');

	require_once($_SERVER['DOCUMENT_ROOT'].'/admin/library/functions.php');
	class ControllerReports{
		private $params;
		private $report;
		public function __construct(){
		    $this->params=(getParameter("params"));
		    $this->report=(getParameter("report"));
			if($this->report=="reportColetasSecretariaDate")
				echo json_encode($this->reportColetasSecretariaDate($this->params));
		}
		
		public function reportColetasSecretariaDate($params){
			$params["data_inicio"]=(new DateTime($params["data_inicio"]))->format("d/m/Y");
			$params["data_fim"]=(new DateTime($params["data_fim"]))->format("d/m/Y");
			$sql="";							
			$sql.="			select ";
			$sql.="					i.secretaria as Secretaria,";
			$sql.="					i.local as Local,";
			$sql.="					i.sala as Sala,";
			$sql.="					c.serial as Serial,";
			$sql.="					c.ip as 'Endereço IP',";
			$sql.="					concat(DATE_FORMAT(min(c.data_coleta),'%d/%m/%y'),' ',DATE_FORMAT(min(c.hora_coleta),'%h:%i:%s'))  as 'Data Início',";
			$sql.="					min(c.paginas) as 'Páginas início' ,";
			$sql.="					concat(DATE_FORMAT(max(c.data_coleta),'%d/%m/%y'),' ',DATE_FORMAT(max(c.hora_coleta),'%h:%i:%s'))  as 'Data Fim',";
			$sql.="					max(c.paginas) as 'Páginas Fim',";
			$sql.="					max(c.paginas)-min(c.paginas) as Total";
			$sql.="				from";
			$sql.="					impressoras i";
			$sql.="					left join coletas_impressoras ci on(ci.id_impressoras=i.id)";
			$sql.="					left join coletas c on(ci.serial=c.serial)";
			$sql.="				where(";
			$sql.="				  (c.data_coleta =ci.data_coleta) ";
			$sql.="				  and (c.hora_coleta =ci.hora_coleta) ";
			$sql.="				  and (c.data_coleta between STR_TO_DATE(:data_inicio,'%d/%m/%Y') and STR_TO_DATE(:data_fim,'%d/%m/%Y')) ";
			$sql.="				  and (ci.data_coleta between STR_TO_DATE(:data_inicio,'%d/%m/%Y') and STR_TO_DATE(:data_fim,'%d/%m/%Y')) ";
			if(isset($params["secretaria"])&&($params["secretaria"]!="TODAS")){
				$sql.="				  and";
				$sql.="				  (i.secretaria=:secretaria)";
			}
			else{
				$sql.="				  and";
				$sql.="				  (:secretaria=:secretaria)";
			}
			$sql.="				)";
			$sql.="				group by";
			$sql.="					i.secretaria,";
			$sql.="					i.local,";
			$sql.="					i.sala,";
			$sql.="					c.serial, ";
			$sql.="					c.ip";
			$sql.="				order by i.secretaria asc, i.local asc,i.Sala asc";
			$sql.="						;";
			return DAOquery($sql,$params,true,"Relatório de páginas impressas por secretaria entre datas");
		}
	}
	if(controlAcess())
		$Controller = new ControllerReports();
?>