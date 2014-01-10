
<script>
$(function() {
	$( "#datepicker1" ).datepicker();
	$( "#datepicker1" ).datepicker('setDate', new Date('01-01-2013'));
    $( "#datepicker2" ).datepicker();
    $( "#datepicker2" ).datepicker('setDate', new Date());
  });
  </script>
<script>
	$(window).ready(function(){ 
	$("#specficInterval").attr('disabled','disabled');
	$("#start").attr('disabled','disabled');
	$("#startDisabled").attr('disabled','disabled');
	var i = $("#merchantSelect") ;
	var j = $("#affiliateSelect") ;
	var k = $("#startDisabled");
	
		$("#interval1,#interval2").click(function(){
			$("#specficInterval").attr('disabled','disabled');
		});
		
		$("#interval3").click(function(){
			$("#specficInterval").removeAttr('disabled');
		});
		
		$("#selectionType1").click(function(){
			$("#replace").html(k) ;			
		});
		
		$("#selectionType2").click(function(){
			$("#start").removeAttr('disabled');			
			$("#replace").html(i) ;			
		});
		
		$("#selectionType3").click(function(){
			$("#start").removeAttr('disabled');			
			$("#replace").html(j) ;			
		});
		
		
		$("#ajaxRequest").click(function(){
			var intervalType = $('input:radio[name="interval"]:checked').val() ;
			var intervalValue ;
			
			if(intervalType=="specific")
				intervalValue = $("#specficInterval").val();
				<!--<?php echo '$("#specficInterval").val();' ?>-->
			else
				intervalValue = 0 ;
			
			var selectionType = $('input:radio[name="selectionType"]:checked').val() ;
			
			var selectionValue ;
			
			if(selectionType=="merchant")
				selectionValue = $(merchantSelect).val();
			else if (selectionType=="affiliate")
				selectionValue = $(affiliateSelect).val();
			else 
				selectionValue = 0 ;
			
			var url = "<?php  echo $this->createUrl('home/ajaxReports'); ?>";
			var data = {
				intervalType: intervalType,
				selectionType: selectionType,
				intervalValue: intervalValue,
				selectionValue: selectionValue,
				dateStart: $( "#datepicker1" ).val(),
				dateEnd: $( "#datepicker2" ).val(),
				
			};
			// $.getJSON( url, data, function( data ) {
			  // var items = [];
			  // $.each( data, function( key, val ) {
				// items.push( "<li id='" + key + "'>" + val + "</li>" );
			  // });
			 
			  // $( "<ul/>", {
				// "class": "my-new-list",
				// html: items.join( "" )
			  // }).appendTo( "body" );
			// });
			//alert(data.intervalType)
			$.ajax({
				type: "POST",
				url: url,
				data: data,
				success: function(data, textStatus, jqXHR) 
				{
					//alert("hello");
					//$("#result").hide('slow');
					var $div = $("#result") ;
					
					$div.html("");
					
					$div.append("<table id=\"resultTable\" class=\"table table-striped\">");
					$("#resultTable").append("<tr><th>Sr</th><th>Date Interval</th><th>Clicks</th><th>Sales</th><th>Commission</th></tr>");
					var myArray = jQuery.parseJSON(data);
					var click = 0 ;
					var sales = 0 ;
					var comm = 0 ;
					for(var i=0;i<myArray.length;i++){
                       $("#resultTable").append("<tr><td>" + (i+1) + "</td><td>"+myArray[i].date_of_report+"</td><td>" +myArray[i].no_of_clicks+"</td><td>" + myArray[i].no_of_sales+"</td><td>" + myArray[i].commission+"</td></tr>");
					   click += parseInt(myArray[i].no_of_clicks) ;
					   sales += parseInt(myArray[i].no_of_sales) ;
					   comm += parseInt(myArray[i].commission) ;
                    }
					$("#resultTable").children().last().append("<tr><th><a class=\"btn btn-success\" href=\"#\" id=\"export\">Download</a></th><th></th><th>" + click + "</th><th>" + sales + "</th><th>" + comm + "</th></tr>" ) ;
					//var myArray = jQuery.parseJSON(data);
					//$div.append("</table>");
					//$div.show('slow');
					
					function exportTableToCSV($table, filename) {
						//alert("hello");
						var $rows = $table.find('tr:has(td)'),

							// Temporary delimiter characters unlikely to be typed by keyboard
							// This is to avoid accidentally splitting the actual contents
							tmpColDelim = String.fromCharCode(11), // vertical tab character
							tmpRowDelim = String.fromCharCode(0), // null character

							// actual delimiter characters for CSV format
							colDelim = '","',
							rowDelim = '"\r\n"',

							// Grab text from table into CSV formatted string
							csv = '"' + $rows.map(function (i, row) {
								var $row = $(row),
									$cols = $row.find('td');

								return $cols.map(function (j, col) {
									var $col = $(col),
										text = $col.text();

									return text.replace('"', '""'); // escape double quotes

								}).get().join(tmpColDelim);

							}).get().join(tmpRowDelim)
								.split(tmpRowDelim).join(rowDelim)
								.split(tmpColDelim).join(colDelim) + '"',

							// Data URI
							csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
							//alert(csvData);
						$(this)
							.attr({
							'download': filename,
								'href': csvData,
								'target': '_blank'
						});
					}
					
					
					$("#export").click(function () {
						// CSV
						//alert("hello");
						exportTableToCSV.apply(this, [$('#resultTable'), 'export.csv']);
						
						// IF CSV, don't do event.preventDefault() or return false
						// We actually need this to be a typical hyperlink
					});
					

				},
				
			});
		});
		
		

    // This must be a hyperlink
    
		
		
	});
	
</script>
<div style="display: none;"><select id="merchantSelect" class="form-control">
			<?php 
			foreach($merchant as $i) {
				echo "<option value=\"$i->id\">$i->merchant_name</option>";
			}
		?>
		</select> 
<select id="affiliateSelect" class="form-control">
			<?php 
			foreach($affiliate as $i) {
				echo "<option value=\"$i->id\">$i->affiliate_name</option>";
			}
		?>
		</select>
<select id="startDisabled" class="form-control"></select>   //redundant
		</div>

<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
  <div class="panel-heading">Criteria</div>
  <div class="panel-body">
		<form name="myForm">
	<div class="col-md-6">
    <div class="input-group">
	  <span class="input-group-addon">
        <input id="interval1" value="weekly" name="interval" type="radio" checked="checked"> Weekly  <!--for preselecting weekly-->
      </span>
	  <span class="input-group-addon">
        <input id="interval2" value="monthly" name="interval" type="radio"> Monthly
      </span>
      <span class="input-group-addon">
        <input id="interval3" value="specific" name="interval" type="radio"> Specific Interval
      </span>
      <input type="text" id="specficInterval" class="form-control">
	  
    </div><!-- /input-group -->
	</div><!-- /.col-lg-6 -->
 
		
	<div class="col-md-6">
    <div class="input-group">
	  <span class="input-group-addon">
        <input id="selectionType1" value="general" class="" name="selectionType" type="radio" checked="checked"> General
      </span>
	  <span class="input-group-addon">
        <input id="selectionType2" value="merchant" name="selectionType" type="radio"> Merchant
      </span>
      <span class="input-group-addon">
        <input id="selectionType3" value="affiliate" name="selectionType" type="radio"> Affiliate
      </span>
	 <div id="replace"><select id="start" class="form-control"></select></div>   <!--Used to display the freezed zone besides Affiliates-->
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
  </div>
  <div class="panel-footer">
	<div class="">
		<div class="col-md-3">
			<input type="text" class="form-control" id="datepicker1">
		</div>
		<div class="col-md-1">
		<input class="btn btn-default" disabled="disabled" type="button" id="" value="To">
		</div>
		<div class="col-md-3">
			<input type="text" class="form-control" id="datepicker2">
		</div>
		<div class="text-right">
		<input class="btn btn-default" type="button" id="ajaxRequest" value="Get Report">
		</div>
	</div>
</div>
</div>
</form>
</div>

<div id="result" class="col-md-8 col-md-offset-2">

</div>

