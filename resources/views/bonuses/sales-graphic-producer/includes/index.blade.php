<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sales Graphics Producer</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    	
	$('a.gotoStep1').click(function () {
      	$('.step2').fadeOut(0);
		$('.step1').fadeIn(800);
		return false;
	});
	
	$('a.gotoStep2').click(function () {
   
		$('#wizard').submit();
		return false;
	});
	
	$('a.gotoStep3').click(function () {
      	$('.step2').fadeOut(0);
		$('.step4').fadeOut(0);
		$('.step3').fadeIn(800);
		return false;
	});
	
	$('a.gotoStep4').click(function () {
      	$('.step3').fadeOut(0);
		$('.step5').fadeOut(0);
		$('.step4').fadeIn(800);
		return false;
	});
	
	$('a.gotoStep5').click(function () {
      	$('.step4').fadeOut(0);
		$('.step5').fadeIn(800);
		return false;
	});
	
	$('a.gotoStep6').click(function () {
      	$('.step5').fadeOut(0);
		$('.step6').fadeIn(800);
		 
		return false;
	});
	
	
  });
</script>

</head>

<body>

<div id="wrapper">

<div id="headerArea"> <a href="index.php"><img src="images/header.png" title="Click to Go to Home Page" /></a>
</div>

<div id="stepArea" style="margin-left:22px; margin-right:22px;">

<form action="create.php" method="POST" id="wizard">

	<div class="step1" style="">
		
        <div class="headlineArea"><img src="images/step1.png" /></div>
        
        <div class="contentWizard" style="height:250px;">
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><div style="margin-left:20px; margin-top:20px;">
            <span style="font-size:14px; font-weight:bold; color:#666; ">Headline Creator</span>
            <img src="images/template1.png"  style="margin-top:10px;" /><br />
            <input name="templateSelect" type="radio" style="margin-left:65px; margin-top:10px;" value="1" checked="checked"  />
            </div>
            </td>
            <td><div style="margin-left:20px; margin-top:20px;">
            <span style="font-size:14px; font-weight:bold; color:#666;">Buy Now Creator</span>
            <img src="images/template2.png"  style="margin-top:10px;" /><br />
            <input type="radio" name="templateSelect" value="2" style="margin-left:65px; margin-top:10px;"  />
            </div></td>
            <td><div style="margin-left:20px; margin-top:20px;">
            <span style="font-size:14px; font-weight:bold; color:#666; ">Belcher Button Creator</span>
            <img src="images/template3.png"  style="margin-top:10px;" /><br />
            <input type="radio" name="templateSelect" value="3" style="margin-left:65px; margin-top:10px;"  />
            </div></td>
            <td><div style="margin-left:20px; margin-top:20px;">
            <span style="font-size:14px; font-weight:bold; color:#666; ">Guarantee Creator</span>
            <img src="images/template4.png"  style="margin-top:10px;" /><br />
            <input type="radio" name="templateSelect" value="4" style="margin-left:65px; margin-top:10px;"  />
            </div></td>
          </tr>
        </table>

        
        </div>
        
        <div class="buttonArea">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
              <tr>
                <td width="47%"><span style=" font-size:12px; color:#999; ">* once you create the graphic right click &amp; go to save image as...</span></td>
                <td width="53%" align="right"><a href="" class="gotoStep2" ><img src="images/next.png" border="0"  /></a></td>
              </tr>
            </table>
        </div>

	</div>
    
    <!-- STEP 2 -->
    
    <div class="step2" style=" display:none;">
		
        <div class="headlineArea"><img src="images/step2.png" /></div>
        
        <div class="contentWizard" style="height:250px;">
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%">
            <div style="margin-top:20px; margin-left:15px;">
            
            	<span style="font-size:14px; color:#666; font-weight:bold;">Site Title </span><span style="font-size:14px; color:#666; ">- Appears at the top of the page</span><br />
                <input type="text" name="metaTitle" class="formArea">
                
                <br /><br /><br />
                
                <span style="font-size:14px; color:#666; font-weight:bold;">Meta Keywords </span><span style="font-size:14px; color:#666; ">- Keywords that relate to your offer</span><br />
                <input type="text" name="metaKeywords" class="formArea">
            
            
            
            </div>
            </td>
            <td><div style="margin-top:20px; margin-left:15px;">
            
            	<span style="font-size:14px; color:#666; font-weight:bold;">Meta Description </span><span style="font-size:14px; color:#666; ">- Blurb about your site/offer</span><br />
                <input type="text" name="metaDesc" class="formArea">
                
                <br /><br /><br />
                
                <span style="font-size:14px; color:#666; font-weight:bold;">Theme Color </span><span style="font-size:14px; color:#666; ">- choose site color</span><br />
                <select name="themeColor" class="formArea" style="width:265px;">
                <option value="1">black</option>
                <option value="2">blue</option>
                <option value="3">green</option>
                <option value="4">red</option>
                <option value="5">orange</option>
                </select>
                
            
            
            
            </div></td>
          </tr>
        </table>
      
        </div>
        
        <div class="buttonArea">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
              <tr>
                <td><a href="" class="gotoStep1" ><img src="images/prev.png" border="0" /></a></td>
                <td align="right"><a href="" class="gotoStep3" ><img src="images/next.png" border="0"  /></a></td>
              </tr>
            </table>
        </div>

	</div>
    
     <!-- STEP 3 -->
    
    <div class="step3" style=" display:none;">
		
        <div class="headlineArea"><img src="images/step3.png" /></div>
        
        <div class="contentWizard" style="height:250px;">
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%" valign="top">
            <div style="margin-top:20px; margin-left:15px;">
            
            	<span style="font-size:14px; color:#666; font-weight:bold;">Sub-Headline</span><span style="font-size:14px; color:#666; ">- Above the fold</span><br />
                <input type="text" name="subHeadline" class="formArea">
                
                <br /><br /><br />
                
                <span style="font-size:14px; color:#666; font-weight:bold;">Main Headline </span><span style="font-size:14px; color:#666; ">- Above the fold</span><br />
                <textarea name="mainHeadline" class="formArea" style="height:50px;" >Text Area!</textarea>
             
            
            
            
            </div>
            </td>
            <td valign="top"><div style="margin-top:20px; margin-left:15px;">
            
            	<span style="font-size:14px; color:#666; font-weight:bold;">Optin Headline </span><span style="font-size:14px; color:#666; ">- Above optin box</span><br />
                <textarea name="optinHeadline" class="formArea" style="height:152px;" >Text Area!</textarea>

            </div></td>
          </tr>
        </table>
        
        
        
        </div>
        
        <div class="buttonArea">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
              <tr>
                <td><a href="" class="gotoStep2" ><img src="images/prev.png" border="0" /></a></td>
                <td align="right"><a href="" class="gotoStep4" ><img src="images/next.png" border="0"  /></a></td>
              </tr>
            </table>
        </div>

	</div>
    
     <!-- STEP 4 -->
    
    <div class="step4" style=" display:none;">
		
        <div class="headlineArea"><img src="images/step4.png" /></div>
        
        <div class="contentWizard" style="height:250px;">
        
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%" valign="top">
            <div style="margin-top:20px; margin-left:15px; margin-right:15px;">
         
            	<p><span style="font-size:14px; color:#666; font-weight:bold;">How to setup video code </span><Br /><br />
            	  <span style="font-size:14px; color:#666;">Your embed code must have the full embed code tag. If you copy and paste the code from youtube, make sure you have the entire bit of code into the form.</span></p>
            	<p><span style="font-size: 12px; color: #666"><strong>Width:</strong> 585 px</span></p>
            	<p><span style="font-size: 12px; color: #666"><strong>Height:</strong> 300 px</span></p>
            </div>
            </td>
            <td valign="top"><div style="margin-top:20px; margin-left:15px;">
    
                <span style="font-size:14px; color:#666; font-weight:bold;">Video Embed Code </span><br />
                <textarea name="videoCode" class="formArea" style="height:130px; width:330px;" >embed code</textarea>

            </div></td>
          </tr>
        </table>
        

        </div>
        
        <div class="buttonArea">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
              <tr>
                <td><a href="" class="gotoStep3" ><img src="images/prev.png" border="0" /></a></td>
                <td align="right"><a href="" class="gotoStep5" ><img src="images/next.png" border="0"  /></a></td>
              </tr>
            </table>
        </div>

	</div>
    
     <!-- STEP 5 -->
    
    <div class="step5" style=" display:none;">
		
        <div class="headlineArea"><img src="images/step5.png" /></div>
        
        <div class="contentWizard" style="height:250px;">
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%" valign="top">
            <div style="margin-top:20px; margin-left:15px; margin-right:15px;">
         
            	<p><span style="font-size:14px; color:#666; font-weight:bold;">How to setup form code</span><Br />
            	  <br />
            	  <span style="font-size:14px; color:#666;">Your auto-responder service will give you some html code for your optin area. Login to your AR account, and copy and paste that code into the form on the right.</span></p>
            	<p><span style="font-size: 14px; color: #666">This will show up in the template in a high converting area!</span></p>
            </div>
            </td>
            <td valign="top"><div style="margin-top:20px; margin-left:15px;">
    
                <span style="font-size:14px; color:#666; font-weight:bold;">Optin Form Code </span><br />
                <textarea name="optinCode" class="formArea" style="height:130px; width:330px;" >optin code here</textarea>

            </div></td>
          </tr>
        </table>
        
        
        
        
        </div>
        
        <div class="buttonArea">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
              <tr>
                <td><a href="" class="gotoStep4" ><img src="images/prev.png" border="0" /></a></td>
                <td align="right"><a href="" class="gotoStep6" ><img src="images/next.png" border="0"  /></a></td>
              </tr>
            </table>
        </div>

	</div>
    
     <!-- STEP 6 -->
    
    <div class="step6" style="display:none;">
		
        <div class="headlineArea"><img src="images/step6.png" /></div>
        
        <div class="contentWizard" style="height:250px;">
        
        
        
        
        
        </div>
        
        <div class="buttonArea">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
              <tr>
                <td></td>
                <td align="right"></td>
              </tr>
            </table>
        </div>

	</div>
    
    
</form>
</div>



</div>

</body>
</html>
