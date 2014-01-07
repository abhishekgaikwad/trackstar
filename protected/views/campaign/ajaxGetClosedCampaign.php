<?php

$data = array() ;
foreach ($closedCampaign as $item) {

    $temp = array() ;
    array_push($temp, date("F Y", strtotime($item->campaign_date)));
    array_push($temp, $item->merchant->merchant_name);
    array_push($temp, $item->affiliate->affiliate_name);
    array_push($temp, $item->estimated_value);
    array_push($temp, $item->final_value);
    array_push($temp, $item->addedBy->username);
    array_push($temp, $item->finalizedBy->username);
    array_push($data, $temp);
}
echo json_encode($data);

?>