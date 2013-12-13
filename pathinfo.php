<?php

$path_parts = pathinfo($argv[1]);

echo 'dirname: ', $path_parts['dirname'], "\n";
echo 'basename: ', $path_parts['basename'], "\n";
echo 'extension: ', $path_parts['extension'], "\n";
echo 'filename: ', $path_parts['filename'], "\n"; // since PHP 5.2.0
?>