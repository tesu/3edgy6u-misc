<?php

header('Content-Type: text/plain');
if (!isset($_GET['user'])) die('user not set!');

$u = preg_replace('/[^A-Za-z0-9_-]/','',$_GET['user']);

function getMALData($u) {
    $malapi = curl_init("http://myanimelist.net/malappinfo.php?u={$u}&status=all&type=anime");
    curl_setopt($malapi, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($malapi, CURLOPT_USERAGENT, "API KEY GOES HERE");
    $ccdata = simplexml_load_string(str_replace("&", "&amp;", curl_exec($malapi)));
    curl_close($malapi);
    return $ccdata;
}

$ccdata = getMALData($u);

echo "[\n";
$result = array();
foreach ($ccdata->anime as $key => $value) {
    if ((int)trim($value->my_status) != 2) continue;
    $title = htmlspecialchars(trim($value->series_title));
    $image = trim($value->series_image);
    $result[] = "    {\"image\": \"{$image}\", \"text\": \"{$title}\"}";
}
echo implode(",\n",$result);
echo "\n]"
?>
