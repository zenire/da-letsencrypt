#!/usr/local/bin/php -n

<?


parse_str(getenv('POST'));

if (!isset($domain))
    die ('There is an error with receiving your domain! Contact the plugin developer, please.');
if (!isset($email))
    die('I did not receive your e-mail address. Go back and enter your e-mail address, please.');

echo 'I am requesting a certificate for you right now. Please standby.';

shell_exec('/usr/local/share/letsencrypt/bin/letsencrypt certonly --webroot -w /home/'.$user.'/domains/'.$domain.'/public_html -d '.$domain.' --email '.$email.' --agree-tos');

if (file_exists('/etc/letsencrypt/live/'.$domain.'/cert.pem') == 1) {
    echo 'Certificate requested succesfully! I will install your certificate right now.';
} else {
    echo 'Oops.. Something went wrong with requesting your certificate. Please check the logs or contact your hoster/ISP when you can not access the logs.';
    exit 1;
}

shell_exec('mv /etc/letsencrypt/live/'.$domain.'/privkey.pem //usr/local/directadmin/data/users/'.$user.'/domains/'.$domain.'/'.$domain.'.key');
shell_exec('mv /etc/letsencrypt/live/'.$domain.'/chain.pem //usr/local/directadmin/data/users/'.$user.'/domains/'.$domain.'/'.$domain.'.cacert');
shell_exec('mv /etc/letsencrypt/live/'.$domain.'/cert.pem //usr/local/directadmin/data/users/'.$user.'/domains/'.$domain.'/'.$domain.'.cert');