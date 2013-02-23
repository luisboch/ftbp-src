<?php
// set configurations.
if (ENVIRONMENT =='development'){
    // Add new include path of local folder only on development mode
    set_include_path(get_include_path().';C:\xampp\htdocs\includes');
}

?>
