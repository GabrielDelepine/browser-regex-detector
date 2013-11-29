<?php
/* Version 0.2 */

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', TRUE);

$start = microtime(true);

if(isset($_POST['u_agent']))
    $u_agent = $_POST['u_agent']; // For test purpose only ! Delete-it for production
else
    $u_agent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';   

$bname = 'Unknown';
$platform = 'Unknown';
$version= $ub = "";

//First get the platform
if (preg_match('/linux/i', $u_agent)) {
    $platform = 'linux';
}
elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
    $platform = 'mac';
}
elseif (preg_match('/windows|win32/i', $u_agent)) {
    $platform = 'windows';
}

// Next get the name of the useragent yes seperately and for good reason
if(preg_match('/Trident/i', $u_agent) && !preg_match('/Opera/i',$u_agent))
{
    if(preg_match('/MSIE/i', $u_agent))
    {
        if(preg_match('/chromeframe/i', $u_agent))
        {
            $bname = 'IE with Chrome Frame';
            $ub = "chromeframe";
        }
        else
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
    }
    else
    {
        $bname = 'Internet Explorer';
        // no $ub because we use another pattern
    }
}
elseif(preg_match('/Firefox/i',$u_agent))
{
    $bname = 'Mozilla Firefox';
    $ub = "Firefox";
}
elseif(preg_match('/Chrome/i',$u_agent))
{
    $bname = 'Google Chrome';
    $ub = "Chrome";
}
elseif(preg_match('/Safari/i',$u_agent))
{
    $bname = 'Apple Safari';
    $ub = "Safari";
}
elseif(preg_match('/Opera/i',$u_agent))
{
    $bname = 'Opera';
    $ub = "Opera";
}
elseif(preg_match('/Netscape/i',$u_agent))
{
    $bname = 'Netscape';
    $ub = "Netscape";
}

// finally get the correct version number
// Only for IE > 10 (not a cosmetic code !)
if(preg_match('/Trident/i', $u_agent) && !preg_match('/MSIE/i', $u_agent))
{
    $pattern = '/Trident\/.*rv:([0-9]{1,}[\.0-9.]{0,})/';
    if(preg_match($pattern, $u_agent, $matches) AND isset($matches[1]))
        $version = $matches[1];
}
// for others (It can be nice to combinate this nice code with the code below !)
else
{
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if(preg_match_all($pattern, $u_agent, $matches))
    {
        // see how many we have
        $i = count($matches['browser']); // we have matching
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= isset($matches['version'][1])?$matches['version'][1]:'';
            }
        }
        else {
            $version= $matches['version'][0];
        }
    }
}

// check if we have a number
if ($version==null || $version=="") {
    $version="?";
}

$info = array(
    'name'      => $bname,
    'version'   => $version,
    'platform'  => $platform,
    'userAgent' => $u_agent,
    'pattern'    => $pattern
);

// Displaying for test. Of course delete-it for production
header("Content-Type: text/plain");
echo "Execution time = ".((microtime(true)-$start)*1000)." ms\n\n";
print_r($info);
