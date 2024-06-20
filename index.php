<?php
// Check if the index.html file exists
if (file_exists('index.html')) {
    // Read and output the contents of index.html
    readfile('index.html');
} else {
    // Display an error message if index.html is not found
    echo 'index.html file not found.';
}
?>
