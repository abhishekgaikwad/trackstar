<?php
    //var_dump($campaign);
    
    $data = array();
    
    foreach($campaign as $item) {
        $temp = array();
        array_push($temp, $item->id);
        array_push($temp, date("F Y", strtotime($item->campaign_date))) ;
        array_push($temp, $item->merchant->merchant_name) ;
        array_push($temp, $item->final_value);        
        $tempVal = 0 ;
        foreach($item->invoiceComponents as $invoiceComp) {
            $tempVal += $invoiceComp->value ;
        }
        array_push($temp, $tempVal) ;
        array_push($temp, $item->final_value - $tempVal) ;
        
        array_push($data, $temp) ;        
    }
    
    echo json_encode($data) ;