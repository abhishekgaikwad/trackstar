<?php
    $arr = array();
    foreach($data as $item) { 
       array_push($arr, $item->affiliate_id);        
    }
    
    $aff = array() ;
    foreach($affiliate as $item) { 
       $aff[$item->affiliate_name] = $item->id ;
    }
    
    $r = array_diff($aff, $arr);
    
    $res = array(); 
    foreach($r as $key => $value) { 
        array_push($res, array($key, $value));
    }
    echo json_encode($res);
?>
