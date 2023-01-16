<?php
Class Terbilang {
	function __construct() {
		$this->dasar = array(1=>'satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan');
		$this->angka = array(1000000000,1000000,1000,100,10,1);
		$this->satuan = array('milyar','juta','ribu','ratus','puluh','');
	}

	function eja($n) {
		$str="";
		$i=0;
		$nTemp=explode(".",$n);
		while($nTemp[0]!=0){
			$count = (int)($nTemp[0]/$this->angka[$i]);
			if($count>=10) $str .= $this->eja($count). " ".$this->satuan[$i]." ";
			else if($count > 0 && $count < 10)
				$str .= $this->dasar[$count] . " ".$this->satuan[$i]." ";
			$nTemp[0] -= $this->angka[$i] * $count;
			$i++;
		}
		$str = preg_replace("/satu puluh (\w+)/i","\\1 belas",$str);
		$str = preg_replace("/satu (ribu|ratus|puluh|belas)/i","se\\1",$str);
		if(!empty($nTemp[1])){
			$str.=" koma ";
			$bilangan = new Terbilang;
			$str.= $bilangan -> eja($nTemp[1]);
		}
		return $str;
	}
}

function mbulan($a){
	if($a=='01') $b= 'Januari';
	if($a=='02') $b= 'Februari';
	if($a=='03') $b= 'Maret';
	if($a=='04') $b= 'April';
	if($a=='05') $b= 'Mei';
	if($a=='06') $b= 'Juni';
	if($a=='07') $b= 'Juli';
	if($a=='08') $b= 'Agustus';
	if($a=='09') $b= 'September';
	if($a=='10') $b= 'Oktober';
	if($a=='11') $b= 'November';
	if($a=='12') $b= 'Desember';
	return $b;
}

function dateAdd($interval,$number,$dateTime) {
	$dateTime = (strtotime($dateTime) != -1) ? strtotime($dateTime) : $dateTime;
	$dateTimeArr=getdate($dateTime);
	$yr=$dateTimeArr['year'];
	$mon=$dateTimeArr['mon'];
	$day=$dateTimeArr['mday'];
	$hr=$dateTimeArr['hours'];
	$min=$dateTimeArr['minutes'];
	$sec=$dateTimeArr['seconds'];
	switch($interval) {
		case "s":/*seconds*/
		$sec += $number;
		break;
		case "n":/*minutes*/
		$min += $number;
		break;
		case "h":/*hours*/
		$hr += $number;
		break;
		case "d":/*days*/
		$day += $number;
		break;
		case "ww":/*Week*/
		$day += ($number * 7);
		break;
		case "m": /*similar result "m" dateDiff Microsoft*/
		$mon += $number;
		break;
		case "yyyy": /*similar result "yyyy" dateDiff Microsoft*/
		$yr += $number;
		break;
		default:
		$day += $number;
	}      
	$dateTime = mktime($hr,$min,$sec,$mon,$day,$yr);
	$dateTimeArr=getdate($dateTime);
	$nosecmin = 0;
	$min=$dateTimeArr['minutes'];
	$sec=$dateTimeArr['seconds'];
	if ($hr==0){$nosecmin += 1;}
	if ($min==0){$nosecmin += 1;}
	if ($sec==0){$nosecmin += 1;}
	if ($nosecmin>2){     
		return(date("Y-m-d",$dateTime));
	} else {     
		return(date("Y-m-d G:i:s",$dateTime));
	}
}

function datediff($interval, $datefrom, $dateto, $using_timestamps = false) { 
	if (!$using_timestamps) { 
		$datefrom = strtotime($datefrom, 0); 
		$dateto = strtotime($dateto, 0); 
	} 
	$difference = $dateto - $datefrom;
	switch($interval) { 
		case 'yyyy':
		$years_difference = floor($difference / 31536000); 
		if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) { 
			$years_difference--; 
		} 
		if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) { 
			$years_difference++; 
		} 
		$datediff = $years_difference; 
		break; 
		case "q":
		$quarters_difference = floor($difference / 8035200); 
		while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) { 
			$months_difference++; 
		} 
		$quarters_difference--; 
		$datediff = $quarters_difference; 
		break; 
		case "m": 
		$months_difference = floor($difference / 2678400); 
		while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) { 
			$months_difference++; 
		} 
		$months_difference--; 
		$datediff = $months_difference; 
		break; 
		case 'y': 
		$datediff = date("z", $dateto) - date("z", $datefrom); 
		break; 
		case "d": 
		$datediff = floor($difference / 86400); 
		break; 
		case "w": 
		$days_difference = floor($difference / 86400); 
		$weeks_difference = floor($days_difference / 7); 
		$first_day = date("w", $datefrom); 
		$days_remainder = floor($days_difference % 7); 
		$odd_days = $first_day + $days_remainder; 
		if ($odd_days > 7) { 
			$days_remainder--; 
		} 
		if ($odd_days > 6) { 
			$days_remainder--; 
		} 
		$datediff = ($weeks_difference * 5) + $days_remainder; 
		break; 
		case "ww": 
		$datediff = floor($difference / 604800); 
		break; 
		case "h": 
		$datediff = floor($difference / 3600); 
		break; 
		case "n": 
		$datediff = floor($difference / 60); 
		break; 
		default: 
		$datediff = $difference; 
		break; 
	} 
	return $datediff; 
}

function dinten($har,$bul,$tah){
	$tmp=date("N", mktime(0, 0, 0, $bul, $har, $tah));
	switch($tmp){
		case 1:
		$hari='Senin';
		break;
		case 2:
		$hari='Selasa';
		break;
		case 3:
		$hari='Rabu';
		break;
		case 4:
		$hari='Kamis';
		break;
		case 5:
		$hari='Jumat';
		break;
		case 6:
		$hari='Sabtu';
		break;
		case 7:
		$hari='Minggu';
		break;
	}
	return $hari;
}

class dbf_class {
	var $dbf_num_rec;
	var $dbf_num_field;
	var $dbf_names = array();

	var $_raw;
	var $_rowsize;
	var $_hdrsize;
	var $_memos;

	function __construct($filename) {
		if ( !file_exists($filename)) {
			echo 'Not a valid DBF file !!!'; exit;
		}
		$tail=substr($filename,-4);
		if (strcasecmp($tail, '.dbf')!=0) {
			echo 'Not a valid DBF file !!!'; exit;
		}

		$handle = fopen($filename, "r");
		if (!$handle) { 
			echo "Cannot read DBF file"; exit; 
		}
		$filesize = filesize($filename);
		$this->_raw = fread ($handle, $filesize);
		fclose ($handle);

		if(!(ord($this->_raw[0]) == 3 || ord($this->_raw[0]) == 131) && ord($this->_raw[$filesize]) != 26) {
			echo 'Not a valid DBF file !!!'; exit;
		}

		$arrHeaderHex = array();
		for($i=0; $i<32; $i++){
			$arrHeaderHex[$i] = str_pad(dechex(ord($this->_raw[$i]) ), 2, "0", STR_PAD_LEFT);
		}

		$line = 32;

		$this->dbf_num_rec=  hexdec($arrHeaderHex[7].$arrHeaderHex[6].$arrHeaderHex[5].$arrHeaderHex[4]);
		$this->_hdrsize= hexdec($arrHeaderHex[9].$arrHeaderHex[8]);

		$this->_rowsize = hexdec($arrHeaderHex[11].$arrHeaderHex[10]);
		$this->dbf_num_field = floor(($this->_hdrsize - $line ) / $line ) ;

		for($j=0; $j<$this->dbf_num_field; $j++){
			$name = '';
			$beg = $j*$line+$line;
			for($k=$beg; $k<$beg+11; $k++){
				if(ord($this->_raw[$k])!=0){
					$name .= $this->_raw[$k];
				}
			}
			$this->dbf_names[$j]['name']= $name;
			$this->dbf_names[$j]['len']= ord($this->_raw[$beg+16]);
			$this->dbf_names[$j]['type']= $this->_raw[$beg+11];
		}
		if (ord($this->_raw[0])==131) {

			$tail=substr($tail,-1,1);   
			if ($tail=='F'){            
				$tail='T';              
			} else {
				$tail='t';
			}
			$memoname = substr($filename,0,strlen($filename)-1).$tail;
			$handle = fopen($memoname, "r");
			if (!$handle) { 
				echo "Cannot read DBT file"; exit; 
			}
			$filesize = filesize($memoname);
			$this->_memos = fread ($handle, $filesize);
			fclose ($handle);
		}
	}

	function getRow($recnum) {
		$memoeot = chr(26).chr(26);
		$rawrow = substr($this->_raw,$recnum*$this->_rowsize+$this->_hdrsize,$this->_rowsize);
		$rowrecs = array();
		$beg=1;
		if (ord($rawrow[0])==42) {
			return false;   
		}
		for ($i=0; $i<$this->dbf_num_field; $i++) {
			$col=trim(substr($rawrow,$beg,$this->dbf_names[$i]['len']));
			if ($this->dbf_names[$i]['type']!='M') {
				$rowrecs[]=$col;
			} else {
				$memobeg=$col*512;
				$memoend=strpos($this->_memos,$memoeot,$memobeg);
				$rowrecs[]=substr($this->_memos,$memobeg,$memoend-$memobeg);
			}
			$beg+=$this->dbf_names[$i]['len'];
		}
		return $rowrecs;
	}

	function getRowAssoc($recnum) {
		$rawrow = substr($this->_raw,$recnum*$this->_rowsize+$this->_hdrsize,$this->_rowsize);
		$rowrecs = array();
		$beg=1;
		if (ord($rawrow[0])==42) {
			return false;
		}
		for ($i=0; $i<$this->dbf_num_field; $i++) {
			$col=trim(substr($rawrow,$beg,$this->dbf_names[$i]['len']));
			if ($this->dbf_names[$i]['type']!='M') {
				$rowrecs[$this->dbf_names[$i]['name']]=$col;
			} else {
				$memobeg=$col*512;
				$memoend=strpos($this->_memos,$memoeot,$memobeg);
				$rowrecs[$this->dbf_names[$i]['name']]=substr($this->_memos,$memobeg,$memoend-$memobeg);
			}
			$beg+=$this->dbf_names[$i]['len'];
		}
		return $rowrecs;
	}
}

class timerClass {
	var $startTime;
	var $started;
	function __construct($start=true) {
		$this->started = false;
		if ($start)
			$this->start();
	}

	function start() {
		$startMtime = explode(' ',microtime());
		$this->startTime = (double)($startMtime[0])+(double)($startMtime[1]);
		$this->started = true;
	}

	function end($iterations=1) {
		$endMtime = explode(' ',microtime());
		if ($this->started) {
			$endTime = (double)($endMtime[0])+(double)($endMtime[1]);
			$dur = $endTime - $this->startTime;
			$avg = 1000*$dur/$iterations;
			$avg = round(1000*$avg)/1000;
			return "$avg milliseconds";
		} else {
			return "timer not started";
		}
	}
}
?>
