<?php 



$apiKey = '2afqnbp4jd93h5n';
$appSecret = 'osdarkrp2k6gi0h';
$authToken = 'sl.AvtTpRIgyzA4QNrGyYIEf914StlCSHYDlpvzYj9r92AlwttfOhHeoq3qQPlP5I0Mi45YNVb8nILVhq3QR3-3Ea00a3cWegcfU6qiwgQn8Ik_79DzR7anTCHeCKGaWicUcrfKLOPm7Rg';

function fileList($authToken){
$parameters = array('path' => '','include_deleted' => true,'recursive' => true);

$headers = array('Authorization: Bearer '.$authToken,
                 'Content-Type: application/json');

$curlOptions = array(
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($parameters),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_VERBOSE => true);

$ch = curl_init('https://api.dropboxapi.com/2/files/list_folder');

curl_setopt_array($ch, $curlOptions);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$result = curl_exec($ch);

$json = json_decode($result, true);
echo "<ol>";
foreach ($json['entries'] as $data) {
    echo "<li>". $data['name'].'</li>';
}
echo "</ol>";

curl_close($ch);

}

    function dbx_get_file($token, $in_filepath, $out_filepath)
    {
    $out_fp = fopen($out_filepath, 'w+');
    if ($out_fp === FALSE)
        {
        echo "fopen error; can't open $out_filepath\n";
        return (NULL);
        }

    $url = 'https://content.dropboxapi.com/2/files/download';

    $header_array = array(
        'Authorization: Bearer ' . $token,
        'Content-Type:',
        'Dropbox-API-Arg: {"path":"' . $in_filepath . '"}'
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header_array);
    curl_setopt($ch, CURLOPT_FILE, $out_fp);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $metadata = null;
    curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $header) use (&$metadata)
        {
        $prefix = 'dropbox-api-result:';
        if (strtolower(substr($header, 0, strlen($prefix))) === $prefix)
            {
            $metadata = json_decode(substr($header, strlen($prefix)), true);
            }
        return strlen($header);
        }
    );

    $output = curl_exec($ch);

    if ($output === FALSE)
        {
        echo "curl error: " . curl_error($ch);
        }

    curl_close($ch);
    fclose($out_fp);

    return($metadata);
    } // dbx_get_file()

//$metadata = dbx_get_file($authToken, '/Test.xlsx', 'Test.xlsx');
//echo "File " . $metadata['name'] . " has the rev " . $metadata['rev'] . ".\n";
//fileList($authToken);


 function accountInfo($authToken)
 {
    $headers = array("Authorization: Bearer ".$authToken,
    "Content-Type: application/json");

$ch = curl_init('https://api.dropboxapi.com/2/users/get_space_usage');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "null");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_error($ch);
$response = curl_exec($ch);

curl_close($ch);
echo $response;
}

function uploadFile($authToken,$path)
{
$fp = fopen($path, 'rb');
$size = filesize($path);

$cheaders = array('Authorization: Bearer '.$authToken,
                  'Content-Type: application/octet-stream',
                  'Dropbox-API-Arg: {"path":"/test/'.$path.'", "mode":"add"}');

$ch = curl_init('https://content.dropboxapi.com/2/files/upload');
curl_setopt($ch, CURLOPT_HTTPHEADER, $cheaders);
curl_setopt($ch, CURLOPT_PUT, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_INFILE, $fp);
curl_setopt($ch, CURLOPT_INFILESIZE, $size);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);

echo 'File Upload Succussfully.'.$response;
curl_close($ch);
fclose($fp);
}

//uploadFile($authToken,'Test2.xlsx'); 
fileList($authToken);
?>