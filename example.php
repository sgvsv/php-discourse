<?php
// There is no example

require_once 'src/API.php';
$API = new \sgvsv\Discourse\API('https://myforum.url.com','MYAPIKEY');
print_r($API->latestTopics());
