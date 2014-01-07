<script>
    $(document).ready(function(){
        var currE ;
        var currF ;
        var i ;
        var $url = "<?php  echo $this->createUrl('campaign/ajaxUpdate'); ?>";
        $(".campaign").change(function(){
            i = $('input[name="camp"]:checked').val();
            currE = "estimate" + $('input[name="camp"]:checked').val();
            currF = "final" + $('input[name="camp"]:checked').val();
            $(".finalVal").attr("disabled","disabled");
            $(".estimateVal").attr("disabled","disabled");
            
            
            if($("#"+currE).length == 0) {
                $("#"+currF).removeAttr("disabled");
                $("#"+currF).blur(function(){
                if(parseInt($(this).val()) > 0 ) {
                    $data = {
                        final_value: parseInt($(this).val()),
                        campaign_id: i,
                    };
                    update($data,$url,"closed", currF);
                } else {
                    alert("Enter Final Value")
                }
                
            });
            } else {
                 $("#"+currE).removeAttr("disabled");
                 $("#"+currE).blur(function(){
                     $curr = $("#"+currE) ;
                     var estVal = parseInt($(this).val()) ;
                     if(parseInt($(this).val()) > 0 ) {
                        $data = {
                            estimate_value: parseInt($(this).val()),
                            campaign_id: i,
                        };
                        $.ajax({
                            type: "POST",
                            url: $url,
                            data: $data,
                            success: function(data, textStatus, jqXHR) {
                                if(data=="error") {
                                    alert("Error");
                                }else {
                                    
                                    $curr.after("<p>"+estVal+"</p>");
                                    $curr.hide("fast");
                                    $curr.remove();
                                }
                            },
                        });
                     }
                     else {
                        alert("Enter Estimate Value") ;
                    }
                 });
            }
            
            
            
        });
        
        
    });
    
    function update($data, $url, $target, curr) {
        $.ajax({
                type: "POST",
                url: $url,
                data: $data,
                success: function(data, textStatus, jqXHR) {                    
                    var myArray = jQuery.parseJSON(data);
                    if(myArray[0]==0) {
                        $("#"+curr).parent().parent().hide("slow");
                        var $t = $("<tr><td>"+myArray[1]+"</td><td>"+myArray[2]+"</td><td>"+myArray[3]+"</td><td>"+myArray[4]+"</td><td>"+myArray[5]+"</td><td>"+myArray[6]+"</td><td>"+myArray[7]+"</td></tr>").hide();
                        $("#closed").append($t);
                        $t.show("slow");
                    } else {
                        alert("Error") ;
                    }
                    
                }
     });
    }
</script>
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default ">
  <div class="panel-heading ">
    Open Campaign
  </div>


<div class="panel-body">
    <table class="text-center table table-striped">
        <tr><td>Update</td><td>Campaign Date</td><td>Merchant</td><td>Affiliate</td><td>Estimate</td><td>Added By</td><td>Final Value</td></tr>
    <?php
    foreach ($openCampaign as $item) {
        echo '<tr>';
        echo '<td >' .  '<input type="radio" name="camp" class="campaign" value="'. $item->id .'">' . '</td>';
        echo '<td >' .  date("F Y", strtotime($item->campaign_date)) . '</td>';
        echo '<td>' . $item->merchant->merchant_name . '</td>';
        echo '<td>' . $item->affiliate->affiliate_name  . '</td>';
        if(isset($item->estimated_value ))echo '<td>' . $item->estimated_value  . '</td>';
        else echo '<td class="col-md-2">' . '<input type="text" disabled="disabled" id="estimate'. $item->id .'"class="form-control estimateVal">' . '</td>';
        echo '<td>' . $item->addedBy->username . '</td>';
        echo '<td class="col-md-2">' . '<input type="text" disabled="disabled" id="final'. $item->id .'"class="form-control finalVal">' . '</td>';
        echo '</tr>';
    }
    ?>
    </table>
</div>
</div>
</div>


<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default ">
  <div class="panel-heading ">
    Closed Campaign
  </div>


<div class="panel-body">
    <table class="text-center table table-striped" id="closed">
        <tr><td>Campaign Date</td><td>Merchant</td><td>Affiliate</td><td>Estimate</td><td>Final</td><td>Added By</td><td>Finalized By</td></tr>
    <?php
    foreach ($closedCampaign as $item) {
        echo '<tr>';
        echo '<td >' .  date("F Y", strtotime($item->campaign_date)) . '</td>';
        echo '<td>' . $item->merchant->merchant_name . '</td>';
        echo '<td>' . $item->affiliate->affiliate_name  . '</td>';
        echo '<td>' . $item->estimated_value  . '</td>';
        echo '<td>' . $item->final_value  . '</td>';
        echo '<td>' . $item->addedBy->username . '</td>';
        echo '<td>' . $item->finalizedBy->username . '</td>';
        echo '</tr>';
    }
    ?>
    </table>
</div>
</div>
</div>