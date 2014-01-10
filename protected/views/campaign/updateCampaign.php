<script>
$(window).ready(function(){
    $("input[name='selectionType']").each(function(){
        $(this).click(function() {
            var value = this.checked ;
            //alert(value);
            $input = $(this).parent().next(); 
            if(value)
                 $input.removeAttr('disabled');
            else
                 $input.attr('disabled','disabled')
        });
    });
    
    $("#ajaxRequest").click(function(){
        var temp = new Array();
        var url = "<?php  echo $this->createUrl('campaign/ajaxGetUpdateCampaign'); ?>";
        $("input[name='selectionType']").each(function(){
            temp.push(this.checked);
        });
        var data = {
            month: temp[0],
            merchant: temp[1],
            affiliate: temp[2],
            monthValue: $("#datepicker").val(),
            merchantValue: $( "#merchantSelect" ).val(),
            affiliateValue: $( "#affiliateSelect" ).val(),				
	};
        
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function(data, textStatus, jqXHR) {
                var $div = $("#result") ;
		$div.html("");
                var temp = "";
                $div.append("<table id=\"resultTable\" class=\"table table-striped \">");
                $("#resultTable").append("<tr><th>Update</th><th>Campaign Date</th><th>Merchant</th><th>Affiliate</th><th>Added By</th><th>Estimate Value</th><th>Final Value</th></tr>");
                var myArray = jQuery.parseJSON(data);
                
                for(var i=0;i<myArray.length;i++){
                    temp += '<tr>';
                    temp += '<td><input type="radio" name="camp" class="campaign" value="' + (myArray[i])[0] + '">' + '</td>';
                    temp += '<td >' + (myArray[i])[1] + '</td>';
                    temp += '<td>' + (myArray[i])[2] + '</td>';
                    temp += '<td>' + (myArray[i])[3] + '</td>';
                    temp += '<td>' + (myArray[i])[4] + '</td>';
                    if((myArray[i])[5] > 0) temp += '<td>' + (myArray[i])[5] + '</td>';
                    else temp += '<td class="col-md-2">' + '<input type="text" disabled="disabled" id="estimate'+ (myArray[i])[0] + '"class="form-control estimateVal">' + '</td>';
                    temp += '<td class="col-md-2">' + '<input type="text" disabled="disabled" id="final'+ (myArray[i])[0] + '"class="form-control finalVal">' + '</td>';
                    temp += '</tr>';
                        
                    //alert(temp);
                    $("#resultTable").append(temp);
                    temp = "";
                    setFuntions();

                }
                //alert((myArray[2])[0]);
            },
        });
        
        
    });
});
</script>


<script>
$(function() {
    $( "#datepicker" ).datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            
            
            $(this).datepicker('setDate', new Date(year, month, 1));
            
        }
    });
    $( "#datepicker" ).datepicker('setDate', new Date());
  });
</script>
<style>
.ui-datepicker-calendar {
    display: none;
    }
</style> 
<script>
    function setFuntions() {
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
            
            
            if($("#"+currE).length == 0) { //works on final value as there is no estimate value input
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
            } else { //works on updating estimated first as estimate value input is present
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
                                    $curr.hide("fast");
                                    $curr.after("<p>"+estVal+"</p>");
                                    
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
        }
    
    function update($data, $url, $target, curr) {
        $.ajax({
                type: "POST",
                url: $url,
                data: $data,
                success: function(data, textStatus, jqXHR) {                    
                    var myArray = jQuery.parseJSON(data);
                    if(myArray[0]==0) {
                        $("#"+curr).parent().parent().hide("slow");
                    } else {
                        alert("Error") ;
                    }
                    
                }
     });
    }
</script>






<div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">
      <div class="panel-heading">
        Update Campaign
      </div>

      <div class="panel-body">
        <form name="myForm" id="myForm">
          <div class="col-md-12">
            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon"><input id="month" value="general" class=
                "" name="selectionType" type="checkbox" /> Month</span> <input id=
                "datepicker" value="" disabled="disabled" class="form-control" name=
                "month" type="text" />
              </div>
            </div>

            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon"><input id="merchant" value="merchant"
                name="selectionType" type="checkbox" /> Merchant</span> <select id=
                "merchantSelect" disabled="disabled" class="form-control">
                  <?php 
                                          foreach($merchant as $i) {
                                                  echo "<option value=\"$i->id\">$i->merchant_name</option>";
                                          }
                                  ?>
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon"><input id="affiliate" value="affiliate"
                name="selectionType" type="checkbox" /> Affiliate</span> <select id=
                "affiliateSelect" disabled="disabled" class="form-control">
                  <?php 
                                          foreach($affiliate as $i) {
                                                  echo "<option value=\"$i->id\">$i->affiliate_name</option>";
                                          }
                                  ?>
                </select>
              </div><!-- /input-group -->
            </div><!-- /input-group -->
          </div><!-- /.col-lg-6 -->
        </form>
      </div>

      <div class="panel-footer">
        <div class="">
          <div class="text-right">
            <input class="btn btn-default" type="button" id="ajaxRequest" value=
            "Get List" />
          </div>
        </div>
      </div>
    </div>
  </div>




<div class="col-md-8 col-md-offset-2">



<div id="result">
    

</div>
</div>

