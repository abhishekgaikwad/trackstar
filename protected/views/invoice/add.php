<script>
 var campaignArray = new Array();
 var invoiceArray = new Array();
 var customArray = new Array();
 var totalGlobal = 0 ;
 var taxGlobal = 0 ;
$(window).ready(function(){
       $("#invoiceContainer").hide();
       $("#getCampaign").click(function(){
           $("#invoiceContainer").show("");
           var url = "<?php  echo $this->createUrl('invoice/ajaxGetCampaign'); ?>";
           var data = {
            affiliateValue: $( "#affiliateSelect" ).val(),				
           };
           $( "#affiliateSelect" ).attr("disabled","disabled");
           $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function(data, textStatus, jqXHR) {
                $("#campaignTable").html("");
                 $("#invoiceTable").html("");
                var head = '<tr><th>Add</th><th>Month</th><th>Merchant</th><th>Value</th><th>Remaining</th></tr>' ;
                var myArray = jQuery.parseJSON(data);
                campaignArray = myArray ;
                $("#campaignTable").append(head);
                for(var i=0;i<myArray.length;i++){
                    $checkbox = '<input type="checkbox" name="campaignSelect" value="'+ (myArray[i])[0] +'">'  
                    $("#campaignTable").append("<tr id='"+i+"'><td>" + 
                            $checkbox+ "</td><td>"+
                            (myArray[i])[1]+"</td><td>" +
                            (myArray[i])[2]+"</td><td>" + 
                            (myArray[i])[3]+"</td><td>" + 
                            (myArray[i])[5]+"</td><td>"
                     );
                }
                
                var detailHead = '<tr><th>Description</th><th>Month</th><th>Tax Percent</th><th>Amount</th><th>Tax</th></tr>' ;
                $("#invoiceTable").append(detailHead);
                setActions() ;
            }
        });
       });
});

function setActions() {
    $("input[name='campaignSelect']").each(function(){
          var trid = parseInt($(this).closest('tr').attr('id'));
          $(this).click(function() {
              if(this.checked) {
                  invoiceArray.push(trid);
                  var trcontent = "<tr id='invoice"+ trid +"'><div class='col-md-8'>" ;
                    trcontent += "<td>" + (campaignArray[trid])[2] + "</td>" ;
                    trcontent += "<td>" + (campaignArray[trid])[1] + "</td>" ;
                    trcontent += "<td><input type='text' id='tax"+ (campaignArray[trid])[0] +"' class='form-control taxfield' value='12.36'></td>" ;
                    trcontent += "<td><input type='text' id='value"+ (campaignArray[trid])[0] +"' class='form-control valuefield' value='"+ (campaignArray[trid])[3] +"'></td>" ;
                    var tempTax = 0.01 * 12.36 * (campaignArray[trid])[3] ;
                    
                    trcontent += "<td class='taxAmt'>" + tempTax.toFixed(2) + "</td>" ;
                    trcontent += "</tr>" ;
                  $("#invoiceTable").append(trcontent);
                  calculate();
                  $(".taxfield").blur(function(){
                      var tax = parseFloat($(this).val()) * 0.01 ;
                      var id = parseInt($(this).attr('id').replace("tax",""));
                      var amt = parseInt($("#value"+id).val());
                      var ans = (amt*tax) ;
                      $(this).parent().next().next().html(ans.toFixed(2));
                      calculate();
                  });
                  
                  $(".valuefield").blur(function(){
                      var amt = parseInt($(this).val()) ;
                      var id = parseInt($(this).attr('id').replace("value",""));
                      //alert(id);
                      var trid1 = parseInt($(this).closest('tr').attr('id').replace("invoice",""));
                      if(amt > ((campaignArray[trid1])[5])) {
                          alert("Amount Can't Be Greater than " + (campaignArray[trid1])[5]);
                          $(this).val((campaignArray[trid1])[5]);
                      } else {
                          var tax = parseFloat($("#tax"+id).val()) * 0.01 ;
                      var ans = (amt*tax) ;
                      $(this).parent().next().html(ans.toFixed(2));
                          
                      }                   
                      calculate();
                  });
                  //alert(invoiceArray);
              } else {
                  var index = invoiceArray.indexOf(trid);
                  var i = invoiceArray.splice(index,1);
                  $("#invoice"+i).remove();
                  calculate();
                  
              };
              //alert(invoiceArray);
          });
      });
      
      $("#customEntry").click(function() {
            var trcontent = "<tr id='customInvoice'><div class='col-md-8'>" ;
                    trcontent += "<td><input type='text'  class='form-control taxfield' value='12.36'></td>" ;
                    trcontent += "<td><input type='text'  class='form-control taxfield' value='12.36'></td>" ;
                    trcontent += "<td><input type='text'  class='form-control taxfield' value='12.36'></td>" ;
                    trcontent += "<td><input type='text'  class='form-control valuefield' value=''></td>" ;
                    trcontent += "<td class='taxAmt'>" + "</td>" ;
                    trcontent += "</tr>" ;
            $("#invoiceTable").append(trcontent);
      });
}

function calculate() {
    var total = 0 ;
    var tax = 0 ;
    var index ;
    for(var i=0;i<invoiceArray.length;i++){
        index = invoiceArray[i] ;
        total += parseInt((campaignArray[index])[3]) ;
    }
    $(".taxAmt").each(function(){
        tax += parseFloat($(this).html()) ;
    });
    $("#subtotal").html(total);
    $("#tax").html(tax);
    $("#total").html(tax+total);
    //alert(total + " " + tax);
}

</script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
$(function() {
    $( "#datepicker" ).datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,
        dateFormat: 'dd MM yy',
    });
    $( "#datepicker" ).datepicker('setDate', new Date());
  });
  </script>
 <div class="col-md-6 col-md-offset-6">
    <div class="col-md-4">
      <label>Select Affiliate</label> <select id="affiliateSelect" class="form-control">
        <?php 
                                                  foreach($affiliate as $i) {
                                                          echo "<option value=\"$i->id\">$i->affiliate_name</option>";
                                                  }
                                          ?>
      </select>
    </div>

    <div class="col-md-8">
      <label>&nbsp;&nbsp; Date of Invoice</label>

      <div>
        <div class="col-md-6">
          <input type="text" id="datepicker" class="form-control" />
        </div>

        <div class="col-md-6">
          <input type="button" id="getCampaign" value="Get Campaign" class=
          "btn btn-success" />
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <br />
  </div>

  <div class="col-md-12" id="invoiceContainer">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          Available Campaign
        </div>

        <div class="panel-body">
            <div class="col-md-12">
           <table id="campaignTable" class="table">
               
           </table>
            </div>
        </div>
      </div>
    </div>

    <div class="col-md-7">
      <div class="panel panel-default">
        <div class="panel-heading">
            <span class="">
                <div class="col-md-4"><input class="form-control" placeholder="Invoice Number" id="invoiceNumber" type="text" /></div> 
                <input class="btn btn-default" value="Add Custom Entry" id="customEntry" type="text" />
            </span>
        </div>
          
        <div class="panel-body">
            <div class="col-md-12" style="height:224px;overflow:auto;">
           <table id="invoiceTable" class="table">
               
           </table>
            </div>
        </div>

        <div class="panel-footer text-right">
            <h4>Sub Total :&nbsp;<span id="subtotal"></span><br>
            Tax :&nbsp;<span id="tax"></span><br>
            Total :&nbsp;<span id="total"></span></h4>
        </div>
      </div>
    </div>
  </div>

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

