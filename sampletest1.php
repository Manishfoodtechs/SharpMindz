<?php
session_start();

include("database.php");
extract($_POST);
extract($_GET);
extract($_SESSION);
/*$rs=mysql_query("select * from mst_question where test_id=$tid",$cn) or die(mysql_error());
if($_SESSION['qn']>mysql_num_rows($rs))
{
unset($_SESSION['qn']);
exit;
}*/
if(isset($subid) && isset($testid))
{
$_SESSION['sid']=$subid;
$_SESSION['tid']=$testid;
header("location:quiz.php");
}
if(!isset($_SESSION['sid']) || !isset($_SESSION['tid']))
{
	header("location: index.php");
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>sharpmindz.com</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="quiz.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
include("header.php");


   $rs=mysql_query("select * from mst_question where test_id='$tid'",$connection) or die(mysql_error());
	
		if($submit=='Next Question' && isset($ans))
		{  
				mysql_data_seek($rs,$_SESSION['qn']);
				$row= mysql_fetch_row($rs);	
				mysql_query("insert into mst_useranswer(sess_id, test_id, que_des, ans1,ans2,ans3,ans4,true_ans,your_ans) values ('".session_id()."', '$tid','$row[2]','$row[3]','$row[4]','$row[5]', '$row[6]','$row[7]','$ans')") or die(mysql_error());
				echo "prev ans you selected is".$ans;
				if($ans==$row[7])
				  { 
				 			$_SESSION['trueans']=$_SESSION['trueans']+1;
				  }
				     $_SESSION['qn']=$_SESSION['qn']+1;
		}
		else if($submit=='Get Result' && isset($ans))
		{
				mysql_data_seek($rs,$_SESSION['qn']);
				$row= mysql_fetch_row($rs);	
				mysql_query("insert into mst_useranswer(sess_id, test_id, que_des, ans1,ans2,ans3,ans4,true_ans,your_ans) values ('".session_id()."', '$tid','$row[2]','$row[3]','$row[4]','$row[5]', '$row[6]','$row[7]','$ans')") or die(mysql_error());
				if($ans==$row[7])
				{
							$_SESSION['trueans']=$_SESSION['trueans']+1;
				}
				echo "<h1 class=head1> Result</h1>";
				$_SESSION['qn']=$_SESSION['qn']+1;
				echo "<Table align=center><tr class=tot><td>Total Question<td> $_SESSION[qn]";
				echo "<tr class=tans><td>True Answer<td>".$_SESSION['trueans'];
				$w=$_SESSION['qn']-$_SESSION['trueans'];
				echo "<tr class=fans><td>Wrong Answer<td> ". $w;
				echo "</table>";
				mysql_query("insert into mst_result(login,test_id,test_date,score) values('$login',$tid,'".date("d/m/Y")."',$_SESSION[trueans])") or die(mysql_error());
				echo "<h1 align=center><a href=review.php> Review Question</a> </h1>";
				unset($_SESSION['qn']);
				unset($_SESSION['sid']);
				unset($_SESSION['tid']);
				unset($_SESSION['trueans']);
				unset($_SESSION['btn']);
				exit;
		}


	mysql_data_seek($rs,$_SESSION['qn']);
	$row= mysql_fetch_row($rs);

		$n=$_SESSION['qn']+1;
		$i=rand(3,6);
		$j=rand(3,6);
		$k=rand(3,6);
		$l=rand(3,6);
		$randomq=$row[0];

	if( $i==$j || $i==$k || $i==$l || $j==$k || $j==$l || $k==$l)
	{	
		do{
	        //echo "all option are same";
	        $i=rand(3,6);
            $j=rand(3,6);
            $k=rand(3,6);
            $l=rand(3,6);
             // echo $i.$j.$k.$l."<br>";
           } while($i==$j || $i==$k || $i==$l || $j==$k || $j==$l || $k==$l);
    }
	$op1=$row[$i];
	$op2=$row[$j];
	$op3=$row[$k];
	$op4=$row[$l];

	$i=$i-2;
	$j=$j-2;
	$k=$k-2;
	$l=$l-2;

    echo "<form name=myfm method=post action=quiz.php>";
	
		$n=$_SESSION['qn']+1;
		echo "<table width=100% id='quiztable'> <tr> <td width=30>&nbsp;<td> <table border=0>";
	echo "<tR><td><span class=style2>Question ".  $n .": $row[2]</style>";
 
	echo "<tr><td class=style8> <label for=option1> <input type=radio name=ans id=option1 value=$i>$op1</label></td></tr>";
    echo "<tr><td class=style8> <label for=option2> <input type=radio name=ans id=option2 value=$j>$op2</label></td></tr>";
    echo "<tr><td class=style8> <label for=option3> <input type=radio name=ans id=option3 value=$k>$op3 </label></td></tr>";
    echo "<tr><td class=style8> <label for=option4> <input type=radio name=ans id=option4 value=$l>$op4 </label></td></tr>";

if($_SESSION['qn']<mysql_num_rows($rs)-1)
{
	echo "<tr><td><input type=submit name=submit value='Next Question'>";
	
}	
else
{
echo "<tr><td><input type=submit name=submit value='Get Result'>";
	}

echo "</table></form>";
?>
</body>
</html>