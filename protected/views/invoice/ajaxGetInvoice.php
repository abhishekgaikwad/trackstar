<?php

//echo "hi";

 $data = array() ;
 
 array_push($data, $invoice->id);
 array_push($data, $invoice->invoice_number);
 array_push($data, $invoice->date_of_invoice);
 array_push($data, $invoice->service_value);
 array_push($data, $invoice->service_tax);
 array_push($data, $invoice->total_value);
 array_push($data, $invoice->added_by);
 array_push($data, $invoice->added_on);
 
 $comp = array();
 foreach($components as $item) {
     
     $temp = array();
     if(isset($item->description)) {
         array_push($temp, $item->description);
         array_push($temp, date("d F Y", strtotime($item->date_of_component)));
         array_push($temp, $item->value);
         array_push($temp, $item->tax);
     } else {
         array_push($temp, $item->campaign->merchant->merchant_name);
         array_push($temp, date("F Y", strtotime($item->campaign->campaign_date)));
         array_push($temp, $item->value);
         array_push($temp, $item->tax);

     }
     
     array_push( $comp, $temp);
        
 }
 array_push( $data, $comp);
echo json_encode($data);