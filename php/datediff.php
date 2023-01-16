<?php
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
?>
