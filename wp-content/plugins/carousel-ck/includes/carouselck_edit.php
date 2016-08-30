<?php
defined('ABSPATH') or die;

// check if the user has the rights to access this page
if (!current_user_can('manage_options')) {
	wp_die(__('You do not have sufficient permissions to access this page.'));
}

// load scripts
wp_enqueue_media();
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core ');
wp_enqueue_script('jquery-ui-dialog');
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_style('wp-jquery-ui-dialog');
wp_enqueue_style('wp-color-picker');
wp_enqueue_style('ckradio2', $this->pluginurl . '/cklibrary/ckfields/ckradio2/ckradio2.css');
wp_enqueue_script('ckradio2', $this->pluginurl . '/cklibrary/ckfields/ckradio2/ckradio2.js', array('jquery'));
wp_enqueue_script('ckcolor', $this->pluginurl . '/cklibrary/ckfields/ckcolor/ckcolor.js', array('jquery', 'jquery-ui-button', 'wp-color-picker'));

// init variables
$post = $post_type = $post_type_object = null;

// check if the post exists
if (isset($_GET['id'])) {
	$post_id = (int) $_GET['id'];
} elseif (isset($_POST['post_ID'])) {
	$post_id = (int) $_POST['post_ID'];
} else {
	$post_id = 0;
}

// get the existing post
if ($post_id) {
	$post = get_post($post_id);
}

if ($post) {
	$post_type = $post->post_type;
}

// check if we get a slideshow object
if (0 !== $post_id && 'carouselck' !== $post->post_type) {
	wp_die(__('Invalid post type'));
}

// do an action
if (!empty($_REQUEST['action']) && isset($post_id)) {
	if ($_REQUEST['action'] == 'save' && wp_verify_nonce($_REQUEST['_wpnonce'], 'carouselck_save')) {
		$ck_post = array(
			'ID' => (int) $post_id,
			'post_title' => sanitize_text_field($_POST['post_title']),
			'post_content' => '',
			'post_type' => 'carouselck',
			'post_status' => 'publish',
			'comment_status' => 'closed',
			'ping_status' => 'closed'
		);

		// save the post into the database
		$ck_post_id = wp_insert_post($ck_post);

		// Update the meta field for the slideshow settings
		update_post_meta($ck_post_id, 'carousel-ck-params', $_POST['carousel-ck-params']);
		update_post_meta($ck_post_id, 'carousel-ck-slides', $_POST['carousel-ck-slides']);
		// TODO : ajouter notice en haut de page
		wp_redirect(home_url() . '/wp-admin/admin.php?page=carouselck_edit&action=updated&id=' . (int) $ck_post_id);
		exit;
	}
}
// get the settings for the post
$this->params = json_decode(str_replace('|qq|', '"', get_post_meta((int) $post_id, 'carousel-ck-params', TRUE)));
if ($this->ispro) $this->pro_class->params = $this->params;
$post_title = isset( $post->post_title ) ? $post->post_title : '';
?>
<?php //echo $CK_Notices; // TODO : creer notices pour dire "bien enregistre"  ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->pluginurl ?>/assets/carouselck_edit.css" media="all" />
<script type="text/javascript" src="<?php echo $this->pluginurl ?>/assets/carouselck_edit.js" ></script>
<input type="hidden" id="demo-params" name="demo-params" value="{|qq|slides_sources|qq|:|qq|slidemanager|qq|,|qq|wrapheight|qq|:|qq|40%|qq|,|qq|navigation|qq|:|qq|1|qq|,|qq|thumbnails|qq|:|qq|1|qq|,|qq|pagination|qq|:|qq|1|qq|,|qq|theme|qq|:|qq|default|qq|,|qq|skin|qq|:|qq|carouselck_black_skin|qq|,|qq|imageheight|qq|:|qq|62%|qq|,|qq|imagesratio|qq|:|qq|0.65|qq|,|qq|thumbnailheight|qq|:|qq|80|qq|,|qq|playPause|qq|:|qq|1|qq|,|qq|playPause1|qq|:|qq|1|qq|,|qq|playPause0|qq|:|qq|0|qq|,|qq|captiontitle_fontcolor|qq|:|qq|#ffffff|qq|,|qq|undefined|qq|:|qq|Clear|qq|,|qq|captiontitle_fontsize|qq|:|qq|18|qq|,|qq|captiontitle_fontfamily|qq|:|qq||qq|,|qq|captiontitle_fontweight|qq|:|qq|normal|qq|,|qq|captiontitle_fontweightnormal|qq|:|qq|normal|qq|,|qq|captiontitle_fontweightbold|qq|:|qq|bold|qq|,|qq|captiondesc_fontcolor|qq|:|qq|#ffffff|qq|,|qq|captiondesc_fontsize|qq|:|qq|12|qq|,|qq|captiondesc_fontfamily|qq|:|qq||qq|,|qq|captiondesc_fontweight|qq|:|qq|normal|qq|,|qq|captiondesc_fontweightnormal|qq|:|qq|normal|qq|,|qq|captiondesc_fontweightbold|qq|:|qq|bold|qq|,|qq|caption_bgcolor1|qq|:|qq||qq|,|qq|caption_bgcolor2|qq|:|qq||qq|,|qq|caption_bgopacity|qq|:|qq|0.8|qq|,|qq|caption_margintop|qq|:|qq||qq|,|qq|caption_marginright|qq|:|qq||qq|,|qq|caption_marginbottom|qq|:|qq||qq|,|qq|caption_marginleft|qq|:|qq||qq|,|qq|caption_paddingtop|qq|:|qq||qq|,|qq|caption_paddingright|qq|:|qq||qq|,|qq|caption_paddingbottom|qq|:|qq||qq|,|qq|caption_paddingleft|qq|:|qq||qq|,|qq|caption_roundedcornerstl|qq|:|qq||qq|,|qq|caption_roundedcornerstr|qq|:|qq||qq|,|qq|caption_roundedcornersbr|qq|:|qq||qq|,|qq|caption_roundedcornersbl|qq|:|qq||qq|,|qq|caption_bordercolor|qq|:|qq||qq|,|qq|caption_borderwidth|qq|:|qq||qq|,|qq|caption_shadowcolor|qq|:|qq|#|qq|,|qq|caption_shadowblur|qq|:|qq||qq|,|qq|caption_shadowspread|qq|:|qq||qq|,|qq|caption_shadowoffsetx|qq|:|qq||qq|,|qq|caption_shadowoffsety|qq|:|qq||qq|,|qq|caption_shadowinset|qq|:|qq|1|qq|,|qq|caption_shadowinset0|qq|:|qq|0|qq|,|qq|caption_shadowinset1|qq|:|qq|1|qq|,|qq|image_roundedcornerstl|qq|:|qq||qq|,|qq|image_roundedcornerstr|qq|:|qq||qq|,|qq|image_roundedcornersbr|qq|:|qq||qq|,|qq|image_roundedcornersbl|qq|:|qq||qq|,|qq|image_bordercolor|qq|:|qq||qq|,|qq|image_borderwidth|qq|:|qq||qq|,|qq|image_shadowcolor|qq|:|qq||qq|,|qq|image_shadowblur|qq|:|qq||qq|,|qq|image_shadowspread|qq|:|qq||qq|,|qq|image_shadowoffsetx|qq|:|qq||qq|,|qq|image_shadowoffsety|qq|:|qq||qq|,|qq|image_shadowinset|qq|:|qq|0|qq|,|qq|image_shadowinset0|qq|:|qq|0|qq|,|qq|image_shadowinset1|qq|:|qq|1|qq|,|qq|time|qq|:|qq|7000|qq|,|qq|duration|qq|:|qq|600|qq|,|qq|captioneffect|qq|:|qq|moveFromLeft|qq|,|qq|captionduration|qq|:|qq|1500|qq|,|qq|autoAdvance|qq|:|qq|1|qq|,|qq|autoAdvance1|qq|:|qq|1|qq|,|qq|autoAdvance0|qq|:|qq|0|qq|,|qq|displayorder|qq|:|qq|normal|qq|}" />
<input type="hidden" id="demo-slides" name="demo-slides" value="[{|qq|imgname|qq|:|qq|wp-content/plugins/carousel-ck/slides/bridge.jpg|qq|,|qq|title|qq|:|qq|This is a bridge|qq|,|qq|description|qq|:|qq|You can get more information about Carousel CK for Wordpress on <a href='http://www.wp-pluginsck.com'>WP Plugins CK</a>|qq|,|qq|imglink|qq|:|qq||qq|,|qq|imgtarget|qq|:|qq|default|qq|,|qq|imgalignment|qq|:|qq|default|qq|,|qq|imgvideo|qq|:|qq||qq|,|qq|imgtime|qq|:|qq||qq|},{|qq|imgname|qq|:|qq|wp-content/plugins/carousel-ck/slides/road.jpg|qq|,|qq|title|qq|:|qq|On the road again|qq|,|qq|description|qq|:|qq|When the sky is blue, the rain will come.|qq|,|qq|imglink|qq|:|qq||qq|,|qq|imgtarget|qq|:|qq|default|qq|,|qq|imgalignment|qq|:|qq|default|qq|,|qq|imgvideo|qq|:|qq||qq|,|qq|imgtime|qq|:|qq||qq|},{|qq|imgname|qq|:|qq|wp-content/plugins/carousel-ck/slides/big_bunny_fake.jpg|qq|,|qq|title|qq|:|qq||qq|,|qq|description|qq|:|qq||qq|,|qq|imglink|qq|:|qq||qq|,|qq|imgtarget|qq|:|qq|default|qq|,|qq|imgalignment|qq|:|qq|default|qq|,|qq|imgvideo|qq|:|qq|http://player.vimeo.com/video/2203727|qq|,|qq|imgtime|qq|:|qq||qq|}]" />
<form id="carouselck-edit" method="post" action="">
	<h2>Carousel CK - <?php echo __('Edit') . ' <span class="small">[ ' . $post_title . ' ]</span>'; ?>
		<input type="button" class="button button-primary" name="save_carouselck" value="<?php esc_attr_e('Save'); ?>" onclick="save_slideshow()" />
	</h2>
	<input type="hidden" name="action" value="save"/>
	<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('carouselck_save'); ?>" />
	<input type="hidden" name="ID" value="<?php echo $post_id ?>" />
	<input type="hidden" name="post_content" id="post_content" value="" />
	<label for="post_title"><?php _e('Name'); ?></label>
	<input type="text" name="post_title" id="post_title" value="<?php echo $post_title ?>" />
	<a class="button" href="javascript:void(0)" onclick="load_carouselck_demo_data()"><img src="<?php echo $this->pluginurl ?>/images/magic.png" style="margin: -1px 3px 0 0;vertical-align: middle;" /><?php _e('Load demo data'); ?></a>
	<input type="hidden" name="carousel-ck-params" id="carousel-ck-params" value="<?php echo get_post_meta($post_id, 'carousel-ck-params', TRUE); ?>" />
	<input type="hidden" name="carousel-ck-slides" id="carousel-ck-slides" value="<?php echo get_post_meta($post_id, 'carousel-ck-slides', TRUE); ?>" />
</form>
<div id="slideshowedition">
	<div class="menulinkck current" tab="tab_images"><?php _e('Images'); ?></div>
	<div class="menulinkck" tab="tab_styles"><?php _e('Styles'); ?></div>
	<div class="menulinkck" tab="tab_effects"><?php echo _e('Effects'); ?></div>
	<div class="clr"></div>
	<div class="tabck menustyles current" id="tab_images">
		<!--<input type="hidden" name="slides_sources" id="slides_sources" value="slidemanager" />-->
		<div class="saveparam">
		<?php
		if ($this->ispro) {
			$options_slides_sources = array('slidemanager' => __('Slides Manager'), 'autoloadfolder' => __('Autoload from a folder'));
			echo $this->get_field('select', 'slides_sources', $this->get_param('slides_sources', 'slidemanager'), 'carouselckparams', $options_slides_sources, false, 'onchange="show_slides_sources();"');
		} else { ?>
			<input type="hidden" name="slides_sources" id="slides_sources" value="slidemanager" />
		<?php
		}
		?>
		</div>
		<div class="slides_source" data-source="slidemanager">
			<div id="ckslides">
				<input name="ckaddslide" id="ckaddslide" type="button" value="<?php _e('Add a Slide') ?>" class="button button-secondary" onclick="addslideck();"/>
				<span id="addslide_waiticon"></span>
				<?php
				if (get_post_meta($post_id, 'carousel-ck-slides', TRUE)) {
					$slides = json_decode(str_replace('|qq|', '"', get_post_meta((int) $post_id, 'carousel-ck-slides', TRUE)));
					if ($slides && count($slides)) {
						foreach ($slides as $i => $slide) {
							$this->add_slide($i, $slide);
						}
					}
				}
				?>
			</div>
			<input name="ckaddslide" id="ckaddslide1" type="button" value="<?php _e('Add a Slide') ?>" class="button button-secondary" onclick="addslideck();"/>
		</div>
		<div class="slides_source saveparam" data-source="autoloadfolder">
			<?php 
			if ($this->ispro) {
				$this->pro_class->render_autoload_from_folder_option();
			}
			?>
		</div>
	</div>
	<div class="tabck menustyles saveparam" id="tab_styles">
		<div style="background: #fff;border:1px solid #ddd;">
			<div style="background: url(<?php echo $this->pluginurl; ?>/images/carouselck_styles.png) 100px 50px no-repeat; width:700px;height:360px;position:relative;margin:0px auto 10px auto;">
				<div style="position:absolute;left:10px;top:150px;width:105px;">
					<div style="position:absolute;left:5px;top:-18px;"><?php _e('Height') ?></div>
					<input id="wrapheight" type="text" value="<?php echo $this->get_param('wrapheight'); ?>" name="wrapheight" style="">
				</div>
				<?php
				$options_yes_no = array(
					'1' => __('Yes')
					, '0' => __('No')
				);
				?>
				<div style="position:absolute;left:570px;top:300px;width:105px;">
					<div style="position:absolute;left:5px;top:-18px;"><?php _e('Navigation') ?></div>
					<?php
					echo $this->get_field('select', 'navigation', $this->get_param('navigation'), '', $options_yes_no);
					?>
				</div>
				<div style="position:absolute;left:350px;top:300px;width:105px;">
					<div style="position:absolute;left:5px;top:-18px;"><?php _e('Thumbnails') ?></div>
					<?php
					echo $this->get_field('select', 'thumbnails', $this->get_param('thumbnails'), '', $options_yes_no);
					?>
				</div>
				<div style="position:absolute;left:480px;top:300px;width:105px;">
					<div style="position:absolute;left:5px;top:-18px;"><?php _e('Pagination') ?></div>
					<?php
					echo $this->get_field('select', 'pagination', $this->get_param('pagination'), '', $options_yes_no);
					?>
				</div>
			</div>
		</div>
		<div>
			<label for="theme"><?php _e('Theme'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/photo.png" />
			<?php echo $this->get_field('select', 'theme', $this->get_param('theme'), 'theme', CKfolder::folders($this->plugindir . '/themes'), true); ?>
		</div>
		<div>
			<label for="skin"><?php _e('Skin'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/palette.png" />
			<?php
			$options_skin = array('carouselck_amber_skin' => 'carouselck_amber_skin',
				'carouselck_ash_skin' => 'carouselck_ash_skin',
				'carouselck_azure_skin' => 'carouselck_azure_skin',
				'carouselck_beige_skin' => 'carouselck_beige_skin',
				'carouselck_black_skin' => 'carouselck_black_skin',
				'carouselck_blue_skin' => 'carouselck_blue_skin',
				'carouselck_brown_skin' => 'carouselck_brown_skin',
				'carouselck_burgundy_skin' => 'carouselck_burgundy_skin',
				'carouselck_charcoal_skin' => 'carouselck_charcoal_skin',
				'carouselck_chocolate_skin' => 'carouselck_chocolate_skin',
				'carouselck_coffee_skin' => 'carouselck_coffee_skin',
				'carouselck_cyan_skin' => 'carouselck_cyan_skin',
				'carouselck_fuchsia_skin' => 'carouselck_fuchsia_skin',
				'carouselck_gold_skin' => 'carouselck_gold_skin',
				'carouselck_green_skin' => 'carouselck_green_skin',
				'carouselck_grey_skin' => 'carouselck_grey_skin',
				'carouselck_indigo_skin' => 'carouselck_indigo_skin',
				'carouselck_khaki_skin' => 'carouselck_khaki_skin',
				'carouselck_lime_skin' => 'carouselck_lime_skin',
				'carouselck_magenta_skin' => 'carouselck_magenta_skin',
				'carouselck_maroon_skin' => 'carouselck_maroon_skin',
				'carouselck_orange_skin' => 'carouselck_orange_skin',
				'carouselck_olive_skin' => 'carouselck_olive_skin',
				'carouselck_pink_skin' => 'carouselck_pink_skin',
				'carouselck_pistachio_skin' => 'carouselck_pistachio_skin',
				'carouselck_pink_skin' => 'carouselck_pink_skin',
				'carouselck_red_skin' => 'carouselck_red_skin',
				'carouselck_tangerine_skin' => 'carouselck_tangerine_skin',
				'carouselck_turquoise_skin' => 'carouselck_turquoise_skin',
				'carouselck_violet_skin' => 'carouselck_violet_skin',
				'carouselck_white_skin' => 'carouselck_white_skin',
				'carouselck_yellow_skin' => 'carouselck_yellow_skin');
			echo $this->get_field('select', 'skin', $this->get_param('skin'), 'skin', $options_skin);
			?>
		</div>
		<div>
			<label for="imageheight"><?php _e('Image ratio') ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/arrow-resize-045.png" />
			<input id="imageheight" type="text" value="<?php echo $this->get_param('imageheight'); ?>" name="imageheight" />
		</div>
		<div>
			<label for="imagesratio"><?php _e('Image Space') ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/applications-blue.png" />
			<input id="imagesratio" type="text" value="<?php echo $this->get_param('imagesratio'); ?>" name="imagesratio" />
		</div>
		<div>
			<label for="thumbnailheight"><?php _e('Thumbnail height') ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/height.png" />
			<input id="thumbnailheight" name="thumbnailheight" type="text" value="<?php echo $this->get_param('thumbnailheight') ?>" />
		</div>
		<div>
			<label for="playPause"><?php _e('Play / Pause button'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/control_play.png" />
			<?php
			// $options_playPause = array('yes' => __('1'), 'no' => __('0'));
			echo $this->get_field('radio', 'playPause', $this->get_param('playPause'), '', $options_yes_no);
			?>
		</div>
		<div class="ckheading"><?php _e('Caption Title'); ?></div>
		<div>
			<label for="captiontitle_color"><?php _e('Title Color'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/color.png" />
			<?php echo $this->get_field('color', 'captiontitle_fontcolor', $this->get_param('captiontitle_fontcolor')) ?>
		</div>
		<div>
			<label for="captiontitle_fontsize"><?php _e('Font Size'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/text_fontsize.png" />
			<?php echo $this->get_field('text', 'captiontitle_fontsize', $this->get_param('captiontitle_fontsize')) ?>
		</div>
		<div>
			<label for="captiontitle_fontfamily"><?php _e('Font Family'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/style.png" />
			<?php echo $this->get_field('text', 'captiontitle_fontfamily', $this->get_param('captiontitle_fontfamily')) ?>
			<?php if ($this->ispro) : ?>
				<br />
				<label for="title_googlefont"><?php _e('Google Font'); ?></label>
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/google.png" />
				<?php echo $this->get_field('text', 'captiontitle_googlefont', $this->get_param('captiontitle_googlefont')) ?>
				<a class="button btn-primary btn" href="javascript:void(0)" onclick="ck_load_googlefont();" title="Example: <link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>"><?php _e('Import'); ?></a>
				<span id="captiontitle_googlefont_wait" style="height:16px;width:16px;"></span>
			<?php endif; ?>
		</div>
		<div>
			<label for="captiontitle_fontweight"><?php _e('Font Weight'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/text_bold.png" />
			<?php
			$options_fontweight = array('normal' => __('Normal'), 'bold' => __('Bold'));
			echo $this->get_field('radio', 'captiontitle_fontweight', $this->get_param('captiontitle_fontweight'), '', $options_fontweight);
			?>
		</div>
		<div class="ckheading"><?php _e('Caption Description'); ?></div>
		<div>
			<label for="captiondesc_color"><?php _e('Description Color'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/color.png" />
<?php echo $this->get_field('color', 'captiondesc_fontcolor', $this->get_param('captiondesc_fontcolor')) ?>
		</div>
		<div>
			<label for="captiondesc_fontsize"><?php _e('Font Size'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/text_fontsize.png" />
<?php echo $this->get_field('text', 'captiondesc_fontsize', $this->get_param('captiondesc_fontsize')) ?>
		</div>
		<div>
			<label for="captiondesc_fontfamily"><?php _e('Font Family'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/style.png" />
			<?php echo $this->get_field('text', 'captiondesc_fontfamily', $this->get_param('captiondesc_fontfamily')) ?>
<?php if ($this->ispro) : ?>
				<br />
				<label for="title_googlefont"><?php _e('Google Font'); ?></label>
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/google.png" />
<?php echo $this->get_field('text', 'captiondesc_googlefont', $this->get_param('captiondesc_googlefont')) ?>
				<a class="button btn-primary btn" href="javascript:void(0)" onclick="ck_load_googlefont();" title="Example: <link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>"><?php _e('Import'); ?></a>
				<span id="captiondesc_googlefont_wait" style="height:16px;width:16px;"></span>
<?php endif; ?>
		</div>
		<div>
			<label for="captiondesc_fontweight"><?php _e('Font Weight'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/text_bold.png" />
			<?php
			// $options_fontweight = array('normal' => __('Normal'), 'bold' => __('Bold'));
			echo $this->get_field('radio', 'captiondesc_fontweight', $this->get_param('captiondesc_fontweight'), '', $options_fontweight);
			?>
		</div>
		<div class="ckheading"><?php _e('Caption Styles'); ?></div>
		<div>
			<label for="caption_bgcolor1"><?php _e('Background Color') ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/color.png" />
<?php echo $this->get_field('color', 'caption_bgcolor1', $this->get_param('caption_bgcolor1')) ?>
<?php echo $this->get_field('color', 'caption_bgcolor2', $this->get_param('caption_bgcolor2')) ?>
		</div>
		<div>
			<label for="caption_opacity"><?php _e('Opacity') ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/layers.png" />
<?php echo $this->get_field('text', 'caption_bgopacity', $this->get_param('caption_bgopacity')) ?>
		</div>
		<div>
			<label for="caption_margintop"><?php _e('Margin'); ?></label>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/margin_top.png" /></span>
			<span style="width:35px;" caption="<?php _e('Top'); ?>"><?php echo $this->get_field('text', 'caption_margintop', $this->get_param('caption_margintop')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/margin_right.png" /></span>
			<span style="width:35px;" caption="<?php _e('Right'); ?>"><?php echo $this->get_field('text', 'caption_marginright', $this->get_param('caption_marginright')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/margin_bottom.png" /></span>
			<span style="width:35px;" caption="<?php _e('Bottom'); ?>"><?php echo $this->get_field('text', 'caption_marginbottom', $this->get_param('caption_marginbottom')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/margin_left.png" /></span>
			<span style="width:35px;" caption="<?php _e('Left'); ?>"><?php echo $this->get_field('text', 'caption_marginleft', $this->get_param('caption_marginleft')) ?></span>
		</div>
		<div>
			<label for="caption_paddingtop"><?php _e('Padding'); ?></label>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/padding_top.png" /></span>
			<span style="width:35px;" caption="<?php _e('Top'); ?>"><?php echo $this->get_field('text', 'caption_paddingtop', $this->get_param('caption_paddingtop')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/padding_right.png" /></span>
			<span style="width:35px;" caption="<?php _e('Right'); ?>"><?php echo $this->get_field('text', 'caption_paddingright', $this->get_param('caption_paddingright')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/padding_bottom.png" /></span>
			<span style="width:35px;" caption="<?php _e('Bottom'); ?>"><?php echo $this->get_field('text', 'caption_paddingbottom', $this->get_param('caption_paddingbottom')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/padding_left.png" /></span>
			<span style="width:35px;" caption="<?php _e('Left'); ?>"><?php echo $this->get_field('text', 'caption_paddingleft', $this->get_param('caption_paddingleft')) ?></span>
		</div>
		<div>
			<label for="caption_roundedcornerstl"><?php _e('Border Radius'); ?></label>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/border_radius_tl.png" /></span>
			<span style="width:35px;" title="<?php _e('Top Left'); ?>"><?php echo $this->get_field('text', 'caption_roundedcornerstl', $this->get_param('caption_roundedcornerstl')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/border_radius_tr.png" /></span>
			<span style="width:35px;" title="<?php _e('Top Right'); ?>"><?php echo $this->get_field('text', 'caption_roundedcornerstr', $this->get_param('caption_roundedcornerstr')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/border_radius_br.png" /></span>
			<span style="width:35px;" title="<?php _e('Bottom Right'); ?>"><?php echo $this->get_field('text', 'caption_roundedcornersbr', $this->get_param('caption_roundedcornersbr')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/border_radius_bl.png" /></span>
			<span style="width:35px;" title="<?php _e('Bottom Left'); ?>"><?php echo $this->get_field('text', 'caption_roundedcornersbl', $this->get_param('caption_roundedcornersbl')) ?></span>
		</div>
		<div>
			<label for="caption_bordercolor"><?php _e('Border'); ?></label>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/color.png" /></span>
			<span><?php echo $this->get_field('color', 'caption_bordercolor', $this->get_param('caption_bordercolor')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/shape_square.png" /></span>
			<span style="width:35px;"><?php echo $this->get_field('text', 'caption_borderwidth', $this->get_param('caption_borderwidth')) ?></span>
		</div>
		<div>
			<label for="caption_shadowcolor"><?php _e('Shadow'); ?></label>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/color.png" /></span>
			<span><?php echo $this->get_field('color', 'caption_shadowcolor', $this->get_param('caption_shadowcolor')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/shadow_blur.png" /></span>
			<span style="width:35px;"><?php echo $this->get_field('text', 'caption_shadowblur', $this->get_param('caption_shadowblur')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/shadow_spread.png" /></span>
			<span style="width:35px;"><?php echo $this->get_field('text', 'caption_shadowspread', $this->get_param('caption_shadowspread')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/offsetx.png" /></span>
			<span style="width:35px;"><?php echo $this->get_field('text', 'caption_shadowoffsetx', $this->get_param('caption_shadowoffsetx')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/offsety.png" /></span>
			<span style="width:35px;"><?php echo $this->get_field('text', 'caption_shadowoffsety', $this->get_param('caption_shadowoffsety')) ?></span>
			<?php
			$optionsboxshadowinset = array('0' => __('Out'), '1' => __('In'));
			echo $this->get_field('radio', 'caption_shadowinset', $this->get_param('caption_shadowinset'), '', $optionsboxshadowinset);
			?>
		</div>
		<div class="ckheading"><?php _e('Image Styles'); ?></div>
		<div>
			<label for="image_roundedcornerstl"><?php _e('Border Radius'); ?></label>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/border_radius_tl.png" /></span>
			<span style="width:35px;" title="<?php _e('Top Left'); ?>"><?php echo $this->get_field('text', 'image_roundedcornerstl', $this->get_param('image_roundedcornerstl')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/border_radius_tr.png" /></span>
			<span style="width:35px;" title="<?php _e('Top Right'); ?>"><?php echo $this->get_field('text', 'image_roundedcornerstr', $this->get_param('image_roundedcornerstr')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/border_radius_br.png" /></span>
			<span style="width:35px;" title="<?php _e('Bottom Right'); ?>"><?php echo $this->get_field('text', 'image_roundedcornersbr', $this->get_param('image_roundedcornersbr')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/border_radius_bl.png" /></span>
			<span style="width:35px;" title="<?php _e('Bottom Left'); ?>"><?php echo $this->get_field('text', 'image_roundedcornersbl', $this->get_param('image_roundedcornersbl')) ?></span>
		</div>
		<div>
			<label for="image_bordercolor"><?php _e('Border'); ?></label>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/color.png" /></span>
			<span><?php echo $this->get_field('color', 'image_bordercolor', $this->get_param('image_bordercolor')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/shape_square.png" /></span>
			<span style="width:35px;"><?php echo $this->get_field('text', 'image_borderwidth', $this->get_param('image_borderwidth')) ?></span>
		</div>
		<div>
			<label for="image_shadowcolor"><?php _e('Shadow'); ?></label>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/color.png" /></span>
			<span><?php echo $this->get_field('color', 'image_shadowcolor', $this->get_param('image_shadowcolor')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/shadow_blur.png" /></span>
			<span style="width:35px;"><?php echo $this->get_field('text', 'image_shadowblur', $this->get_param('image_shadowblur')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/shadow_spread.png" /></span>
			<span style="width:35px;"><?php echo $this->get_field('text', 'image_shadowspread', $this->get_param('image_shadowspread')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/offsetx.png" /></span>
			<span style="width:35px;"><?php echo $this->get_field('text', 'image_shadowoffsetx', $this->get_param('image_shadowoffsetx')) ?></span>
			<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/offsety.png" /></span>
			<span style="width:35px;"><?php echo $this->get_field('text', 'image_shadowoffsety', $this->get_param('image_shadowoffsety')) ?></span>
			<?php
			$optionsboxshadowinset = array('0' => __('Out'), '1' => __('In'));
			echo $this->get_field('radio', 'image_shadowinset', $this->get_param('image_shadowinset'), '', $optionsboxshadowinset);
			?>
		</div>
	</div>
	<div class="tabck menustyles saveparam" id="tab_effects">
		<div>
			<label for="time"><?php _e('Slide duration'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/hourglass.png" />
			<?php echo $this->get_field('text', 'time', $this->get_param('time')) ?> ms
		</div>
		<div>
			<label for="duration"><?php _e('Transition duration'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/hourglass.png" />
			<?php echo $this->get_field('text', 'duration', $this->get_param('duration')) ?> ms
		</div>
		<div>
			<label for="captioneffect"><?php _e('Caption animation'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/application_view_gallery.png" />
			<?php
			$options_captioneffect = array(
					'moveFromLeft' => __('moveFromLeft')
					, 'fadeIn' => __('fadeIn')
				);
			echo $this->get_field('select', 'captioneffect', $this->get_param('captioneffect'), '', $options_captioneffect)
			?>
		</div>
		<div>
			<label for="captionduration"><?php _e('Caption duration'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/hourglass.png" />
			<?php echo $this->get_field('text', 'captionduration', $this->get_param('captionduration')) ?> ms
		</div>
		<div>
			<label for="autoAdvance"><?php _e('Autoplay'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/control_play.png" />
<?php
echo $this->get_field('radio', 'autoAdvance', $this->get_param('autoAdvance'), '', $options_yes_no);
?>
		</div>
		<div>
			<label for="displayorder"><?php _e('Display order'); ?></label>
			<img class="iconck" src="<?php echo $this->pluginurl ?>/images/control_repeat.png" />
<?php
$options_displayorder = '<option value="normal">' . __('Normal') . '</option>
					<option value="shuffle">' . __('Random') . '</option>';
echo $this->get_field('select', 'displayorder', $this->get_param('displayorder'), '', $options_displayorder)
?>
		</div>
	</div>

</div>

<script type="text/javascript">
	jQuery('#slideshowedition > div.tabck:not(.current)').hide();
	jQuery('#slideshowedition > .menulinkck').each(function(i, tab) {
		jQuery(tab).click(function() {
			jQuery('#slideshowedition > div.tabck').hide();
			jQuery('#slideshowedition > .menulinkck').removeClass('current');
			if (jQuery('#' + jQuery(tab).attr('tab')).length)
				jQuery('#' + jQuery(tab).attr('tab')).show();
			jQuery(this).addClass('current');
		});
	});

	function addslideck() {
		var data = {
			action: 'add_slide',
			number: jQuery('.ckslide').length
		};
		jQuery('#addslide_waiticon').addClass('ckwait_mini');
		jQuery.post(ajaxurl, data, function(response) {
			response = jQuery(response);
			jQuery('#ckslides').append(response);
			jQuery('#addslide_waiticon').removeClass('ckwait_mini');
			create_tabs_in_slide(response);
		});
	}

	function add_image_url_to_slideck(button, url) {
		button = jQuery(button);
		url_relative = url.replace('<?php echo get_site_url(); ?>/', '');
		var ckslide = jQuery(button.parents('.ckslide')[0]);
		ckslide.find('.ckslideimgname').val(url_relative);
		ckslide.find('.ckslideimgthumb').attr('src', '<?php echo get_site_url(); ?>/' + url_relative);
	}

	function renumber_slides() {
		var index = 0;
		jQuery('#ckslides .ckslide').each(function(i, slide) {
			jQuery('.ckslidenumber', jQuery(slide)).html(index);
			index++;
		});
	}

	jQuery(document).ready(function($) {
		show_slides_sources();

		jQuery("#ckslides").sortable({
			placeholder: "ui-state-highlight",
			handle: ".ckslidehandle",
			items: ".ckslide",
			axis: "y",
			forcePlaceholderSize: true,
			forceHelperSize: true,
			dropOnEmpty: true,
			tolerance: "pointer",
			placeholder: "placeholder",
					zIndex: 9999,
			update: function(event, ui) {
				renumber_slides();
			}
		});

		jQuery('#ckslides .ckslide').each(function(i, slide) {
			slide = jQuery(slide);
			create_tabs_in_slide(slide);
		});
	});
</script>
