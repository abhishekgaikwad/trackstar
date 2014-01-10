<?php function getStartAndEndDate($week, $year)
{

    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    $time += ((7*$week)+1-$day)*24*3600;
    $return[0] = date("d-M-Y", $time);
    $time += 6*24*3600;
    $return[1] = date("d-M-Y", $time);
	$temp = $week+1 ;
    return "Week $temp : " . $return[0] . " To " . $return[1];
} ?>



<?php

	if($intervalType == "weekly") {
		
		$arr = array();
		$start = strtotime($dateStart);
		$end = strtotime($dateEnd);
		foreach($commission as $item) {
			$ddate = $item->date_of_report ;
			$duedt = explode("-", $ddate);
			$date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
			$week  = (int)date('W', $date);
			if(isset($arr[$week])) {
				$temp = $arr[$week] ;
				
				$temp['no_of_clicks'] += $item->no_of_clicks;
				
				$temp['no_of_sales'] += $item->no_of_sales;
				$temp['commission'] += $item->commission;
				$arr[$week] = $temp ;
				
			} else {
				$temp = array(
					'date_of_report' => getStartAndEndDate($week-1, 2013),
					'no_of_clicks' => $item->no_of_clicks ,
					'no_of_sales' => $item->no_of_sales,
					'commission' => $item->commission,
				) ;				
				$arr[$week] = $temp;
			}
		}
		
		$res = array();
		foreach($arr as $item) { 
			array_push($res, $item);
		}
		
		echo json_encode($res);
	} 
	else if($intervalType == "monthly") {
		
		$arr = array();
		
		foreach($commission as $item) {
			$ddate = $item->date_of_report ;
			$month = date('F', strtotime($ddate));
			$mn = date('m', strtotime( $ddate ));
			if(isset($arr[$mn])) {
				$temp = $arr[$mn] ;
				$temp['no_of_clicks'] += $item->no_of_clicks;
				$temp['no_of_sales'] += $item->no_of_sales;
				$temp['commission'] += $item->commission;
				$arr[$mn] = $temp ;
				
			} else {
				$temp = array(
					'date_of_report' => "$month",
					'no_of_clicks' => $item->no_of_clicks ,
					'no_of_sales' => $item->no_of_sales,
					'commission' => $item->commission,
				) ;				
				$arr[$mn] = $temp ;
			}
		}
		$res = array();
		foreach($arr as $item) { 
			array_push($res, $item);
		}
		
		echo json_encode($res);
	} 
	else if($intervalType == "specific"){
		
		if($intervalValue < 1) $intervalValue = 1 ; ;
		$start = strtotime($dateStart);
		$end = strtotime($dateEnd);
		$interval = $intervalValue * 24 * 3600 ;
		$i = $start ;
		$j = $start ;
		$count = 0 ;
		$size = sizeof($commission);
		$arr = array();
		//echo $commission[$j]->date_of_report;
		// while($i <= $end) {
			// $i = $j + 1;
			// echo Date("Y-m-d",$i) . "  ";
			// $j = $j + $interval - 1;
			// echo Date("Y-m-d",$j) . "\n";
			// for($j=$count; $j<4; $j++) {
				// echo $commission[$j]->date_of_report;
			// }

		// }
		foreach($commission as $item) {
			$temp = strtotime($item->date_of_report) ;
			$difference = $temp - $start ;
			$intgroup = floor($difference / $interval) + 1 ;
			$st = Date("d-M-Y",($intgroup-1)*$interval + $start);
			$et = Date("d-M-Y",($intgroup)*$interval + $start - 1);
			if(isset($arr[$intgroup])) {
				$temp = $arr[$intgroup] ;
				$temp['no_of_clicks'] += $item->no_of_clicks;
				$temp['no_of_sales'] += $item->no_of_sales;
				$temp['commission'] += $item->commission;
				$arr[$intgroup] = $temp ;
				
			} else {
				$temp = array(
					'date_of_report' => "$st To $et",
					'no_of_clicks' => $item->no_of_clicks ,
					'no_of_sales' => $item->no_of_sales,
					'commission' => $item->commission,
				) ;				
				$arr[$intgroup] = $temp ;
			}
		}
		$res = array();
		foreach($arr as $item) { 
			array_push($res, $item);
		}
		
		echo json_encode($res);
	
	}
	else {
		echo CJavaScript::jsonEncode($commission);
	}
?>