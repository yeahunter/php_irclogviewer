<?php
if (is_dir($holalog)) {
    if ($handle = opendir($holalog)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && preg_match("/[#]+/", $entry)) {
                $TombAmibeBeolvasunk['unsorted'][] = $entry;
                $TombAmibeBeolvasunk['index'][] = filemtime($holalog.$entry);
            }
        }
        arsort( $TombAmibeBeolvasunk['index'] );
        closedir($handle);
    }
}


foreach ( $TombAmibeBeolvasunk['index'] as $i => $t ) {
    preg_match("/(?P<Network>[^\_]*)[\_](?P<Channel>[\#].*?)[\_](?P<Year>[0-9]{4})[^0-9]*(?P<Month>[0-9]{2})[^0-9]*(?P<Day>[0-9]{2})*[\.]log/",
        $TombAmibeBeolvasunk['unsorted'][$i],
        $hohooegytomb);
    $tomb[$hohooegytomb["Network"]][$hohooegytomb["Year"]][$hohooegytomb["Month"]][$hohooegytomb["Day"]][$i][] = $hohooegytomb["Channel"];
    $tomb[$hohooegytomb["Network"]][$hohooegytomb["Year"]][$hohooegytomb["Month"]][$hohooegytomb["Day"]][$i][] = $t;
}

function fixEncoding($in_str) 
{ 
    $cur_encoding = mb_detect_encoding($in_str) ; 
    if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8")) 
    return $in_str; 
    else 
    return utf8_encode($in_str); 
}

function makeClickableLinks($s) {
  return preg_replace('"\b((https?)?(ftp)?://\S+)"', '<a href="$1" target="_blank" rel="nofollow">$1</a>', $s);
}
?>
