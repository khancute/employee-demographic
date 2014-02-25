<?php
require_once("database.class.php");
require_once("dashboard.class.php");
require_once("grid.class.php");
require_once("form.class.php");
require_once("common.class.php");
class content extends database
{
	
	function loadContent($contentModule)
	{
		$contentName = str_replace("-", "_", $contentModule);
		
		switch ( $contentName )
		{
			case "data_pegawai":
				$content = $this->dataPegawai();
				break;
			
			case "data_pendidikan_pegawai":
				$content = $this->dataPegawaiPendidikan();
				break;
			
			case "data_golongan_pegawai":
				$content = $this->dataPegawaiGolongan();
				break;
			
			case "detail_pendidikan":
				$content = $this->detailPendidikan();
				break;
			
			case "detail_golongan":
				$content = $this->detailGolongan();
				break;
			
			case "struktur_organisasi":
				$content = $this->strukturOrganisasi();
				break;
			
			case "komposisi_jumlah_pegawai":
				$content = $this->komposisiJumlahPegawai();
				break;
		}
		
		return $content;
	}
	
	function detailGolongan()
	{
		$grid = new grid;
		$sql = "SELECT b.kode, CONCAT(b.prefix,'/',b.sufix) as uraian, a.tahun
				FROM data_pegawai_golongan a
				LEFT JOIN ref_golongan b ON a.kode_golongan = b.kode
				WHERE a.".$_POST['fieldName']." = '".$_POST['rowId']."'
				ORDER BY nip, kode";
		$gridHeader = array("Kode", "Uraian", "Tahun");
		$grid->getGridData($sql);
		$gridData = $grid->gridRows;
		$gridFilter = "";
		$gridFunctions = array(
								array(	"name"=>"btn_add_golongan",
										"id"=>"btn_add_golongan",
										"value"=>"Input Golongan Pegawai",
										"attr"=>array()),
								array(	"name"=>"btn_edit_golongan",
										"id"=>"btn_edit_golongan",
										"value"=>"Ubah Golongan Pegawai",
										"attr"=>array()),
								array(	"name"=>"btn_delete_golongan",
										"id"=>"btn_delete_golongan",
										"value"=>"Hapus Golongan Pegawai",
										"attr"=>array()),		
								);
		$grid->gridId = "data_golongan_pegawai";
		$grid->gridFieldCount = 5;
		$grid->gridFieldId = "kode";
		$grid->withRowSelection = true;
		$content = $grid->dataGrid($gridHeader, $gridData, $gridFilter, $gridFunctions, true);
		
		return $content;
	}
	
	function detailPendidikan()
	{
		$grid = new grid;
		$sql = "SELECT b.kode, b.uraian, a.lembaga, a.jurusan, a.negara
				FROM data_pegawai_pendidikan a
				LEFT JOIN ref_pendidikan b ON a.kode_pendidikan = b.kode
				WHERE a.".$_POST['fieldName']." = '".$_POST['rowId']."'
				ORDER BY nip, kode";
		$gridHeader = array("Kode", "Tingkat Pendidikan", "Lembaga Pendidikan", "Jurusan", "Negara");
		$grid->getGridData($sql);
		$gridData = $grid->gridRows;
		$gridFilter = "";
		$gridFunctions = array(
								array(	"name"=>"btn_add_pendidkan",
										"id"=>"btn_add_pendidkan",
										"value"=>"Input Pendidikan Pegawai",
										"attr"=>array()),
								array(	"name"=>"btn_edit_pendidkan",
										"id"=>"btn_edit_pendidkan",
										"value"=>"Ubah Pendidikan Pegawai",
										"attr"=>array()),
								array(	"name"=>"btn_delete_pendidkan",
										"id"=>"btn_delete_pendidkan",
										"value"=>"Hapus Pendidikan Pegawai",
										"attr"=>array()),		
								);
		$grid->gridId = "data_pendidikan_pegawai";
		$grid->gridFieldCount = 7;
		$grid->gridFieldId = "kode";
		$grid->withRowSelection = true;
		$content = $grid->dataGrid($gridHeader, $gridData, $gridFilter, $gridFunctions, true);
		
		return $content;
	}
	
	function dataPegawaiGolongan()
	{
		$grid = new grid;
		$common = new common;
		$sql = "SELECT a.nip, a.nama, 
					   (CASE WHEN jenis_kelamin = 0 THEN 'Laki-Laki' WHEN jenis_kelamin = 1 THEN 'Perempuan' END) as jenis_kelamin,
					   c.uraian as unit_eselon_2,
					   d.uraian as unit_eselon_3
				FROM data_pegawai a
				JOIN data_pegawai_unitkerja b ON a.nip = b.nip
				LEFT JOIN ref_unit_eselon_2 c ON b.kode_eselon_2 = c.kode
				LEFT JOIN ref_unit_eselon_3 d ON b.kode_eselon_3 = d.kode
				ORDER BY c.kode, d.kode, a.nama";
		
		$eselon2 = $common->getListEselon2();
		$eselon2Data = $common->comboEselonData($eselon2);
		$gridHeader = array("NIP", "Nama Pegawai", "Jenis Kelamin", "Unit Eselon 2", "Unit Eselon 3");
		$grid->getGridData($sql);
		$gridData = $grid->gridRows;
		$gridFilter = array(
							array (	"name"=>"filter_nama", 
									"id"=>"filter_nama",
									"type"=>"text",
									"label"=>"Nama Pegawai",
									"attr"=> array("class"=>"filter")
									),
							array (	"name"=>"filter_jenis_kelamin", 
									"id"=>"filter_jenis_kelamin",
									"type"=>"combo",
									"label"=>"Jenis Kelamin",
									"nullvalue"=>true,
									"attr"=> array("class"=>"filter"),
									"data"=> array (
													array ("value"=>"L", "text"=>"Laki-Laki"),
													array ("value"=>"P", "text"=>"Perempuan")
													) 
									),
							array (	"name"=>"filter_kode_eselon_2", 
									"id"=>"filter_kode_eselon_2",
									"type"=>"combo",
									"label"=>"Unit Eselon 2",
									"nullvalue"=>false,
									"attr"=> array("class"=>"filter"),
									"data"=> $eselon2Data
									)
							);
		
		$grid->gridId = "data_pegawai";
		$grid->gridFieldCount = 7;
		$grid->gridFieldId = "nip";
		$grid->withRowSelection = true;
		$grid->withChildGrid = true;
		$grid->gridChild = "detail_golongan";
		$content = $grid->dataGrid($gridHeader, $gridData, $gridFilter, "", true);
		
		return $content;
	}
	
	function dataPegawaiPendidikan()
	{
		$grid = new grid;
		$common = new common;
		$sql = "SELECT a.nip, a.nama, 
					   (CASE WHEN jenis_kelamin = 0 THEN 'Laki-Laki' WHEN jenis_kelamin = 1 THEN 'Perempuan' END) as jenis_kelamin,
					   c.uraian as unit_eselon_2,
					   d.uraian as unit_eselon_3
				FROM data_pegawai a
				JOIN data_pegawai_unitkerja b ON a.nip = b.nip
				LEFT JOIN ref_unit_eselon_2 c ON b.kode_eselon_2 = c.kode
				LEFT JOIN ref_unit_eselon_3 d ON b.kode_eselon_3 = d.kode
				ORDER BY c.kode, d.kode, a.nama";
		
		$eselon2 = $common->getListEselon2();
		$eselon2Data = $common->comboEselonData($eselon2);
		$gridHeader = array("NIP", "Nama Pegawai", "Jenis Kelamin", "Unit Eselon 2", "Unit Eselon 3");
		$grid->getGridData($sql);
		$gridData = $grid->gridRows;
		$gridFilter = array(
							array (	"name"=>"filter_nama", 
									"id"=>"filter_nama",
									"type"=>"text",
									"label"=>"Nama Pegawai",
									"attr"=> array("class"=>"filter")
									),
							array (	"name"=>"filter_jenis_kelamin", 
									"id"=>"filter_jenis_kelamin",
									"type"=>"combo",
									"label"=>"Jenis Kelamin",
									"nullvalue"=>true,
									"attr"=> array("class"=>"filter"),
									"data"=> array (
													array ("value"=>"L", "text"=>"Laki-Laki"),
													array ("value"=>"P", "text"=>"Perempuan")
													) 
									),
							array (	"name"=>"filter_kode_eselon_2", 
									"id"=>"filter_kode_eselon_2",
									"type"=>"combo",
									"label"=>"Unit Eselon 2",
									"nullvalue"=>false,
									"attr"=> array("class"=>"filter"),
									"data"=> $eselon2Data
									)
							);
		
		$grid->gridId = "data_pegawai";
		$grid->gridFieldCount = 7;
		$grid->gridFieldId = "nip";
		$grid->withRowSelection = true;
		$grid->withChildGrid = true;
		$grid->gridChild = "detail_pendidikan";
		$content = $grid->dataGrid($gridHeader, $gridData, $gridFilter, "", true);
		
		return $content;
	}
	
	function dataPegawai()
	{
		$grid = new grid;
		$common = new common;
		$sql = "SELECT a.nip, a.nama, 
					   (CASE WHEN jenis_kelamin = 0 THEN 'Laki-Laki' WHEN jenis_kelamin = 1 THEN 'Perempuan' END) as jenis_kelamin,
					   c.uraian as unit_eselon_2,
					   d.uraian as unit_eselon_3
				FROM data_pegawai a
				JOIN data_pegawai_unitkerja b ON a.nip = b.nip
				LEFT JOIN ref_unit_eselon_2 c ON b.kode_eselon_2 = c.kode
				LEFT JOIN ref_unit_eselon_3 d ON b.kode_eselon_3 = d.kode
				ORDER BY c.kode, d.kode, a.nama";
		
		$eselon2 = $common->getListEselon2();
		$eselon2Data = $common->comboEselonData($eselon2);
		$gridHeader = array("NIP", "Nama Pegawai", "Jenis Kelamin", "Unit Eselon 2", "Unit Eselon 3");
		$grid->getGridData($sql);
		$gridData = $grid->gridRows;
		$grid->gridId = "data_pegawai";
		$grid->gridFieldCount = 7;
		$grid->gridFieldId = "nip";
		$grid->withRowSelection = true;
		$gridFilter = array(
							array (	"name"=>"filter_nama", 
									"id"=>"filter_nama",
									"type"=>"text",
									"label"=>"Nama Pegawai",
									"attr"=> array("class"=>"filter")
									),
							array (	"name"=>"filter_jenis_kelamin", 
									"id"=>"filter_jenis_kelamin",
									"type"=>"combo",
									"label"=>"Jenis Kelamin",
									"nullvalue"=>true,
									"attr"=> array("class"=>"filter"),
									"data"=> array (
													array ("value"=>"L", "text"=>"Laki-Laki"),
													array ("value"=>"P", "text"=>"Perempuan")
													) 
									),
							array (	"name"=>"filter_kode_eselon_2", 
									"id"=>"filter_kode_eselon_2",
									"type"=>"combo",
									"label"=>"Unit Eselon 2",
									"nullvalue"=>false,
									"attr"=> array("class"=>"filter"),
									"data"=> $eselon2Data
									)
							);
		$gridFunctions = array(
								array(	"name"=>"btn_add",
										"id"=>"btn_add",
										"value"=>"Input Pegawai Baru",
										"attr"=>array("onclick"=>"pegawaiForm(0, \"".$grid->gridId."\")")),
								array(	"name"=>"btn_edit",
										"id"=>"btn_edit",
										"value"=>"Ubah Data Pegawai",
										"attr"=>array("onclick"=>"pegawaiForm(1, \"".$grid->gridId."\")")),
								array(	"name"=>"btn_delete",
										"id"=>"btn_delete",
										"value"=>"Hapus Data Pegawai",
										"attr"=>array()),		
								);
		
		$content = $grid->dataGrid($gridHeader, $gridData, $gridFilter, $gridFunctions);
		
		return $content;
	}
	
	function komposisiJumlahPegawai()
	{
		$content = "";
		$grid = new grid;
		$sql = "SELECT d.uraian as unit_kerja,
				   SUM(CASE WHEN kode_eselon = 1 THEN 1 ELSE 0 END) as eselon_1,
				   SUM(CASE WHEN kode_eselon = 2 THEN 1 ELSE 0 END) as eselon_2,
				   SUM(CASE WHEN kode_eselon = 3 THEN 1 ELSE 0 END) as eselon_3,
				   SUM(CASE WHEN kode_eselon = 4 THEN 1 ELSE 0 END) as eselon_4,
				   SUM(CASE WHEN kode_eselon = 5 AND kode_statuspegawai = 1 THEN 1 ELSE 0 END) as staf_aktif,
				   SUM(CASE WHEN kode_statuspegawai IN (2,6,7,8,9,10) THEN 1 ELSE 0 END) as pegawai_pasif
			FROM data_pegawai_unitkerja a
			JOIN data_pegawai_jabatan b ON a.nip = b.nip
			JOIN data_pegawai c ON b.nip = c.nip
			JOIN ref_eselon_2 d ON a.kode_eselon_2 = d.kode
			WHERE kode_eselon_2 NOT IN (14,15,16)
			GROUP BY uraian
			ORDER BY kode_eselon_2";
		
		$gridHeader = array("NIP", "Esl I", "Esl 2", "Esl 3", "Esl 4", "Staf Aktif", "Staf Pasif");
		$grid->gridRowPerPage = 20;
		$grid->getGridData($sql);
		$gridData = $grid->gridRows;
		$content .= $grid->dataGrid($gridHeader, $gridData, "", 7, "", "komposisi-pegawai", false);
		
		$sql = "SELECT (CASE 
							 WHEN kode_statuspegawai = 6 THEN 'Pegawai yang sedang tugas belajar dalam negeri'
							 WHEN kode_statuspegawai = 7 THEN 'Pegawai yang sedang tugas belajar luar negeri'
							 WHEN kode_statuspegawai = 8 THEN 'Pegawai yang diperbantukan'
							 WHEN kode_statuspegawai = 9 THEN 'Pegawai yang dipekerjakan'
							 WHEN kode_statuspegawai = 10 THEN 'Pegawai cuti di luar tanggungan negara'
					   END) status_pegawai, 
					   COUNT(nip) jumlah
				FROM data_pegawai
				WHERE kode_statuspegawai IN (6,7,8,9,10)
				GROUP BY status_pegawai
				ORDER BY kode_statuspegawai";
		$gridHeader = "<tr><td colspan='4' align='center' class='grid-header'>Jumlah Pegawai Pasif Tahun ".date('Y')."</td></tr>\n";
		$gridData = $grid->getGridData($sql);
		$content .= $grid->dataGrid($gridHeader, $gridData, "", 4, "", "komposisi-pegawai-pasif", false);
		return $content;
	}
	
	function strukturOrganisasi()
	{
		$common = new common;
		
		$content = $common->loadJSFile(array("asset/js/jit.js", "asset/js/org-data.js", "asset/js/org-tree.js"));
		$content .= $common->loadCSSFile(array("asset/css/jit-Spacetree.css"));
		$content .= "<div id='strukturOrganisasiContainer'></div>";
		
		return $content;
	}
	
}
?>