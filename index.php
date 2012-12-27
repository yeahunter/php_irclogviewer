<?php
include "conf.php";
include "func.php";
if(!empty($_GET))
{
    if (isset($_GET["Network"])) {
        $network = $_GET["Network"];
    }
    if (isset($_GET["Year"])) {
        $year = $_GET["Year"];
    }
    if (isset($_GET["Month"])) {
        $month = $_GET["Month"];
    }
    if (isset($_GET["Day"])) {
        $day = $_GET["Day"];
    }
    if (isset($_GET["Channel"])) {
        $channel = $_GET["Channel"];
    }
    if (isset($_GET["Network"]) && !isset($_GET["Year"]) && !isset($_GET["Month"]) && !isset($_GET["Day"]) && !isset($_GET["Channel"])
        && array_key_exists($_GET["Network"], $tomb)
        )
    {
        $yearsArray = array_keys($tomb[$network]);
        sort($yearsArray);
        foreach($yearsArray as $year) {
            if (!empty($year)) {
                $logkiirlesz .= "<a href=\"".$webpath."?Network=".$network."&Year=".$year."\">".$year."</a><br/>\n";
            }
        }
    }
    elseif (isset($_GET["Network"]) && isset($_GET["Year"]) && !isset($_GET["Month"]) && !isset($_GET["Day"]) && !isset($_GET["Channel"])
        && array_key_exists($_GET["Network"], $tomb) && array_key_exists($_GET["Year"], $tomb[$_GET["Network"]])
        )
    {
        $monthsArray = array_keys($tomb[$network][$year]);
        sort($monthsArray);
        foreach($monthsArray as $month) {
            if (!empty($month)) {
                $logkiirlesz .= "<a href=\"".$webpath."?Network=".$network."&Year=".$year."&Month=".$month."\">".$month."</a><br/>\n";
            }
        }
    }
    elseif (isset($_GET["Network"]) && isset($_GET["Year"]) && isset($_GET["Month"]) && !isset($_GET["Day"]) && !isset($_GET["Channel"])
        && array_key_exists($_GET["Network"], $tomb) && array_key_exists($_GET["Year"], $tomb[$_GET["Network"]])
        && array_key_exists($_GET["Month"], $tomb[$_GET["Network"]][$_GET["Year"]])
        )
    {
        $daysArray = array_keys($tomb[$network][$year][$month]);
        sort($daysArray);
        foreach($daysArray as $day) {
            if (!empty($day)) {
                $logkiirlesz .= "<a href=\"".$webpath."?Network=".$network."&Year=".$year."&Month=".$month."&Day=".$day."\">".$day."</a><br/>\n";
            }
        }
    }
    elseif (isset($_GET["Network"]) && isset($_GET["Year"]) && isset($_GET["Month"]) && isset($_GET["Day"]) && !isset($_GET["Channel"])
        && array_key_exists($_GET["Network"], $tomb) && array_key_exists($_GET["Year"], $tomb[$_GET["Network"]])
        && array_key_exists($_GET["Month"], $tomb[$_GET["Network"]][$_GET["Year"]])
        && array_key_exists($_GET["Day"], $tomb[$_GET["Network"]][$_GET["Year"]][$_GET["Month"]])
        )
    {
        $channelsArray = array_values($tomb[$network][$year][$month][$day]);
        sort($channelsArray);
        foreach($channelsArray as $channel) {
            if (!empty($channel)) {
                $channel_link = preg_replace("/\#/", "!pound", $channel); // Kodolja a # jelet mert az anchor miatt bekavar.
                $channel_link = preg_replace("/\+/", "!plus", $channel_link);       // Atalakitjuk a + jelet mert ezt atkene.
                $logkiirlesz .= "<a href=\"".$webpath."?Network=".$network."&Year=".$year."&Month=".$month."&Day=".$day."&Channel=".$channel_link."\">".$channel."</a><br/>\n";
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
        $channel = preg_replace("/!pound/", "#", $channel);
        $channel = preg_replace("/!plus/", "+", $channel);
        $logfile = $holalog.$network."_".$channel."_".$year.$month.$day.".log";

        if(file_exists($logfile))
        {
            $logkiirlesz .= "<pre>";
            $dh = fopen($logfile,"r");
            $logkiir = htmlspecialchars(fread($dh,524288));
            $logkiir = preg_replace("/\n/", "<br/>\n", $logkiir);
            $logkiir = preg_split("/\n/", $logkiir);
            $logkiir_counter = count($logkiir)-2;
            for ($ii = 0; $ii <= $logkiir_counter; $ii++)
            {
                $anchor = $ii+1;
                $logkiirlesz .= "<a name=\"".$anchor."\" href=\"".$_SERVER["REQUEST_URI"]."#".$anchor."\">".$anchor."</a>\t".fixEncoding($logkiir[$ii]);
            }
            $logkiirlesz .= "</pre>";
        }
        else
            echo "<meta http-equiv='Refresh' content='0; URL=".$webpath."'>";
    }
    else
        echo "<meta http-equiv='Refresh' content='0; URL=".$webpath."'>";
}
else {
    $networksArray = array_keys($tomb); // Kiszedem a halozatokat egy tombbe
    sort($networksArray); // Rendezem a tombot
    foreach($networksArray as $network) {
        if (!empty($network)) {
            $logkiirlesz .=  "<a href=\"".$webpath."?Network=".$network."\">".$network."</a><br/>\n"; // Es kiirom az elemeit
        }
    }
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
