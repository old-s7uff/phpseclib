<?php
include('Net/SSH2.php');
include('File/ANSI.php');
$return = TRUE;
$link = mysqli_connect("localhost", "root", "secret", "gpanel");
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
$domain = mysqli_real_escape_string($link, $_POST['domain']);

$sql = "INSERT INTO lgsl (domain) VALUES ('$domain')";
if(mysqli_query($link, $sql)){
    echo "Domain Hosted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

$ssh = new Net_SSH2('1.1.1.1', '22');
if (!$ssh->login('root', 'secret')) {
    exit('Login Failed');
}
$ansi = new File_ANSI();

$ssh->enablePTY();
$ssh->exec("raws ahost $domain");
$ssh->exec("service apache2 reload -S");
$ssh->setTimeout(5);
$ansi->appendString($ssh->read());
echo $ansi->getScreen();

mysqli_close($link);
?>
