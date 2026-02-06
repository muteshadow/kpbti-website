<?php
header("Content-Type: text/css");

echo file_get_contents("reset.css");
echo file_get_contents("style.css");
echo file_get_contents("animation.css");
echo file_get_contents("mobile.css");