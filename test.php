<?php
include('Net/SSH2.php');

$ssh = new Net_SSH2('69.69.69.69', '22');
if (!$ssh->login('root', '69696969passwd')) {
    exit('Login Failed');
}

echo $ssh->exec('cd /root/');
echo $ssh->exec('echo "Hello World!" > out.xXx');
echo $ssh->exec('cat out.xXx');
echo $ssh->exec('rm -Rf /root/out.xXx');
?>
