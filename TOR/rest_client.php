<!-- 
  send images to a deep learning server
    1. first compress (zip) the images
    2. then send the zip file to the server
-->

<?php

// global variables
// create a POST request with the zip file and uuid info using cURL
$rest_server = "http://128.8.235.4:5000/";
// a base dir for zip files
$zip_dir = realpath("zips");
// a base dir for image files
$imgs_dir = realpath("images");
// debug boolean
$debug = true;


// initialize a recognizer
function init_recognizer($uuid) {
  global $rest_server, $debug;
  // the target url
  $target_url = $rest_server . "init";
  if ($debug) {
    echo "\nrequest to " . $target_url;  
  }
  // create a json file
  $data = array("uuid" => $uuid);
  $json_data = json_encode($data);
  // create the POST request
  $request = curl_init($target_url);
  curl_setopt($request, CURLOPT_POST, true);
  curl_setopt($request, CURLOPT_POSTFIELDS, $json_data);
  curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($request, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($json_data)
  ));

  $response = curl_exec($request);
  if ($debug) {
    echo "\nresponse: " . $response;
  }

  curl_close($request);
}

// check if a given dir is existed
// if not existed, craete it
function check_dir($dir) {
  // first check whether existed
  if (!file_exists($dir)) {
    // create it if not existed
    if (!mkdir($dir, 0774, true)) {
      print_r(error_get_last());
      echo "\nfailed to create " . $dir;
      return false;
    }
    return true;
  }
}

// create a zip file with all images received during the given phase
// For more information, https://stackoverflow.com/questions/4914750/how-to-zip-a-whole-folder-using-php
function compress_images($uuid, $phase) {
  global $zip_dir, $imgs_dir, $debug;

  $zip_dest = $zip_dir . '/' . $uuid;
  check_dir($zip_dest);

  $zip_dest =  $zip_dest . '/' . $phase . '.zip';
  $imgs_src = $imgs_dir . '/' . $uuid . '/' . $phase;

  if ($debug) {
    echo "\ncompressing into " . $zip_dest;
  }
  // open a zip file while checking if the zip file is already existed
  $zip = new ZipArchive;
  $res = $zip->open($zip_dest, ZipArchive::CREATE);
  if ($res) {
    // add all subdirs into the zip file
    $files = new RecursiveIteratorIterator(
              new RecursiveDirectoryIterator($imgs_src),
              RecursiveIteratorIterator::LEAVES_ONLY
            );

    foreach ($files as $name => $file) {
      // skip directories, as they would be added automatically
      if (!$file->isDir()) {
        // get real and relative path for current file
        $file_abs_path = $file->getRealPath();
        $file_rel_path = substr($file_abs_path, strlen($imgs_src) + 1);
        // add current file to archive
        $zip->addFile($file_abs_path, $file_rel_path);
      }
    }

    $zip->close();
    return $zip_dest;
  } elseif ($res == ZipArchive::ER_EXISTS) {
    return $zip_dest;
  } else {
    return "N/A";  
  }
}

// upload a zip file
function upload_zip($uuid, $zip_file) {
  global $rest_server, $debug;
  // the target url
  $target_url = $rest_server . "upload";
  if ($debug) {
    echo "\nrequest to " . $target_url;  
  }
  // create the request
  $request = curl_init($target_url);
  // echo ("created cURL request", 3, "/var/www/php.log");
  
  curl_setopt($request, CURLOPT_POST, true);
  curl_setopt(
    $request,
    CURLOPT_POSTFIELDS,
    array(
      'file' => new \CurlFile($zip_file, 'application/octet-stream', basename($zip_file)),
      'uuid' => $uuid
      )
  );
  curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

  // send the request and print its output
  // error_log("sending the request", 3, "/var/www/php.log");
  $response = curl_exec($request);
  if ($debug) {
    echo "\nresponse: " . $response;
  }
  // error_log($output, 3, "/var/www/php.log");
  // error_log("sent the request", 3, "/var/www/php.log");

  // close the session
  curl_close($request);
}

// upload a zip file and then trigger either training or testing
function upload_and_do($uuid, $phase, $zip_file) {
  global $rest_server, $debug;
  // the target url
  $target_url = $rest_server;
  // https://stackoverflow.com/questions/4366730/how-do-i-check-if-a-string-contains-a-specific-word
  if (strpos($phase, "train") !== false) {
    $target_url = $rest_server . "train";
  } elseif (strpos($phase, "test") !== false) {
    $target_url = $rest_server . "test";
  }
  
  if ($debug) {
    echo "\nrequest to " . $target_url;  
  }
  
  // create the request
  $request = curl_init($target_url);
  // echo ("created cURL request", 3, "/var/www/php.log");
  
  curl_setopt($request, CURLOPT_POST, true);
  curl_setopt(
    $request,
    CURLOPT_POSTFIELDS,
    array(
      'file' => new \CurlFile($zip_file, 'application/octet-stream', basename($zip_file)),
      'uuid' => $uuid,
      'phase' => $phase
      )
  );
  curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

  // send the request and print its output
  // error_log("sending the request", 3, "/var/www/php.log");
  $response = curl_exec($request);
  if ($debug) {
    echo "\nresponse: " . $response;
  }
  // error_log($output, 3, "/var/www/php.log");
  // error_log("sent the request", 3, "/var/www/php.log");

  // close the session
  curl_close($request);
}

// prepare a upload request
function prepare_upload($uuid, $phase) {
  global $imgs_dir, $zip_dir, $debug;

  if ($debug) {
    echo "images_dir: " . $imgs_dir;
    echo "\nzip_dir: " . $zip_dir;
  }

  // first compress the images
  $zip_file = compress_images($uuid, $phase);
  upload_and_do($uuid, $phase, $zip_file);
}


?>