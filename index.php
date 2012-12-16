<?php
include "conf.php";
include "func.php";
if(!empty($_GET))
{
    if (isset($_GET["Network"]) && !isset($_GET["Year"]) && !isset($_GET["Month"]) && !isset($_GET["Day"]) && !isset($_GET["Channel"])
        && array_key_exists($_GET["Network"], $tomb)
        )
    {
        $segedtomb =  array_keys($tomb[$_GET["Network"]]);
        sort($segedtomb);
        for ($i = 0; $i <= count($segedtomb); $i++) {
            $logkiirlesz .= "<a href=\"".$webpath."?Network=".$_GET["Network"]."&Year=".$segedtomb[$i]."\">".$segedtomb[$i]."</a><br/>\n";
        }
    }
    elseif (isset($_GET["Network"]) && isset($_GET["Year"]) && !isset($_GET["Month"]) && !isset($_GET["Day"]) && !isset($_GET["Channel"])
        && array_key_exists($_GET["Network"], $tomb) && array_key_exists($_GET["Year"], $tomb[$_GET["Network"]])
        )
    {
        $segedtomb =  array_keys($tomb[$_GET["Network"]][$_GET["Year"]]);
        sort($segedtomb);
        for ($i = 0; $i <= count($segedtomb); $i++) {
            $logkiirlesz .= "<a href=\"".$webpath."?Network=".$_GET["Network"]."&Year=".$_GET["Year"]."&Month=".$segedtomb[$i]."\">".$segedtomb[$i]."</a><br/>\n";
        }
    }
    elseif (isset($_GET["Network"]) && isset($_GET["Year"]) && isset($_GET["Month"]) && !isset($_GET["Day"]) && !isset($_GET["Channel"])
        && array_key_exists($_GET["Network"], $tomb) && array_key_exists($_GET["Year"], $tomb[$_GET["Network"]])
        && array_key_exists($_GET["Month"], $tomb[$_GET["Network"]][$_GET["Year"]])
        )
    {
        $segedtomb =  array_keys($tomb[$_GET["Network"]][$_GET["Year"]][$_GET["Month"]]);
        // var_dump ( $segedtomb );
        sort($segedtomb);
        for ($i = 0; $i <= count($segedtomb); $i++) {
            $logkiirlesz .= "<a href=\"".$webpath."?Network=".$_GET["Network"]."&Year=".$_GET["Year"]."&Month=".$_GET["Month"]."&Day=".$segedtomb[$i]."\">".$segedtomb[$i]."</a><br/>\n";
        }
        // echo "asd";
    }
    elseif (isset($_GET["Network"]) && isset($_GET["Year"]) && isset($_GET["Month"]) && isset($_GET["Day"]) && !isset($_GET["Channel"])
        && array_key_exists($_GET["Network"], $tomb) && array_key_exists($_GET["Year"], $tomb[$_GET["Network"]])
        && array_key_exists($_GET["Month"], $tomb[$_GET["Network"]][$_GET["Year"]])
        && array_key_exists($_GET["Day"], $tomb[$_GET["Network"]][$_GET["Year"]][$_GET["Month"]])
        )
    {
        $segedtomb =  array_values($tomb[$_GET["Network"]][$_GET["Year"]][$_GET["Month"]][$_GET["Day"]]);
        sort($segedtomb);
        // var_dump($tomb[$_GET["Network"]][$_GET["Year"]][$_GET["Month"]][$_GET["Day"]]);
        for ($i = 0; $i <= count($segedtomb); $i++) {
            if ($segedtomb[$i] != "") {
                $linklesz = preg_replace("/\#/", "!pound", $segedtomb[$i]); // Kodolja a # jelet mert az anchor miatt bekavar.
                $linklesz = preg_replace("/\+/", "!plus", $linklesz);       // Atalakitjuk a + jelet mert ezt atkene.
                $logkiirlesz .= "<a href=\"".$webpath."?Network=".$_GET["Network"]."&Year=".$_GET["Year"]."&Month=".$_GET["Month"]."&Day=".$_GET["Day"]."&Channel=".$linklesz."\">".$segedtomb[$i]."</a><br/>\n";
            }
        }
    }
    elseif (isset($_GET["Network"]) && isset($_GET["Year"]) && isset($_GET["Month"]) && isset($_GET["Day"]) && isset($_GET["Channel"]) && preg_match("/!/", $_GET["Channel"])
        && array_key_exists($_GET["Network"], $tomb) && array_key_exists($_GET["Year"], $tomb[$_GET["Network"]])
        && array_key_exists($_GET["Month"], $tomb[$_GET["Network"]][$_GET["Year"]])
        && array_key_exists($_GET["Day"], $tomb[$_GET["Network"]][$_GET["Year"]][$_GET["Month"]])
        // && array_key_exists($_GET["Channel"], $tomb[$_GET["Network"]][$_GET["Year"]][$_GET["Month"]][$_GET["Day"]])
        )
    {
        $logfile = preg_replace("/!pound/", "#", $_GET["Channel"]);
        $logfile = preg_replace("/!plus/", "+", $logfile);
        $holalog2 = $holalog.$_GET["Network"]."_".$logfile."_".$_GET["Year"].$_GET["Month"].$_GET["Day"].".log";
        // echo $holalog;
        if(file_exists($holalog2))
        {
            $ittaszoveg = $holalog2;
            $logkiirlesz .= "<pre>";
            // echo "<pre>";
            $dh = fopen($holalog2,"r");
            $logkiir = fread($dh,524288);
            $logkiir = preg_replace("/</", "&lt;", $logkiir);
            $logkiir = preg_replace("/>/", "&gt;", $logkiir);
            $logkiir = preg_replace("/\n/", "<br/>\n", $logkiir);
            $logkiir = preg_split("/\n/", $logkiir);
            $logkiir_counter = count($logkiir)-2;
            for ($ii = 0; $ii <= $logkiir_counter; $ii++)
            {
                $anchor = $ii+1;
                $logkiirlesz .= "<a name=\"".$anchor."\" href=\"".$_SERVER["REQUEST_URI"]."#".$anchor."\">".$anchor."</a>\t".fixEncoding($logkiir[$ii]);
                // $logkiirlesz .= "<a name=\"".$anchor."\" href=\"?".$_SERVER["QUERY_STRING"]."#".$anchor."\">".$anchor."</a>\t".fixEncoding($logkiir[$ii]);
                // echo "<a name=\"".$anchor."\" href=\"".$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"]."#".$anchor."\">".$anchor."</a>\t".fixEncoding($logkiir[$ii]);
            }
            $logkiirlesz .= "</pre>";
            // echo "</pre>";
        }
        else { echo "<meta http-equiv='Refresh' content='0; URL=index.php'>"; }
    }
    else {
        echo "<meta http-equiv='Refresh' content='0; URL=index.php'>";
    }
}
else {
$segedtomb =  array_keys($tomb);
sort($segedtomb);
for ($i = 0; $i <= count($tomb); $i++) {
    $logkiirlesz .=  "<a href=\"".$webpath."?Network=".$segedtomb[$i]."\">".$segedtomb[$i]."</a><br/>\n";
}
// var_dump ( $tomb["Rizon"]["2012"]["06"] );
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $htmltitle." | Written by Invisible &copy; 2012"; ?></title>
</head>
<body>
<?php
echo $logkiirlesz;
?>
</body>
</html>
