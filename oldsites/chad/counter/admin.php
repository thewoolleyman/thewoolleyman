<?
$version="1.2.0"; 
?>
<html>
<head>
<title>The PHP - MySQL Hit counter Admin Page.</title>
</head>
<link rel="stylesheet" href="/stylesheet/css"> 

<body bgcolor="#CFCFCF" text="black" link="white" vlink="blue">
<form action="admin.php" method="post">

<? 

Print "$individual_files";
function CreateDatabaseAndTables( $dbuser, $dbpass, $host, $databasename, $tablename){
	Print "\n<br><font color=\"green\">Creating Querys for the table and database";
	
	$qry = "create database $databasename";
	$qry1 = "create table $tablename (counterdata varchar(255), domain varchar(255), webpage varchar(255)";
	
	Print "\n<br>Connecting to MySQL.";
	@MYSQL_CONNECT($host,$dbuser,$dbpass) OR DIE("Unable to connect to database");
	
	Print "\n<br>Creating database with the SQL command ".$qry.".";
	/* Query the database */
	MYSQL_QUERY($qry);

	Print "\n<br>Creating table with the SQL command ".$qry1.".\n<br>";
	MYSQL_QUERY($qry1);
	@MYSQL_CLOSE();

	Print "</font>";
	return 0;
}

if(!$Setup){ 
?>
<center>
Setup page for the PHP-MySQL counter version <?Print $version;?>
<table valign="center">
<tr>
   <td width="50%" align="left">Database Username ( Default = root )</td> <td><input type="text" name="username"></td>
</tr>
<tr>
   <td width="50%" align="left">Check this if there is no password on the database</td> <td><input type="checkbox" name="box" value="checked" <?if($box == "checked"){ Print "checked=\"true\""; } ?>> </td>
</tr>
<tr>
   <td width="50%" align="left">Database Password</td> <td><input type="password" name="password"></td>
</tr>
<tr>
   <td width="50%" align="left">Comfirm Database Password</td> <td><input type="password" name="cpassword"></td>
</tr>
<tr>
   <td width="50%" align="left">Database Host Name ( Defalut = localhost )</td> <td><input type="text" name="host"></td>
</tr>
<tr>
   <td width="50%" align="left">Check this to create the default Database and Table</td> <td><input type="checkbox" name="create" value="checked" <?if($create == "checked"){ Print "checked=\"true\""; } ?>> </td>
</tr>
<tr>
   <td width="50%" align="left">Database Name ( Defalut = counter )</td> <td><input type="text" name="databasename"></td>
</tr>
<tr>
   <td width="50%" align="left">Table Name ( Defalut = counter )</td> <td><input type="text" name="tablename"></td>
</tr>
<tr>
   <td width="50%" align="left">Path To the Images</td> <td><input type="text" name="imgpath"></td>
</tr>
<tr>
   <td width="50%" align="left">Extension of the Images. ( Default .gif )</td> <td><input type="text" name="imgend"></td>
</tr>
<tr>
   <td width="50%" align="left">To Create a separate config file and counter select yes</td> <td><select name="individual_files"> <option selected value="0">no</option> <option value="1">yes</option> </select> </td>
</tr>
<input type="hidden" value="1" name="Setup">
<tr>
   <td colspan="2" align="center"> <input type="submit" value="Create PHP-MySQL Counter"></td>
</tr>
<tr>
<?

}else{

	Print "Verifying Data Please Wait";
	if ($username == "") {
		$username = "root";
	}else{
		$username = "$username";
	}
	
	Print ".";
	if($password != $cpassword or $password == "") {
		if($box == ""){
			Print("<font color=\"#FF00FF\">Please Verify the condition of the password and Verified Password... Or check the box for no database password</font><br>");
			Print "$box,";
			$error = 1;
		}
	}else{
		$password = "$password";
	}
	
	Print ".";
	if ($host == "") {
		$hostname = "localhost";
	}else{
		$hostname = "$host";
	}
	
	Print ".";
	if ($databasename == "") {
		$dbName = "counter";
	}else{
		$dbName = "$databasename";
	}
	
	Print ".";
	if ($databasename == "") {
		$userstable = "counter";
	}else{
		$userstable = "$tablename";
	}
	if ($imgpath == "" && $error !=1) {
		$imgpath = "";
		Print("<br><font color=\"#FF00FF\">Please Provide a Path to the images. ( i.e. /home/usr/public_html/images/ )</font><br>");
		$error=4;
	}else{
		$imgpath = "$imgpath";
	}
	
	if ($imgend == "") {
		$imgend = ".gif";
	}else{
		$imgend = "$imgend";
	}
	
	Print ".\n<br>";
	
	/* make connection to and select the database, If there are no errors.*/
	if($error == 0){
		Print "All Required Data Is here...\n<br>";
			if($create == "checked"){
				$ret = CreateDatabaseAndTables( $username, $password, $hostname, $dbName, $userstable);
			}
		Print "Trying to connect to the database and do a query.<br>";
		if(@!MYSQL_CONNECT($hostname,$username,$password)){
			Print "<br><font color=\"#FF00FF\">Connection to the MySQL Database Failed....";
			Print "<br>Please Check the username, hostname, and password.</font>";
			$error = 2;
		}
		
		Print ".";
		if($error == 0){
			if(@!mysql_select_db("$dbName")){
				Print "<br><font color=\"#FF00FF\">Unable to select database $dbName</font>"; 
				$error = 3;
			}
			
			Print ".";
			/* Select The Ammount of Hits to the Site.. */
			$query = "SELECT * FROM $userstable ";
			
			if($error == 0){
				/* Query the database */
				$result = MYSQL_QUERY($query);
			}
			Print ".";
				
			if($error == 0){
				/* How many Hits are there? */
				$number = @MYSQL_NUMROWS($result);
			}
		}
		Print ".\n<br>";
	}		
	if($error != 0){
?>
<center>
<table valign="center">
<tr>
   <td width="50%" align="left">Database Username ( Default = root )</td> <td><input type="text" name="username" <? Print "value='$username'";?>></td>
</tr>
<tr>
   <td width="50%" align="left">Check this if there is no password on the database</td> <td><input type="checkbox" name="box" value="checked" <?if($box == "checked"){ Print "checked=\"true\""; } ?>> </td>
</tr>
<tr>
   <td width="50%" align="left">Database Password</td> <td><input type="password" name="password"></td>
</tr>
<tr>
   <td width="50%" align="left">Comfirm Database Password</td> <td><input type="password" name="cpassword"></td>
</tr>
<tr>
   <td width="50%" align="left">Database Host Name ( Defalut = localhost )</td> <td><input type="text" name="host" <? Print "value=\"$hostname\"";?>></td>
</tr>
<tr>
   <td width="50%" align="left">Check this to create the default Database and Table</td> <td><input type="checkbox" name="create" value="checked" <?if($create == "checked"){ Print "checked=\"true\""; } ?>> </td>
</tr>
<tr>
   <td width="50%" align="left">Database Name ( Defalut = counter )</td> <td><input type="text" name="databasename" <? Print "value=\"$dbName\"";?>></td>
</tr>
<tr>
   <td width="50%" align="left">Table Name ( Defalut = counter )</td> <td><input type="text" name="tablename" <? Print "value=\"$userstable\"";?>></td>
</tr>
<tr>
   <td width="50%" align="left">Path To the Images</td> <td><input type="text" name="imgpath" <? Print "value=\"$imgpath\"";?>></td>
</tr>
<tr>
   <td width="50%" align="left">Extension of the Images. ( Default .gif )</td> <td><input type="text" name="imgend" <? Print "value=\"$imgend\"";?>></td>
</tr>
<tr>
   <td width="50%" align="left">To Create a separate config file and counter select yes</td> <td><select name="individual_files"> <option selected value="0">no</option> <option value="1">yes</option> </select> </td>
</tr>
<input type="hidden" value="1" name="Setup">
<tr>
   <td colspan="2" align="center"> <input type="submit" value="Setup PHP/MySQL Counter"></td>
</tr>
<tr>
<?
	
	}else{
		if($error !=4){

		Print "Everything Looks Peachy!\n<br> Thank you for downloading and installing the PHP/MySQL Counter.\n";
		Print "<br>A Configuration file will be made in this directory.";
		Print "<br>It will automatically generate the needed";
		Print " variables and will make the PHP - MySQL Script avaliable for all your webpages.";
	
		//These 2 strings are all the varaibles for the php counter and the counter itself.

		$string = "\n\$hostname=\"$hostname\";\n\$username=\"$username\";\n\$password=\"$password\";\n\$dbName=\"$dbName\";\n\$userstable=\"$userstable\";\n\$path=\"$imgpath\";\n\$end=\"$imgend\";\n\$version=\"$version\";";
		$str2 = "\n\nIF (\$webpage == \"\") :\n\tdie(\"No Webpage Specified\");\nENDIF;\n\nIF (\$domain == \"\") :\n\tdie(\"No Domain Specified\");\nENDIF;\nMYSQL_CONNECT(\$hostname,\$username,\$password) OR DIE(\"Unable to connect to database\");\n@mysql_select_db(\"\$dbName\") or die(\"Unable to select database\"); \n\n\$query = \"SELECT * FROM \$userstable where webpage='\$webpage' and domain='\$domain'\";\n\n\$result = MYSQL_QUERY(\$query);\n\n\$number = MYSQL_NUMROWS(\$result);\n\n\$counter = mysql_result(\$result,\$i,\"counterdata\");\n\n\$i = 0;\n\nif(\$nocounting != 1){\n\tIF (\$number == 0) {\n\t\t\$query=\"insert into \$userstable values('1', '\$domain', '\$webpage') \";\n\t\t\$result = MYSQL_QUERY(\$query);\n\t\t\$query = \"SELECT * FROM \$userstable \";\n\t\t\$result = MYSQL_QUERY(\$query);\n\t\t\$counter = mysql_result(\$result,\$i,\"counterdata\");\n\t\t\$website = mysql_result(\$result,\$i,\"webpage\");\n\t\t\$wepsitepage = mysql_result(\$result,\$i,\"domain\");\n\t}ELSEIF (\$number > 0){\n\t\t\$counter = mysql_result(\$result,\$i,\"counterdata\");\n\t\t\$query=\"update \$userstable set counterdata = counterdata+1 where domain = '\$domain' and webpage = '\$webpage'\";\n\t\t\$result = MYSQL_QUERY(\$query);\n\t}\n}\n\n\$counter = trim(\$counter);\n\$counter = chop(\$counter);\n\$NumDigits = strlen(\$counter);\nfor(\$i=0;\$i<=\$NumDigits;\$i++){\n\t\$tmp=1;\n\t\$digit[\$i] = substr(\"\$counter\",\$i,\$tmp);\n}\n\nfor(\$i=0;\$NumDigits>=\$i;\$i++){\n\tif(\$digit[\$i] == \"\"){\n\t}else{\n\tPrint \"<img src=\\\"\$path\$digit[\$i]\$end\\\">\";\n\t}\n}\nPrint \"\\n<!-- PHP/Mysql Website hit counter Version \$version. \\nMade By Michael Mac Rae. If you like the counter Hit my site at www.fuynon.com-->\\n\";\n?>";

		Print $individual_files + "<br>";
		if($individual_files == 1 ){
			$fp =fopen("conf",w);
			
			fwrite($fp,"<?");	
			fwrite($fp,$string);
			fwrite($fp,"?>");
			fclose($fp);
	
			$fp =fopen("counter.php",w);
			fwrite($fp,"<?");
			fwrite($fp,$str2);
			fclose($fp);
		//	chmod("conf",0777);
		}else{
		
			$fp =fopen("counter.php",w);
	
			fwrite($fp,"<?");	
			fwrite($fp,$string);
			fwrite($fp,$str2);
			fclose($fp);
		//	chmod("conf",0777);
		}

		Print "<br>Everything is all set... To use the counter simply copy the counter.php script to the directory that it is supposed to be in. :)";
		}
	}
}



?>
</table>
</form>
</html>


