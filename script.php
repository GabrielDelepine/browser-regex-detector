<?php
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', TRUE);
    
    $start = microtime(true);
    
    $u_agent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
    
    // For test
    $u_agent = "Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko"; // IE 11
    $u_agent = "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)"; // IE 10
    //~ $u_agent = "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)"; // IE 9
    //~ $u_agent = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)"; // IE 8
    //~ $u_agent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)"; // IE 7
    //~ $u_agent = "Mozilla/5.0 (Windows NT 6.1; Intel Mac OS X 10.6; rv:7.0.1) Gecko/20100101 Firefox/7.0.1"; // FF7 windows
    //~ $u_agent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:7.0.1) Gecko/20100101 Firefox/7.0.1"; // FF 7 Mac
    //~ $u_agent = "Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25"; // iPad IOS6
    //~ $u_agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/30.0.1599.114 Chrome/30.0.1599.114 Safari/537.36**30.0.1599.114"; // Chromium 30 Linux
    
    
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= $ub = "";

    //First get the platform?
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
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
    );
    
    echo "Execution time = ".((microtime(true)-$start)*1000)."ms<br/><br/>";
    
    echo str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", nl2br(print_r($info, true)));
