<?php
//params

$sAdresseFTP = '192.168.1.32';
$sUsername = 'pi';
$sMotDePasse = 'raspberry';
$sFolderNamePhoto = './photo/';
$sSousRep = 'home/pi/nova-photo-booth/photos';

//DEBUG
/*$sAdresseFTP = '10.10.10.10/photo';
$sUsername = 'rooter';
$sMotDePasse = 'azerty';
$sFolderNamePhoto = './photo/';*/

// Make our connection
$connection = ssh2_connect($sAdresseFTP);
 
// Authenticate
if (!ssh2_auth_password($connection, $sUsername, $sMotDePasse)) {
    throw new Exception('Unable to connect.');
}
 
// Create our SFTP resource
if (!$sftp = ssh2_sftp($connection)) {
    throw new Exception('Unable to create SFTP connection.');
}
 
/**
  * Now that we have our SFTP resource, we can open a directory resource
  * to get us a list of files. Here we will use the $sftp resource in
  * our address string as I previously mentioned since our ssh2:// 
  * protocol allows it.
  */
$files = array();
$dirHandle = opendir("ssh2.sftp://$sftp/".$sSousRep.'/');
 
// Properly scan through the directory for files, ignoring directory indexes (. & ..)
while (false !== ($file = readdir($dirHandle))) {
    if ($file != '.' && $file != '..' && false === strpos($file,'2019-05-15')) {
        $files[] = $file;
    }
}

$aNewFiles = [];
 
/**
  * Using our newly created list of files, we can go about downloading. We will
  * open a remote stream and a local stream and write from one to the other.
  * We will use error suppression on the fopen call to suppress warnings from
  * not being able to open the file.
  */
if (count($files)) {
    foreach ($files as $fileName) {
        //if he has not already download
        if(false === file_exists($sFolderNamePhoto.$fileName)){

            // Remote stream
            if (!$remoteStream = @fopen("ssh2.sftp://$sftp/".$sSousRep."/".$fileName, 'r')) {
                throw new Exception("Unable to open remote file: $fileName");
            } 
    
            // Local stream
            if (!$localStream = @fopen($sFolderNamePhoto.$fileName, 'w')) {
                throw new Exception("Unable to open local file for writing: /photo/$fileName");
            }
    
            // Write from our remote stream to our local stream
            $read = 0;
            $fileSize = filesize("ssh2.sftp://$sftp/".$sSousRep."/".$fileName);
            while ($read < $fileSize && ($buffer = fread($remoteStream, $fileSize - $read))) {
                // Increase our bytes read
                $read += strlen($buffer);
    
                // Write to our local file
                if (fwrite($localStream, $buffer) === FALSE) {
                    throw new Exception("Unable to write to local file: /localdir/$fileName");
                }
            }

            array_push($aNewFiles, $sFolderNamePhoto.$fileName);
    
            // Close our streams
            fclose($localStream);
            fclose($remoteStream);
        }

    }


    if(count($aNewFiles) > 0){
        echo 1;
    }

//echo 1 ;
}