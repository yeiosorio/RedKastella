


<?php wt_theme_generator('wt_footerWrapper',$post->ID);?>

<?php if(wt_get_option('footer','footer_top')):?>
<?php wt_theme_generator('wt_footerTop',$post->ID);?>
		<?php dynamic_sidebar(__('Footer Top Area','wt_admin')); ?>        
	</div> <!-- End container -->
</footer> <!-- End footerTop -->
<?php endif;?>

<?php if(wt_get_option('footer','footer')):?>
<?php wt_theme_generator('wt_footer',$post->ID);?>
	<div class="container">
		<div class="row">
<?php
$footer_column = wt_get_option('footer','column');
if(is_numeric($footer_column)):
	switch ( $footer_column ):
		case 1:
			$class = 'wt_footer_col col-lg-12 col-md-12 col-sm-12';
			break;
		case 2:
			$class = 'wt_footer_col col-lg-6 col-md-6 col-sm-6';
			break;
		case 3:
			$class = 'wt_footer_col col-lg-4 col-md-4 col-sm-4';
			break;
		case 4:
			$class = 'wt_footer_col col-lg-3 col-md-3 col-sm-3';
			break;
		case 6:
			$class = 'wt_footer_col col-lg-2 col-md-2 col-sm-2';
			break;
	endswitch;
	for( $i=1; $i<=$footer_column; $i++ ):
?>	
	<div class="<?php echo $class; ?>" data-animation="fadeInUp" data-animation-delay="<?php echo $i*200; ?>"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php		
	endfor;
else:
	switch($footer_column):
		case 'col-lg-9_col-lg-3':
?>
		<div class="wt_footer_col col-lg-9 col-md-9 col-sm-9"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-3 col-md-3 col-sm-3"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php
			break;
		case 'col-lg-3_col-lg-9':
?>
		<div class="wt_footer_col col-lg-3 col-md-3 col-sm-3"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-9 col-md-9 col-sm-9"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php
			break;
		case 'col-lg-2_col-lg-5_col-lg-5':
?>
		<div class="wt_footer_col col-lg-2 col-md-2 col-sm-2"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-5 col-md-5 col-sm-5"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-5 col-md-5 col-sm-5"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php
			break;
		case 'col-lg-3_col-lg-3_col-lg-6':
?>
		<div class="wt_footer_col col-lg-3 col-md-3 col-sm-3"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-3 col-md-3 col-sm-3"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-6 col-md-6 col-sm-6"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php
			break;
		case 'col-lg-3_col-lg-6_col-lg-3':
?>
		<div class="wt_footer_col col-lg-3 col-md-3 col-sm-3"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-6 col-md-6 col-sm-6"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-3 col-md-3 col-sm-3"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php
			break;

		case 'col-lg-3_col-lg-9':
?>
		<div class="wt_footer_col col-lg-3 col-md-3 col-sm-3"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-9 col-md-9 col-sm-9"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php
			break;
		case 'col-lg-6_col-lg-3_col-lg-3':
?>
		<div class="wt_footer_col col-lg-6 col-md-6 col-sm-6"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-3 col-md-3 col-sm-3"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-3 col-md-3 col-sm-3"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php
			break;
		case 'col-lg-4_col-lg-8':
?>
		<div class="wt_footer_col col-lg-4 col-md-4 col-sm-4"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-8 col-md-8 col-sm-8"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php
			break;
		case 'col-lg-8_col-lg-4':
?>
		<div class="wt_footer_col col-lg-8 col-md-8 col-sm-8"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-4 col-md-4 col-sm-4"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php
			break;
		case 'col-lg-9_col-lg-3':
?>
		<div class="wt_footer_col col-lg-9 col-md-9 col-sm-9"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-3 col-md-3 col-sm-3"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php
			break;
		case 'col-lg-5_col-lg-7':
?>
		<div class="wt_footer_col col-lg-5 col-md-5 col-sm-5"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-7 col-md-7 col-sm-7"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php
			break;
		case 'col-lg-5_col-lg-5_col-lg-2':
?>
		<div class="wt_footer_col col-lg-5 col-md-5 col-sm-5"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-5 col-md-5 col-sm-5"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
		<div class="wt_footer_col col-lg-2 col-md-2 col-sm-2"><?php wt_theme_generator('wt_footer_sidebar'); ?></div>
<?php
			break;
	endswitch;
endif;
?>      
		</div> <!-- End row -->
	</div> <!-- End container -->
</footer> <!-- End footer -->
<?php endif;?>
<?php if(wt_get_option('footer','sub_footer')):?>
<?php wt_theme_generator('wt_footerBottom',$post->ID);?>
		<?php if(wt_get_option('footer','copyright')):?>
			<div id="copyright">
				<p class="copyright">
				<?php echo wpml_t(THEME_NAME, 'Copyright Footer Text',stripslashes(wt_get_option('footer','copyright'))); ?>
			</div>
		<?php endif;?>
		<?php dynamic_sidebar(__('Footer Bottom Area','wt_admin')); ?>      
	</div> <!-- End container -->
</footer> <!-- End footerBottom -->
<?php endif;?>
</div> <!-- End footerWrapper -->

</div> <!-- End wt_page -->
</div> <!-- End wrapper -->
<script type="text/javascript">
/* <![CDATA[ */
var theme_uri="<?php echo THEME_URI;?>";
/* ]]> */
</script>
<?php
wt_scripts();
wt_add_cufon_code_footer();
if(wt_get_option('general','analytics')){
	echo stripslashes(wt_get_option('general','analytics'));
}
?>
<?php
wp_footer();
?>
</body>
</html>