﻿<?php 

if(isset($_POST["txtContent"])) {

echo "Content: " . $_POST["txtContent"];

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <script language="javascript" src='scripts/innovaeditor.js'></script>

    <link href="styles/simple.css" rel="stylesheet" type="text/css" />
</head>
<body style="margin:50px;">

Default Example | <a href="default_full.htm">More Examples</a> | <a href="docs.htm">Documentation</a> | <a href="docs_aspnet.htm">ASP.NET Documentation</a> | <a href="default_aspnet.aspx">ASP.NET Example</a>

<h2>Default Example</h2>
<form method="post">
<textarea id="txtContent" name="txtContent" rows=4 cols=30>
    <p>Hello World!</p>
</textarea>

<script language="javascript" type="text/javascript">
    var oEdit1 = new InnovaEditor("oEdit1");

    /*Apply stylesheet for the editing content*/
    oEdit1.css = "styles/simple.css";
	oEdit1.useBR=false;
    /*Render the editor*/
    oEdit1.REPLACE("txtContent");

</script>
<input type="submit" value="submit" />  

</form>
</body>
</html>