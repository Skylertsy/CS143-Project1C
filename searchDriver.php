<html>
<body link="FFFFFF" alink="FFFFFF" vlink="FFFFFF">

<?php
require($DOCUMENT_ROOT . "./menu_bar.html");
?>
<title>CS 143 Movie Database</title>

<?php
	# Functions will go here

	function print_info($result, $type) {
		$i = 0;
		if($result == 0) {
			print "<td><font color='FFFFFF'><b>" . "No arguments provided." . "</b></font></td>";
			print "</tr></table";
		} else {
			while($i < @mysql_num_fields($result)) {
				print "<td><font color='FFFFFF'><b>" . @mysql_field_name($result, $i) . "</b></font</td>";
				$i++;
			}
			print "</tr>";

			$arr = preg_split("/[\/]/", $type);
			$value = $arr[1];
			
			$arr = preg_split("/[\.]/", $value);
			$value = $arr[0];

			$ind = 0;
			while($row = @mysql_fetch_row($result)) {
				print "<tr align='center'>";
				$i = 0;
				$form_name = "myForm" . $ind;
				print "<form name='myForm".$ind."' method='POST' action='".$type."'>";
				while($i < @mysql_num_fields($result)) {
					if($i == 0) {
						print "<input type='hidden' name='".$value."' value='$row[$i]'/>";
						print "<td align='center'><font color='FFFFFF'>";
						print "<a href='".$type."?".$value."=".$row[$i]."' onClick='document.$form_name.submit();return false;'>".$row[$i]."</a>";
						print "</font></td>";
					} else {
						if($row[$i] == "") {
							print "<td><font color='FFFFFF'>N/A</font></td>";
						} else {
							print "<td><font color='FFFFFF'>" . $row[$i] . "</font></td>";
						}
					}
					$i++;
				}
				$ind++;
				print "</form></tr>\n";
			}
			print "</table>";
		}
	}
?>

<?php
	$db_connection = mysql_connect("localhost", "cs143", "");
	if(!$db_connection) {
		$err_msg = mysql_error($db_connection);
		print "Connection failed: $errmsg <br/>";
		exit(1);
	}

	mysql_select_db("CS143", $db_connection);

	$mode = $_POST['mode'];
	if(! $mode) {
		$mode = "AND ";
	}

	if( $_POST['id'] == "Person" ) {
		$first = $_POST['first'];
		$last = $_POST['last'];
		$sex = $_POST['sex'];
		$dob = $_POST['dob'];
		$dod = $_POST['dod'];

		$append = 0;

		$base_query = "SELECT * From Actor WHERE ";
		if($first) {
			$base_query .= "first='$first' ";
			$append = 1;
		}
		if($last) {
			if($append == 1) {
				$base_query = $base_query . $mode . "last='$last' ";
			} else {
				$base_query .= "last='$last' ";
				$append = 1;
			}
		}
		if($sex) {
			if($append == 1) {
				$base_query = $base_query . $mode . "sex='$sex' ";
			} else {
				$base_query .= "sex='$sex' ";
				$append = 1;
			}
		}
		if($dob) {
			if($append == 1) {
				$base_query = $base_query . $mode . "dob='$dob' ";
			} else {
				$base_query .= "dob='$dob' ";
				$append = 1;
			}
		}
		if($dod) {
			if($append == 1) {
				$base_query = $base_query . $mode . "dod='$dod' ";
			} else {
				$base_query .= "dod='$dod' ";
				$append = 1;
			}
		}

		if($append == 1) {
			$result_actor = @mysql_query($base_query, $db_connection);
		} else {
			$result_actor = 0;
		}

		$append = 0;

		$base_query_director = "SELECT * From Director WHERE ";
		if($first) {
			$base_query_director .= "first='$first' ";
			$append = 1;
		}
		if($last) {
			if($append == 1) {
				$base_query_director = $base_query_director . $mode . "last='$last' ";
			} else {
				$base_query_director .= "last='$last' ";
				$append = 1;
			}
		}
		if($dob) {
			if($append == 1) {
				$base_query_director = $base_query_director . $mode . "dob='$dob' ";
			} else {
				$base_query_director .= "dob='$dob' ";
				$append = 1;
			}
		}
		if($dod) {
			if($append == 1) {
				$base_query_director = $base_query_director . $mode . "dod='$dod' ";
			} else {
				$base_query_director .= "dod='$dod' ";
				$append = 1;
			}
		}

		if($append == 1) {
			$result_director = @mysql_query($base_query_director, $db_connection);
		} else {
			$result_director = 0;
		}		

		print "<link rel='stylesheet' type='text/css' media='all' href='searchsm.css'>";
		print "<table align='center' width='650' class='searchtablesm' bgcolor='0038A8'>";
		print "<tr bgcolor='0038A8'>";
		print "<td align='center' colspan='6'><font color='FFFFFF'>";
		print "Search Results";
		print "</font></td></tr>";
	
		print "<tr bgcolor='0038A8'><td align='center' colspan='6'><font color='FFFFFF'>Actor Results</font></td></tr>";

		print "<tr bgcolor='0038A8' align='center'>";
		
		print_info($result_actor, "./actor.php");
		
		print "<table align='center' width='650' class='searchtablesm' bgcolor='0038A8'>";
		print "<tr bgcolor='0038A8'><td align='center' colspan='5'><font color='FFFFFF'>Director Results</font></td></tr>";
		
		print "<tr bgcolor='0038A8' align='center'>";
		
		print_info($result_director, "./director.php");

	} else if( $_POST['id'] == "Movie" ) {
		$title = $_POST['title'];
		$rating = $_POST['rating'];
		$year = $_POST['year'];
		$company = $_POST['company'];
		
		$base_query_movie = "SELECT * FROM Movie WHERE ";
		$append = 0;

		if($title) {
			$base_query_movie .= "title='$title' ";
			$append = 1;
		}
		if($rating) {
			if($append == 1) {
				$base_query_movie = $base_query_movie . $mode . "rating='$rating' ";
			} else {
				$base_query_movie .= "rating='$rating' ";
				$append = 1;
			}
		}
		if($year) {
			if($append == 1) {
				$base_query_movie = $base_query_movie . $mode . "year=$year ";
			} else {
				$base_query_movie .= "year=$year ";
				$append = 1;
			}
		}
		if($company) {
			if($append == 1) {
				$base_query_movie = $base_query_movie . $mode . "company='$company' ";
			} else {
				$base_query_movie .= "company='$company' ";
				$append = 1;
			}
		}

		if($append == 1) {
			$result_movie = @mysql_query($base_query_movie, $db_connection);
		} else {
			$result_movie = 0;
		}

		print "<link rel='stylesheet' type='text/css' media='all' href='searchsm.css'>";
		print "<table align='center' width='650' class='searchtablesm' bgcolor='0038A8'>";
		print "<tr bgcolor='0038A8'>";
		print "<td align='center' colspan='6'><font color='FFFFFF'>";
		print "Search Results";
		print "</font></td></tr>";
	
		print "<tr bgcolor='0038A8'><td align='center' colspan='6'><font color='FFFFFF'>Movie Results</font></td></tr>";

		print "<tr bgcolor='0038A8' align='center'>";
		
		print_info($result_movie, "./movie.php");	
	} else {
		echo "<h1>OH GOD FATAL ERROR SHUT DOWN THE MAINFRAME</h1>";
	}
?>
		

</body>
</html>