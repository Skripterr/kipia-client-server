<?php 

Application\Core\Utils\Headers::createHeaders($httpResponseCode, 'json');
include $content;
die();

?>