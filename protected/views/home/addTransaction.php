<script>
	$(window).ready(function(){
		var currentMerchant ;
		var currentAffiliate ;
		
		if($("#fixedValue").val()==0) {
			$("#commission-form").hide();
			$("#comtable").hide();
		}
		else {
			$("#transactionType").hide();
			if($("#fixedType").val()=="merchant") {
				var i = $("#fixedValue").val() ;
				$("#Commission_merchant_id").val(i);
				$("#Commission_merchant_id").attr('disabled','disabled');
			} else {
				var i = $("#fixedValue").val() ;
				$("#Commission_affiliate_id").val(i);
				$("#Commission_affiliate_id").attr('disabled','disabled');
			}
		}		
		
		
		$("#merchantButton").click(function(){
			currentMerchant = $("#merchantSelect").val();
			$("#transactionType").hide("slow");
			$("#Commission_merchant_id").val(currentMerchant);
			$("#Commission_merchant_id").attr('disabled','disabled');
			$("#fixedType").val("merchant");
			$("#fixedValue").val(currentMerchant);
			$("#commission-form").show("slow");
			$("#comtable").delay("500").show("slow");
		});
		
		$("#affiliateButton").click(function(){
			currentAffiliate = $("#affiliateSelect").val();
			$("#transactionType").hide("slow");
			$("#Commission_affiliate_id").val(currentAffiliate);
			$("#Commission_affiliate_id").attr('disabled','disabled');
			$("#fixedType").val("affiliate");
			$("#fixedValue").val(currentAffiliate);
			$("#commission-form").show("slow");
			$("#comtable").show("slow");
		});
		
		$('#commission-form').submit(function(){
			$("#Commission_merchant_id").removeAttr('disabled');
			$("#Commission_affiliate_id").removeAttr('disabled');
		});
	});	
	
</script>

<div id="transactionType" class="col-md-8 col-md-offset-2">
	<div class="col-md-6">
		
		<select id="merchantSelect" class="form-control">
			<?php 
			foreach($merchant as $i) {
				echo "<option value=\"$i->id\">$i->merchant_name</option>";
			}
		?>
		</select>
		<br />
		<div class="text-center">
		<input type="button" class="btn btn-default" value="Add by Merchant" id="merchantButton"> </input>
		</div>
	</div>
	<div class="col-md-6">
		
		<select id="affiliateSelect" class="form-control">
			<?php 
			foreach($affiliate as $i) {
				echo "<option value=\"$i->id\">$i->affiliate_name</option>";
			}
		?>
		</select>
		<br />
		<div class="text-center">
		<input type="button" class="btn btn-default" value="Add by Affiliate" id="affiliateButton"> </input>
		</div>
	</div>
</div>

<script>
$(function() {
    $( "#datepicker" ).datepicker();
    $( "#datepicker" ).datepicker('setDate', new Date());
  });
  </script>
  <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'commission-form',
	//'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
<div class="col-md-12">
		<?php echo $form->error($model,'date_of_report',array('class'=>'alert alert-danger',)); ?>
		<?php echo $form->error($model,'no_of_clicks',array('class'=>'alert alert-danger',)); ?>

		<?php echo $form->error($model,'no_of_sales',array('class'=>'alert alert-danger',)); ?>
		<?php echo $form->error($model,'commission',array('class'=>'alert alert-danger',)); ?>

</div>
<div class="col-md-12">
<?php if(isset($message)) {
	echo '<div class="alert alert-warning alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <strong>' . $message . '</strong></div>' ;

}?>

	<div class ="col-md-2">
		<?php 
			$mer = array();
			foreach($merchant as $i) {
				$mer[$i->id] = $i->merchant_name;
			}
		?>
		<?php echo $form->dropDownList($model,'merchant_id', $mer ,array('class'=>'form-control',)); ?>
	</div>
	
	<div class ="col-md-2">
		<?php 
			$aff = array();
			foreach($affiliate as $i) {
				$aff[$i->id] = $i->affiliate_name;
			}
		?>
		<?php echo $form->dropDownList($model,'affiliate_id',$aff,array('class'=>'form-control',)); ?>
	</div>
	
	<div class ="col-md-2">
		<?php echo $form->textField($model,'date_of_report',array('class'=>'form-control','placeholder'=>'Enter Date of Report','id'=>'datepicker',)); ?>
	</div>
	
	<div class ="col-md-1">
		<?php echo $form->textField($model,'no_of_clicks',array('class'=>'form-control','placeholder'=>'Clicks','id'=>'',)); ?>
	</div>
	
	<div class ="col-md-1">
		<?php echo $form->textField($model,'no_of_sales',array('class'=>'form-control','placeholder'=>'Sales','id'=>'',)); ?>
	</div>
	
	<div class ="col-md-2">
		<?php echo $form->textField($model,'commission',array('class'=>'form-control','placeholder'=>'Commission','id'=>'',)); ?>
	</div>
	
	<div class ="col-md-2">
		<?php echo CHtml::submitButton('Save Transaction',array('class'=>'btn btn-default',)); ?>
	</div>
	
	<input type="hidden" id="fixedType" name="fixedType" value="<?php if(isset($fixedType)) echo "$fixedType" ; ?>">
	<input type="hidden" id="fixedValue" name="fixedValue" value="<?php if(isset($fixedValue)) echo "$fixedValue" ; ?>">
 <?php $this->endWidget(); ?>
</div>
<br><br>

 <div class="col-md-8 col-md-offset-2 ">
 <table id="comtable" class="table table-striped">
 
 <tr><th>Merchant Name</th><th>Affiliate Name</th><th>Date of Transaction</th><th>No of Clicks</th><th>No of Sales</th><th>Commission</th></tr>
<?php 
	foreach($present as $item) {
		echo '<tr><td>' . $item->merchant->merchant_name . '</td><td>' . $item->affiliate->affiliate_name . '</td><td>' . $item->date_of_report . '</td><td>'  . $item->no_of_clicks . '</td><td>' . $item->no_of_sales . '</td><td>' . $item->commission . '</td></tr>' ;
	}
?>
</table> 
 </div>
