<?php
require_once (TEMPLATEPATH . '/framework/theme-files.php');
$theme = new wt_themeFiles();
$theme->wt_init(array(
    'theme_name' => 'WhoaThemes', 
    'theme_slug' => 'whoathemes'
));



// update_option( 'siteurl', 'http://192.168.1.50/kastella_landing' );
// update_option( 'home', 'http://192.168.1.50/kastella_landing' );


 update_option( 'siteurl', 'http://redkastella.com/landing' );
 update_option( 'home', 'http://redkastella.com/landing' );
