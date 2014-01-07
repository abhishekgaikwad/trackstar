<?php
    
if($error == 1) {
    $arr = array();
    array_push($arr, $error);
} else {
    $arr = array();
    array_push($arr, $error);
    array_push($arr, date("F Y", strtotime($model->campaign_date)));
    array_push($arr, $model->merchant->merchant_name);
    array_push($arr, $model->affiliate->affiliate_name);
    array_push($arr, $model->estimated_value);
    array_push($arr, $model->final_value);
    array_push($arr, $model->addedBy->username);
    array_push($arr, $model->finalizedBy->username);    
}

echo json_encode($arr);

?>