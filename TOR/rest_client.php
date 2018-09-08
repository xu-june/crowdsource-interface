

<?php

// <!-- 
//   send images to a deep learning server
//     1. first compress (zip) the images
//     2. then send the zip file to the server
// -->

// global variables
// create a POST request with the zip file and uuid info using cURL
$rest_server = "http://128.8.224.124:5000/";
// a base dir for zip files
$zip_dir = realpath("zips");
//$zip_dir = getcwd()."/zips";
// a base dir for image files
$imgs_dir = realpath("images");
// debug boolean
$debug = false;

function init_vars() {
	global $rest_server, $zip_dir, $imgs_dir, $debug;
	$rest_server = "http://128.8.224.124:5000/";
	$zip_dir = realpath("zips");
	$imgs_dir = realpath("images");
	$debug = false;
}


/*
  initialize a recognizer
    @input  : uuid (string)
    @output : N/A
 */
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


/*
  stop a recognizer
    @input  : uuid (string)
    @output : N/A
 */
function stop_recognizer($uuid) {
  global $rest_server, $debug;
  // the target url
  $target_url = $rest_server . "stop";
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

/*
  check if a user can trigger the training
    @input  : uuid (string), phase (string)
    @output : N/A
 */
function check_for_training($uuid, $phase) {
  global $rest_server, $debug;
  // the target url
  $target_url = $rest_server . "check";
  if ($debug) {
    echo "\nrequest to " . $target_url;  
  }
  // create a json file
  $data = array("uuid" => $uuid, "phase" => $phase);
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
  // response includes the following:
  //  result: "True" or "False"
  //  before_me: (the number of participants waiting in the queue)
  //             or -1 --- error
  if ($debug) {
    echo "\nresponse: " . $response;
  }

  curl_close($request);
  
  $json = json_decode($response, true);
  // $ret = $json['result'];
  $num_waiting = $json['before_me']

  return $num_waiting
}


/*
  check if a given dir is existed; if not existed, craete it
    @input  : dir (string)
    @output : true/false (boolean)
 */
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


/*
  create a zip file with all images received during the given phase
  for more information, https://stackoverflow.com/questions/4914750/how-to-zip-a-whole-folder-using-php
    @input  : uuid (string),
              trial (string),
              phase (string)
    @output : zipfile path (on success)
              "N/A" (on failure)
 */
function compress_images($uuid, $trial, $phase) {
  global $zip_dir, $imgs_dir, $debug;
  
  $zip_dest = $zip_dir . '/' . $uuid;
  //error_log($zip_dest."This is the folder, zip dir: " .$zip_dir. " Curr dir: ".getcwd());
  check_dir($zip_dest);

  $zip_dest =  $zip_dest . '/' . $trial . '_' . $phase . '.zip';
  $imgs_src = $imgs_dir . '/' . $uuid . '/' . $trial . '/' . $phase;

  // open a zip file while checking if the zip file is already existed
  $zip = new ZipArchive;
  $res = $zip->open($zip_dest, ZipArchive::CREATE);

  if ($res) {
    if ($debug) {
      echo "\ncompressing " . $imgs_src . " into " . $zip_dest;
    }  
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
    if ($debug) {
      echo "\n Error occurred during the compression";
    }
    return "N/A";  
  }
}


/*
  upload a zip file
    @input  : uuid (string),
              zipfile path (string)
    @output : N/A
 */
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


/*
  upload a zip file and then trigger the training
    @input  : uuid (string),
              trial (string),
              phase (string),
              zipfile path (string)
    @output : N/A
 */
function upload_and_train($uuid, $trial, $phase, $zip_file) {
  global $rest_server, $debug;
  // the target url
  $target_url = $rest_server;
  // https://stackoverflow.com/questions/4366730/how-do-i-check-if-a-string-contains-a-specific-word
  if (strpos($phase, "train") !== false) {
    $target_url = $rest_server . "train";
  } else {
    echo "\nERROR: wrong phase!!";
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
      'phase' => $trial . '_' . $phase
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

  $json = json_decode($response, true);
  // $ret = $json['result'];
  $num_waiting = $json['before_me']

  return $num_waiting
}


/*
  upload an image file and then trigger the testing
    @input  : uuid (string),
              phase (string),
              image file path (string)
    @output : label (string)
 */
function upload_and_test($uuid, $phase, $img_file) {
  global $rest_server, $debug;
  // the target url
  $target_url = $rest_server;
  // https://stackoverflow.com/questions/4366730/how-do-i-check-if-a-string-contains-a-specific-word
  if (strpos($phase, "test") !== false) {
    $target_url = $rest_server . "test";
  } else {
    echo "\nERROR: wrong phase!!";
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
      'file' => new \CurlFile($img_file, 'application/octet-stream', basename($img_file)),
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

  // process JSON response
  $json = json_decode($response, true);
  $label = $json['label'];

  return $label;
}


/*
  prepare a upload request
    @input  : uuid (string),
              trial (string),
              phase (string)
    @output : N/A
 */
function prepare_upload($uuid, $trial, $phase) {
  global $imgs_dir, $zip_dir, $debug;

  if ($debug) {
    echo "images_dir: " . $imgs_dir;
    echo "\nzip_dir: " . $zip_dir;
  }

  // first compress the images
  $zip_file = compress_images($uuid, $trial, $phase);
  return upload_and_train($uuid, $trial, $phase, $zip_file);
}


?>