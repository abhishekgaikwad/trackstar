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
        var url = "<?php  echo $this->createUrl('campaign/ajaxGetClosedCampaign'); ?>";
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
            data: JSON.stringify(data),
            success: function(data, textStatus, jqXHR) {
                var $div = $("#result") ;
		$div.html("");
                $div.append("<table id=\"resultTable\" class=\"table table-striped \">");
                $("#resultTable").append("<tr\"><th>Campaign Date</th><th>Merchant</th><th>Affiliate</th><th>Estimate</th><th>Final</th><th>Added By</th><th>Finalized By</th></tr>");
                var myArray = jQuery.parseJSON(data);
                for(var i=0;i<myArray.length;i++){
                    $("#resultTable").append("<tr><td>" + 
                            (myArray[i])[0]+ "</td><td>"+
                            (myArray[i])[1]+"</td><td>" +
                            (myArray[i])[2]+"</td><td>" + 
                            (myArray[i])[3]+"</td><td>" + 
                            (myArray[i])[4]+"</td><td>" + 
                            (myArray[i])[5]+"</td><td>" + 
                            (myArray[i])[6]+"</td></tr>");

                }
                //alert((myArray[2])[0]);
            },
        });
        
        
    });
});
</script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
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

<div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">
      <div class="panel-heading">
        Closed Campaign
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
<div id="result" class="col-md-8 col-md-offset-2">

</div>
<?php




?>
