<?php
    $data = array();
    
    foreach ($openCampaign as $item) {
        $temp = array();
        array_push($temp, $item->id) ;
        array_push($temp, date("F Y", strtotime($item->campaign_date))) ;
        array_push($temp, $item->merchant->merchant_name) ;
        array_push($temp, $item->affiliate->affiliate_name) ;
        array_push($temp, $item->addedBy->username) ;
        if (isset($item->estimated_value)) {
            array_push($temp, $item->estimated_value);
        } else {
            array_push($temp, 0);
        }
        array_push($data, $temp);
    }
    echo json_encode($data);

    ?>