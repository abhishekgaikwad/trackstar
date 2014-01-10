<?php

//echo "hi";

 $data = array() ;
 
 foreach($invoiceList as $item) {
     $temp = array() ;
     array_push($temp, $item->id) ;
     array_push($temp, $item->invoice_number) ;
     array_push($temp, $item->affiliate->affiliate_name) ;
     array_push($temp, date("d F Y", strtotime($item->date_of_invoice))) ;
     array_push($temp, $item->total_value) ;
     array_push($data, $temp);     
 }
echo json_encode($data);
 ?>
