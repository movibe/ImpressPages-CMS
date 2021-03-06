<?php
/**
 * @package ImpressPages

 *
 */

namespace IpUpdate\Library\Helper;


class Net
{

    public function downloadFile($url, $fileName)
    {
        
        if (!function_exists('curl_init')) {
            throw new \IpUpdate\Library\UpdateException("CURL is not installed. Please download this file $url and put it in following directory $fileName manually.", \IpUpdate\Library\UpdateException::CURL_REQUIRED);
        }
        
        $fs = new \IpUpdate\Library\Helper\FileSystem();
        
        $fs->makeWritable($fs->getParentDir($fileName));

        $ch = curl_init();
        
        $fh = fopen($fileName, 'w');
        if(function_exists('set_time_limit')) {
            set_time_limit(1800);
        }
        $options = array(
            CURLOPT_FILE => $fh,
            CURLOPT_TIMEOUT => 1800, // set this to 30 min so we don't timeout on big files
            CURLOPT_URL => $url
        );
        
        curl_setopt_array($ch, $options);

        if (curl_exec($ch)) {
            return true;
        } else {
            return curl_error($ch);
        }
    }
    
    
}