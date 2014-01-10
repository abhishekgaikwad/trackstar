<script>
 var campaignArray = new Array();
 var invoiceArray = new Array();
 var customArray = new Array();
 var totalGlobal = 0 ;
 var taxGlobal = 0 ;
$(window).ready(function(){
       $("#invoiceContainer").hide();
       $("#getCampaign").click(function(){
            campaignArray = new Array();
            invoiceArray = new Array();
            customArray = new Array();
            totalGlobal = 0 ;
            taxGlobal = 0 ;
           $("#subtotal").html("");
           $("#tax").html("");
           $("#total").html("");
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
                
                var detailHead = '<tr><th>Description</th><th>Month/Date</th><th>Tax Percent</th><th>Amount</th><th>Tax</th></tr>' ;
                $("#invoiceTable").append(detailHead);
                
                setActions() ;
                
            }
            
        });
       });
       setCustomActions() ;
       $("#save").click(function(){save();});
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
                    trcontent += "<td><input type='text' id='value"+ (campaignArray[trid])[0] +"' class='form-control valuefield' value='"+ (campaignArray[trid])[5] +"'></td>" ;
                    var tempTax = 0.01 * 12.36 * (campaignArray[trid])[3] ;
                    
                    trcontent += "<td class='taxAmt'>" + tempTax.toFixed(2) + "</td>" ;
                    trcontent += "</tr>" ;
                  $("#invoiceTable").append(trcontent);
                  trcontent = "";
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
      

}

function setCustomActions() {
      $("#customEntry").click(function() {         
            //alert($(".customDesc").length)
            var cusID = $(".customDesc").length ;
            var trcontent = "<tr id='customInvoice"+cusID+"'><div class='col-md-8'>" ;
                    trcontent += "<td><input type='text' class='form-control customDesc' id='customDesc" + cusID + "'></td>" ;
                    trcontent += "<td><input type='text' class='form-control customDate' id='customDate" + cusID + "'></td>" ;
                    trcontent += "<td><input type='text' class='form-control customTaxfield' value='12.36' id='customTax" + cusID + "'></td>" ;
                    trcontent += "<td><input type='text' class='form-control customValuefield' value='0' id='customValue" + cusID + "'></td>" ;
                    trcontent += "<td class='customTaxAmt'>0" + "</td>" ;
                    trcontent += "</tr>" ;
            $("#invoiceTable").append(trcontent);
            trcontent = "" ;
            
            $( ".customDate" ).datepicker( {
                changeMonth: true,
                changeYear: true,
                showButtonPanel: false,
                dateFormat: 'dd MM yy',
            });
            $( ".customDate" ).datepicker('setDate', new Date());
            
            calculate();
            
            $(".customTaxfield").blur(function(){
                var tax = parseFloat($(this).val()) * 0.01 ;
                var id = parseInt($(this).attr('id').replace("customTax",""));
                var amt = parseInt($("#customValue"+id).val());
                var ans = (amt*tax) ;
                $(this).parent().next().next().html(ans.toFixed(2));
                calculate();
            });
            
            $(".customValuefield").blur(function(){
                //alert("hi");
                var amt = parseInt($(this).val()) ;
                var id = parseInt($(this).attr('id').replace("customValue",""));
                var tax = parseFloat($("#customTax"+id).val()) * 0.01 ;
                var ans = (amt*tax) ;
                $(this).parent().next().html(ans.toFixed(2));
                calculate();
            });
      });
}

function calculate() {
    var total = 0 ;
    var tax = 0 ;
    var index ;
//    for(var i=0;i<invoiceArray.length;i++){
//        index = invoiceArray[i] ;
//        total += parseInt((campaignArray[index])[3]) ;
//    }
    $(".valuefield").each(function(){
        total += parseInt($(this).val()) ;
    });
    $(".customValuefield").each(function(){
        total += parseInt($(this).val()) ;
    });
    
    $(".taxAmt").each(function(){
        tax += parseFloat($(this).html()) ;
    });
    $(".customTaxAmt").each(function(){
        tax += parseFloat($(this).html()) ;
    });
    var ans = tax+total ;
    $("#subtotal").html(total);
    $("#totalTax").html(tax.toFixed(2));
    $("#total").html(ans.toFixed(2));
    //alert(total + " " + tax);
}

function save() {
    //alert(invoiceArray) ;
    calculate();
    var invoice = new Array();
    var error = "" ;
    var invoice_number ;
    var affiliate_id ;
    var date_of_invoice ;
    var campaign = new Array() ;
    
    if($("#invoiceNumber").val())
        invoice_number = $("#invoiceNumber").val() ;
    else
        error += "Enter Invoice Number \n";
    var i = 1 ;
    $(".customDesc").each(function(){
        if($(this).val()=="")
            error += "Enter Descprition for Custom Filed " + (i++) +"\n";
    });
    
    affiliate_id = $( "#affiliateSelect" ).val() ;
    date_of_invoice = $( "#datepicker" ).val() ;
    
    
    var temp ;
    var campaign_id ;
    var value ;
    var taxPercent ;
    for(var i=0;i<invoiceArray.length;i++){
        index = invoiceArray[i] ; 
        campaign_id = parseInt((campaignArray[index])[0]) ;  
        value = parseInt($("#value"+campaign_id).val()) ;  
        taxPercent = parseFloat($("#tax"+campaign_id).val()) ;
        //alert(taxPercent);
        temp = {
            campaign_id: campaign_id,
            value: value,
            tax: taxPercent,
        };
        campaign.push(temp) ;
    }
    
    var desc ;
    var date_comp ;
    var temp_id ;
     $(".customTaxfield").each(function(){
         temp_id = parseInt($(this).attr('id').replace("customTax",""));
         desc = $("#customDesc"+temp_id).val() ;
         date_comp = $("#customDate"+temp_id).val() ;
         taxPercent = $(this).val() ;
         value = $("#customValue"+temp_id).val() ;
         
         temp = {
            description: desc,
            date_of_component: date_comp,
            tax: taxPercent,
            value: value,
        };
        campaign.push(temp) ;
     });
    
    var dataArr = {
        invoice_number: invoice_number,
        affiliate_id: affiliate_id,
        date_of_invoice: date_of_invoice,
        service_value: parseFloat($("#subtotal").html()),
        service_tax: parseFloat($("#totalTax").html()),
        total_value: parseFloat($("#total").html()),
        campaign: campaign,
    };
    
    var data = JSON.stringify(dataArr);
    
    if(error == "") {
        $.ajax({
            type: 'POST',
            url: "<?php  echo $this->createUrl('invoice/ajaxSave'); ?>",
            data: data,
            success: function(res) { 
                //alert(res);
                if(res == "saved") {
                    alert("Invoice Saved");
                    $("#save").attr("disabled", "disabled");
                    $("#save").value("Invoice Saved");
                    
                } else {
                    alert("Something Went Wrong") ;
                }
            },
            //contentType: "application/json",
           // dataType: 'json'
        });
    }
    else {
        alert(error) ;
        //alert(JSON.stringify(data)) ;
    }
}

    
    
</script>

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
            Tax :&nbsp;<span id="totalTax"></span><br>
            Total :&nbsp;<span id="total"></span></h4>
            <input type="button" id="save" value="Save" class=
          "btn btn-success" />
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

