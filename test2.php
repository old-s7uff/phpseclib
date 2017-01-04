<?php
include('Net/SSH2.php');
include('File/ANSI.php');
$return = TRUE;
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "secret", "gpanel");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
// Escape user inputs for security
$domain = mysqli_real_escape_string($link, $_POST['domain']);
 
// attempt insert query execution
$sql = "INSERT INTO lgsl (domain) VALUES ('$domain')";
if(mysqli_query($link, $sql)){
    echo "Server added successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// close connection
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
