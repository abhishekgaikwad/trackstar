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
            update();
        }
    });
    $( "#datepicker" ).datepicker('setDate', new Date());
  });
  </script>
<script>
        $(window).ready(function(){
                var currentMerchant ;
                var currentAffiliate ;

                update() ;
                   
                $('#merchantSelect').change(function(){
                    
                    update() ;
                     
                 });
                 

                
                
                
                $('#campaign-form').submit(function(){
                        $("#Commission_merchant_id").removeAttr('disabled');
                        $("#Commission_affiliate_id").removeAttr('disabled');
                });
        });        
        
        
        function update() {
                $("#affiliateSelect").html("");
                var url = "<?php  echo $this->createUrl('campaign/ajaxGetAffiliate'); ?>";
                var data = {
				period: $( "#datepicker" ).val(),
				merchant: $('#merchantSelect').val(),	
 
			};
            $.ajax({
				type: "POST",
				url: url,
				data: data,
				success: function(data, textStatus, jqXHR) {
                                    var myArray = jQuery.parseJSON(data);
                                    //alert(myArray[1][1]);
                                    for(var i=0;i<myArray.length;i++){
                                        $("#affiliateSelect").append($("<option>", { 
                                            value: myArray[i][1],
                                            text : myArray[i][0]
                                        }));
                                    }
                                    
                                }
                     });
        }
</script>



<style>
.ui-datepicker-calendar {
    display: none;
    }
</style>

  <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'campaign-form',
        //'enableClientValidation'=>true,
        'clientOptions'=>array(
                'validateOnSubmit'=>true,
        ),
)); ?>
<div class="col-md-12">
                <?php echo $form->error($model,'campaign_date',array('class'=>'alert alert-danger',)); ?>
                
                <?php echo $form->error($model,'estimated_value',array('class'=>'alert alert-danger',)); ?>

</div>
<div class="col-md-12">
<?php if(isset($message)) {
        echo '<div class="alert alert-warning alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <strong>' . $message . '</strong></div>' ;

}?>

        <div class ="col-md-2 col-md-offset-1">
                <?php echo $form->textField($model,'campaign_date',array('class'=>'form-control','placeholder'=>'Enter Date of Campaign','id'=>'datepicker',)); ?>
        </div>
    
        <div class ="col-md-2 ">
                <?php 
                        $mer = array();
                        foreach($merchant as $i) {
                                $mer[$i->id] = $i->merchant_name;
                        }
                ?>
                <?php echo $form->dropDownList($model,'merchant_id', $mer ,array('class'=>'form-control', 'id'=> 'merchantSelect',)); ?>
        </div>
        
        <div class ="col-md-2">

                <?php echo $form->dropDownList($model,'affiliate_id',array(),array('class'=>'form-control', 'id'=> 'affiliateSelect',)); ?>
        </div>
        

        
        
        
        <div class ="col-md-2">
                <?php echo $form->textField($model,'estimated_value',array('class'=>'form-control','placeholder'=>'Value','id'=>'',)); ?>
        </div>
        
        <div class ="col-md-2">
                <?php echo CHtml::submitButton('Save Campaign',array('class'=>'btn btn-default',)); ?>
        </div>
        
        <input type="hidden" id="fixedType" name="fixedType" value="<?php if(isset($fixedType)) echo "$fixedType" ; ?>">
        <input type="hidden" id="fixedValue" name="fixedValue" value="<?php if(isset($fixedValue)) echo "$fixedValue" ; ?>">
 <?php $this->endWidget(); ?>
</div>
<br><br>

 <div class="col-md-8 col-md-offset-2 ">
 <table id="comtable" class="table table-striped">
 
 <tr><th>Merchant Name</th><th>Affiliate Name</th><th>Date of Campaign</th><th>Estimated Value</th></tr>
<?php 
        foreach($present as $item) {
                echo '<tr><td>' . $item->merchant->merchant_name . '</td><td>' . $item->affiliate->affiliate_name . '</td><td>' . date("F Y", strtotime($item->campaign_date)) .'</td><td>' . $item->estimated_value . '</td></tr>' ;
        }
?>
</table> 
 </div>