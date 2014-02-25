<?php
require_once("class/database.class.php");

function data_pegawai()
{
	$db = new database;
	$sql = "SELECT nip, nama,
				   b.kode as eselon_2,
				   c.kode as eselon_3,
				   (CASE 
						 WHEN pendidikan LIKE '%S3%' THEN 8 
						 WHEN pendidikan LIKE '%S2%' THEN 7
						 WHEN pendidikan LIKE '%sarjana%' OR pendidikan LIKE '%S1%' THEN 6
						 WHEN pendidikan LIKE '%D III%' THEN 5
						 WHEN pendidikan LIKE '%D I%' THEN 4
						 WHEN pendidikan LIKE '%S M A%' OR 
							  pendidikan LIKE '%S L T A%' OR 
							  pendidikan LIKE '%S M E A%' OR 
							  pendidikan LIKE '%SMEA%' OR 
							  pendidikan LIKE '%SMA%' THEN 3
						 WHEN pendidikan LIKE '%S M P%' OR 
							  pendidikan LIKE '%S L T P%' OR 
							  pendidikan LIKE '%SLTP%' OR 
							  pendidikan LIKE '%K P A A%' THEN 2
						 WHEN pendidikan LIKE '%SD%' OR pendidikan LIKE '%S D%' THEN 1
				   END) as pendidikan,
				   (CASE 
						 WHEN jabatan = 'CTLN' THEN 10
						 WHEN jabatan LIKE 'Dipekerjakan%' THEN 9
						 WHEN jabatan LIKE 'Diperbantukan%' THEN 8
						 WHEN jabatan LIKE '%TB-DN%' THEN 7
						 WHEN jabatan LIKE '%TB-LN%' THEN 6
						 WHEN jabatan LIKE '%CPNS%' THEN 5
						 ELSE 1
				   END) as jabatan,
				   jenis_kelamin,
				   d.kode as golongan,
				   a.eselon
			FROM master_pegawai a
			LEFT JOIN ref_unit_eselon_2 b ON a.unit_eselon_2 = b.kode_unit
			LEFT JOIN ref_unit_eselon_3 c ON a.unit_eselon_3 = c.kode_unit
			JOIN ref_golongan d ON a.golongan = CONCAT(d.prefix,'.',d.sufix)
		ORDER BY eselon_3, eselon_2";
	$data = $db->dbFetchArray($sql);
	foreach ( $data as $row )
	{
		$thnLahir = substr($row['nip'], 0, 4);
		$blnLahir = substr($row['nip'], 4, 2);
		$tglLahir = substr($row['nip'], 6, 2);
		$tanggal_lahir = $thnLahir."-".$blnLahir."-".$tglLahir;
		
		$jenis_kelamin = ($row['jenis_kelamin'] == 'L') ? 0 : 1;
		/*
		$insert = "INSERT INTO data_pegawai 
					(nip, nama, tanggal_lahir, jenis_kelamin, kode_statuspegawai)
					VALUES
					(".$row['nip'].", '".$row['nama']."', '".$tanggal_lahir." 00:00:00', ".$jenis_kelamin.", ".$row['jabatan'].")";
		*/
		//$row['pendidikan'] = ($row['pendidikan']!="") ? $row['pendidikan'] : "NULL";
		$insert = "INSERT INTO data_pegawai_jabatan 
					(nip, kode_eselon, operator, `insert`)
					VALUES
					(".$row['nip'].", ".$row['eselon'].", 0, UNIX_TIMESTAMP())";
		//echo $insert."<br>";
		$rs = $db->dbExecuteQuery($insert);
	}
}

data_pegawai();
?>