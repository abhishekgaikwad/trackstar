<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-theme.css" />
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>


<body>
<nav class="navbar navbar-default" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Trackstar</a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li <?php if(Yii::app()->controller->action->id=="index") echo 'class="active"'; ;?> ><a href="<?php  echo $this->createUrl('home/index'); ?>">Home</a></li>
            <li <?php if(Yii::app()->controller->action->id=="addTransaction") echo 'class="active"'; ;?> ><a href="<?php  echo $this->createUrl('home/addTransaction'); ?>">Transaction</a></li>
            <li <?php 
                if(Yii::app()->controller->action->id=="addMerchant" || Yii::app()->controller->action->id=="addUser" || Yii::app()->controller->action->id=="addAffiliate") echo  'class="active dropdown"' ;
                else echo 'class="dropdown"' ; ?>><a href="#" class="dropdown-toggle" data-toggle="dropdown">Add<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?php  echo $this->createUrl('home/addMerchant'); ?>">Add Merchant</a></li>
                <li><a href="<?php  echo $this->createUrl('home/addAffiliate'); ?>">Add Affiliate</a></li>
                <li><a href="<?php  echo $this->createUrl('home/addUser'); ?>">Add User</a></li>
            </ul>		
            </li>
            <li <?php if(Yii::app()->controller->action->id=="generateReports") echo 'class="active"'; ;?> ><a href="<?php  echo $this->createUrl('home/generateReports'); ?>">Reports</a></li>
            <li <?php 
                if(Yii::app()->controller->action->id=="addCampaign" || Yii::app()->controller->action->id=="updateCampaign") echo  'class="active dropdown"' ;
                else echo 'class="dropdown"' ; ?>><a href="#" class="dropdown-toggle" data-toggle="dropdown">Campaign<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?php  echo $this->createUrl('campaign/addCampaign'); ?>">Add</a></li>
                <li><a href="<?php  echo $this->createUrl('campaign/updateCampaign'); ?>">Update</a></li>
                <li><a href="<?php  echo $this->createUrl('campaign/closedCampaign'); ?>">Closed</a></li>
            </ul>		
            </li>
	  
	  
	  
      <li><a href="<?php echo $this->createUrl('home/logout'); ?>">Logout</a></li>      
    </ul>
	
    <ul class="nav navbar-nav navbar-right">
        <li><a>Current User : <?php echo Yii::app()->user->getId(); ?></a></li>
    </ul>
    
  </div><!-- /.navbar-collapse -->
</nav>
<?php echo $content; ?>

</body>
</html>
