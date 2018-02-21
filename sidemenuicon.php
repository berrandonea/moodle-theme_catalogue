<?php
require_once('../../config.php');
$key = required_param('key', PARAM_ALPHA);
$src = "$CFG->wwwroot/blocks/catalogue/pix/coursehome.png";
echo "<img src='$src' height='30px'>";
