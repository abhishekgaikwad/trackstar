<script>
    function getInvoice(id) {
        var data = {
            invoice_id : id,
        }
        $.ajax({
            type: "POST",
            url: "<?php  echo $this->createUrl('invoice/ajaxGetInvoice'); ?>",
            data: data,
            success: function(data, textStatus, jqXHR) {
                var $div = $(".invoiceTable");
                $div.html("");
                $div.append("<table id=\"resultInvoiceTable\" class=\"table\">");
                $("#resultInvoiceTable").append("<tr\"><th>Description</th><th>Date</th><th>Tax</th><th>Amount</th><th>Tax Amount</th></tr>");
                var myArray = jQuery.parseJSON(data);
                $("#invoiceNumber").html("Invoice Number : " +myArray[1]);
                $("#subtotal").html(myArray[3]);
                $("#totalTax").html(myArray[4]);
                $("#total").html(myArray[5]);
                
                for(var i=0;i<(myArray[8]).length;i++){
                     var temp = "<tr>" ;
                     temp += "<td>"+((myArray[8])[i])[0]+"</td>" ;
                     temp += "<td>"+((myArray[8])[i])[1]+"</td>" ;
                     temp += "<td>"+((myArray[8])[i])[3]+"</td>" ;
                     temp += "<td>"+((myArray[8])[i])[2]+"</td>" ;
                     var amt = parseFloat(((myArray[8])[i])[3]) * parseFloat(((myArray[8])[i])[2]) * 0.01
                     temp += "<td>"+amt.toFixed(2)+"</td>" ;
                     
                     temp += "</tr>";
                     $("#resultInvoiceTable").append(temp);
                }
                $("#divTable").show("fast");
                
            }
        });
        
    }
</script>

<script>
$(window).ready(function(){
    $("#divList").hide();
    $("#divTable").hide();
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
    
    $("#ajaxGetInvoiceList").click(function(){
         $("#divList").show("fast");
        var temp = new Array();
        var url = "<?php  echo $this->createUrl('invoice/ajaxGetInvoiceList'); ?>";
        $("input[name='selectionType']").each(function(){
            temp.push(this.checked);
        });
        var data = {
            start: temp[0],
            end: temp[1],
            affiliate: temp[2],
            startValue: $("#datepicker1").val(),
            endValue: $( "#datepicker1" ).val(),
            affiliateValue: $( "#affiliateSelect" ).val(),				
	};

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function(data, textStatus, jqXHR) {
                var $div = $("#invoiceList") ;
		$div.html("");
                $div.append("<table id=\"resultInvoiceListTable\" class=\"table\">");
                $("#resultInvoiceListTable").append("<tr\"><th>View</th><th>Invoice</th><th>Affiliate</th><th>Date</th><th>Value</th></tr>");
                var myArray = jQuery.parseJSON(data);
                for(var i=0;i<myArray.length;i++){
                    var temp = "<tr>" ;
                    temp += "<td><input type='radio' class='invoiceid' name='invoiceRadio' value='"+(myArray[i])[0]+"'></td>";
                    temp += "<td>"+(myArray[i])[1]+"</td>";
                    temp += "<td>"+(myArray[i])[2]+"</td>";
                    temp += "<td>"+(myArray[i])[3]+"</td>";
                    temp += "<td>"+(myArray[i])[4]+"</td>";
                    temp += "</tr>";                    
                    $("#resultInvoiceListTable").append(temp);
                    
//                    $('input:radio[name=invoiceRadio]').click(function(){
//                        var i = $('input:radio[name=invoiceRadio]:checked').val();
//                        alert(i);
//                    });
//                    
//                    
                
                //alert((myArray[2])[0]);
                }
                $(".invoiceid").click(function(){
                    var invoice_id = parseInt(($('input:radio[name=invoiceRadio]:checked').val())) ;
                    
                    getInvoice(invoice_id);
                });
             }
        });
        //alert(JSON.stringify(data));
    });

});
</script>




<script>
$(function() {
    $( "#datepicker1" ).datepicker( {
                changeMonth: true,
                changeYear: true,
                showButtonPanel: false,
                dateFormat: 'dd MM yy',
            });
    $( "#datepicker1" ).datepicker('setDate', new Date());
    $( "#datepicker2" ).datepicker( {
                changeMonth: true,
                changeYear: true,
                showButtonPanel: false,
                dateFormat: 'dd MM yy',
            });
    $( "#datepicker2" ).datepicker('setDate', new Date());
  });
  </script>

<div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">
      <div class="panel-heading">
        Invoice List
      </div>

      <div class="panel-body">
        <form name="myForm" id="myForm">
          <div class="col-md-12">
            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon"><input id="month" value="general" class=
                "" name="selectionType" type="checkbox" /> Start</span> <input id=
                "datepicker1" value="" disabled="disabled" class="form-control" name=
                "month" type="text" />
              </div>
            </div>

            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon"><input id="merchant" value="merchant"
                name="selectionType" type="checkbox" /> End</span> <input id=
                "datepicker2" value="" disabled="disabled" class="form-control" name=
                "month" type="text" />
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
            <input class="btn btn-default" type="button" id="ajaxGetInvoiceList" value=
            "Get Invoice List" />
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-12" id="invoiceContainer">
    <div class="col-md-5">
      <div class="panel panel-default" id="divList">
        <div class="panel-heading">
          Invoice List
        </div>

        <div class="panel-body">
            <div class="col-md-12" id="invoiceList" style="height:224px;overflow:auto;">
           
            </div>
        </div>
      </div>
    </div>

    <div class="col-md-7">
      <div class="panel panel-default" id="divTable">
        <div class="panel-heading">
            <span class="">
                <span id="invoiceNumber"></span>
                
            </span>
        </div>
          
        <div class="panel-body">
            <div class="col-md-12 invoiceTable" style="height:224px;overflow:auto;">
                
            </div>
        </div>

        <div class="panel-footer text-right">
            <h4>Sub Total :&nbsp;<span id="subtotal"></span><br>
            Tax :&nbsp;<span id="totalTax"></span><br>
            Total :&nbsp;<span id="total"></span></h4>
            
        </div>
      </div>
    </div>
  </div>