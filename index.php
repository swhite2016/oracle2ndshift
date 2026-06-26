<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!--- ---------------------------------------------------------------------
* Copyright \302\251 2010-2011 Susie White
*
* Author       - Susie White
*
* Filename     - index.php
* 
* Purpose      - dvd library display page
*
* Flow         - Top level script
*
--------------------------------------------------------------------   --->

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="cache-control" content="no-cache">
  <meta content="text/html;charset=ISO-8859-1" http-equiv="Content-Type">
  <link rel="SHORTCUT ICON" href="gif/favicon.ico" type=text/css>
  <link rel="stylesheet" href="tcs_style_red_3.css" type="text/css" />
  <title>DVD Library</title>
  <script type="text/javascript" src="dvds.js"></script>
</head>
<body>

<?php 
  include '../show_errors.php';
  include 'env.php'; 
  $config  = include "dbconnect.php";
  $version = $config['version'];
  $dp      = $config['dp'];
  include "dvds.php";
?>
<!-- header -->
<div id="wrapper">
  <div id="header">
    <table width="100%">
      <tr>
        <td align=center width="30%"><font family=Arial size=+2>
              DVD Library<br>Administration Page</font></td>
        <td align=center width="70%"><font family=Arial size=+2>
          <div id="nav_inline">
          <form name="HeaderForm" method="post" 
                action="<?php echo($php_self); ?>">
            <ul id="nav_inline">
              <li title="<?php echo ($form_q_title); ?>"><br><font size=+2>DVD Title<br>
                  <input name="v_title" id="v_title" type="text" size=20 style="font-size:20px;">
              <li><br><br><input type="submit" name="GetQueDiv" style="font-size:20px;"
                           value="Query" title="<?php echo ($form_submitque);?>" >
            </ul>
          </form>
          </div></font>
        </td></tr>
    </table>
  </div>
  <div style="clear: both;"></div>
</div>

<!---  main div --->
<div id="nav_main_wrap" >

<?php 

    if(!empty($_REQUEST['GetQueDiv'])) {
      $v_title    = $_POST['v_title'];
    } else {
      $v_title    = '';
    }

    $sql = "select * from dvds where 1=1 ";

    if ($v_title != "") {
      $sql .= " and title like '".$v_title."%'";
    }
    
    $sql .= " order by title";
    
    // run v5 or v8 mysql calls
    $result = run_query($version,$dp,$sql);
    if (!$result) {
        die("Failed query:  sql=$sql");
    }
      
    echo ("<p><h2><font style='color:#FFF'>DVD Library</font></h2></p>\n");
    echo ("<div id='main_dyn'>\n");
    echo ("<table width=100%>");
  
    $counter = -1;

    while($row = run_fetch($version,$result))
    {
      $v_id         = $row["id"];
      $v_title_disp = $row["title"];
      
      $v_title      = str_replace("'",'^',$v_title_disp);

      if ($counter >= 3) {
          $counter = 1;
          echo ( "</tr>\n<tr>\n");
      } elseif ($counter == -1) {
          echo ("<tr>\n");
          $counter = 1;
      } else { 
          $counter += 1;
      }
      echo ("<td width=30%><table>\n");
      echo ("<tr><td><img src='gif/t".$v_id.".gif' width='80px' height='115px'></td></tr>\n");
      echo ("<tr><td><div style='word-wrap: break-word'><h3><font size=+1>".$v_title_disp."</font></h3></td></tr>");
      echo ("</table></td>\n");
    }
    echo ("</tr></table></div>\n");
    run_free($version,$result);

?>

</div>
</body>
</html>
