<?php
/*
****************************************************************
	Plugin settings page	
****************************************************************
*/
add_action('admin_menu', 'tlap_creat_admin_page', 8, 0);

function tlap_creat_admin_page(){	
	global $admin_page_hooks;	
	if (isset($admin_page_hooks['wp-booster'])  ) {
		return;
	}
	
add_menu_page(
        esc_html__('WP Booster', 'true-lazy-analytics'),
        esc_html_x('WP Booster', 'Menu item', 'true-lazy-analytics'),
		'manage_options',
		'wp-booster',
		'tlap_options_page_output',
		'dashicons-backup',
		92.3 
            );
}	

add_action('admin_head', function(){
  	echo '<style>
    .toplevel_page_wp-booster li.wp-first-item {
    display: none;}
  </style>';
});

add_action('admin_menu', function(){
	$submenu = add_submenu_page(
	'wp-booster',
	'True Lazy Analytics',
	esc_html__('💹 True Lazy Analytics', 'true-lazy-analytics'),
	'manage_options',
	'true-lazy-analytics',
	'tlap_options_page_output'
	);
		//Admin print js&css		
		add_action( 'admin_print_styles-' . $submenu, 'tlap_admin_custom_css' );
}, 99 );

/* enqueue plugin Admin css & js */
function tlap_admin_custom_js (){
	wp_enqueue_script( TLAP_SLUG .'-js', TLAP_FOLDER .'/admin-script.js', array(), false, true );
}
function tlap_admin_custom_css (){
	wp_enqueue_style( TLAP_SLUG .'-css', TLAP_FOLDER .'/admin-style.css', false );
}
/* Redirect after activation on Setting Page */
add_action( 'activated_plugin', function ( $plugin ) {
    if( $plugin == plugin_basename( TLAP_FILE ) ) {
        exit( wp_redirect( admin_url( 'admin.php?page=true-lazy-analytics' ) ) );
    }
} );

function tlap_options_page_output(){
	$second_tab = ( isset( $_GET['action'] ) && 'second-tab' == $_GET['action'] ) ? true : false;
    $third_tab = ( isset( $_GET['action'] ) && 'third-tab' == $_GET['action'] ) ? true : false;
	$fourth_tab = ( isset( $_GET['action'] ) && 'fourth-tab' == $_GET['action'] ) ? true : false;
	$fifth_tab = ( isset( $_GET['action'] ) && 'fifth-tab' == $_GET['action'] ) ? true : false;
	?>
<style>
.tlap-field-premium-icon::after {
	display: inline-block;
	position: relative;
	content: 'PRO';
	background: #ff5722;
	border-radius: 4px;
	color: #fff;
	font-size: 10px;
	line-height: 1;
	font-style: normal;
	padding: 4px 6px;
	margin-left: 4px;
	vertical-align: top;
	top: -10px;
	left: -20px;
	z-index: 11;
}
.tlap-field-soon-icon::after {
    display: inline-block;
    position: relative;
    content: "<?php echo __('SOON', 'true-lazy-analytics'); ?>";
    background: #00bb06;
    border-radius: 4px;
    color: #fff;
    font-size: 10px;
    line-height: 1;
    font-style: normal;
    padding: 4px 6px;
    margin-left: 4px;
    vertical-align: top;
    top: -10px;
    left: -20px;
    z-index: 11;
}
</style>
<script>
function notAvailableMsg(node) {
    return confirm("<?php echo __('This will be available in future versions.', 'true-lazy-analytics'); ?>");
}
</script>
<div class="wrap">    
      <h1  style="display:inline;">True Lazy Analytics <small>v<?php echo TLAP_VERSION; ?></small></h1>  
   		<h2 class="nav-tab-wrapper">
			<a href="<?php echo admin_url( 'admin.php?page='.TLAP_SLUG ); ?>" class="nav-tab<?php if ( !isset( $_GET['action'] ) || isset( $_GET['action'] ) && 'second-tab' != $_GET['action']  && 'third-tab' != $_GET['action'] && 'fourth-tab' != $_GET['action'] && 'fifth-tab' != $_GET['action']) echo ' nav-tab-active'; ?>"><span class="dashicons dashicons-admin-generic"></span><?php echo __('Main Settings', 'true-lazy-analytics'); ?></a>
			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'second-tab' ), admin_url( 'admin.php?page='.TLAP_SLUG ) ) ); ?>" class="nav-tab<?php if ( $second_tab ) echo ' nav-tab-active'; ?>"><span class="dashicons dashicons-chart-pie"></span><?php echo __('Counters', 'true-lazy-analytics'); ?></a> 
			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'third-tab' ), admin_url( 'admin.php?page='.TLAP_SLUG ) ) ); ?>" class="nav-tab<?php if ( $third_tab ) echo ' nav-tab-active'; ?>"><span class="dashicons dashicons-chart-bar"></span><?php echo __('Yandex Metrica', 'true-lazy-analytics'); ?></a>       			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'fourth-tab' ), admin_url( 'admin.php?page='.TLAP_SLUG ) ) ); ?>" class="nav-tab<?php if ( $fourth_tab ) echo ' nav-tab-active'; ?>"><span class="dashicons dashicons-performance"></span><?php echo __('Speed Up Your Website', 'true-lazy-analytics'); ?></a> 
			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'fifth-tab' ), admin_url( 'admin.php?page='.TLAP_SLUG ) ) ); ?>" class="nav-tab<?php if ( $fifth_tab ) echo ' nav-tab-active'; ?>"><span class="dashicons dashicons-yes-alt"></span><?php echo __('License', 'true-lazy-analytics'); ?></a> 
			</h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					
					<div class="postbox">						

						<div class="inside">
		       	 <form method="post" action="options.php"><?php //   settings_fields( 'wpco_general' );
				 if($fifth_tab) {
					settings_fields( 'tlap_add_analytics_option_license' );
					do_settings_sections( 'tlap_page_5' );
					submit_button();

				}	elseif($fourth_tab) {
					settings_fields( 'tlap_add_analytics_option_speedup' );
					do_settings_sections( 'tlap_page_4' );
					submit_button();


				}	elseif($third_tab) {
					settings_fields( 'tlap_add_analytics_option_metrica' );
					do_settings_sections( 'tlap_page_3' );
					submit_button();


				} elseif($second_tab) {					 					 
					settings_fields( 'tlap_add_analytics_option_counters' );
					do_settings_sections( 'tlap_page_2' );
					submit_button();

				} else {
					settings_fields( 'tlap_add_analytics_option_main' );
					do_settings_sections( 'tlap_page' );
					submit_button(); 
				} ?>
			</form>
							</div>
						<!-- .inside -->
					</div>
					<!-- .postbox -->
				</div>
				<!-- .meta-box-sortables .ui-sortable -->
			</div>
			<!-- post-body-content -->
			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<style>
						.bottom-text {
							position: absolute;bottom: 8px;right: 5px;left: 5px;margin: 0 auto;padding: 5px;color: white;font-size: 1rem;font-weight: 600;background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAADGCAYAAAAT+OqFAAAAdklEQVQoz42QQQ7AIAgEF/T/D+kbq/RWAlnQyyazA4aoAB4FsBSA/bFjuF1EOL7VbrIrBuusmrt4ZZORfb6ehbWdnRHEIiITaEUKa5EJqUakRSaEYBJSCY2dEstQY7AuxahwXFrvZmWl2rh4JZ07z9dLtesfNj5q0FU3A5ObbwAAAABJRU5ErkJggg==);
							}
							.bottom-text:hover {
								background: black;
							}
						.clickable-background {
							position: absolute; bottom: 0px; right: 0px; left: 0px; margin: 0 auto; padding: 0px; height: 100%;
						}
					</style>
					<div class="postbox">						
						<iframe src="https://wp-booster.com/landing/order-boost-site.html" style="width: 100%; height: 433px;" scrolling="no" frameborder="0"></iframe>
					</div>
					<div class="postbox">
						<a href="https://bit.ly/3mZ0rLu" target="_blank">
						<iframe src="https://wp-booster.com/landing/helper-lite.html" style="width: 100%;height: 200px;" scrolling="no" frameborder="0"></iframe>
						<div class="clickable-background"></div>
						<div class="bottom-text"><?php _e( 'Improve Speed with Lazy-Loaded Images', 'true-lazy-analytics' ); ?></div>
							</a>							
						</div>						
					<div class="postbox">
						<a href="https://bit.ly/3vyH92i" target="_blank">
						<img width="258" height="129" src="<?php echo TLAP_FOLDER . 'img/google-pagespeed-insights.jpg'; ?>">
						<div class="bottom-text"><?php _e( 'Improve Speed with Lazy-Loaded Youtube Videos', 'true-lazy-analytics' ); ?></div>
							</a>							
						</div>				
					<div class="postbox">
					<h4><?php _e( 'About plugin', 'true-lazy-analytics' ); ?></h4>
						<div class="inside">
			<a href="https://wordpress.org/plugins/true-lazy-analytics/#faq" target="_blank"><?php _e( 'FAQ', 'true-lazy-analytics' ); ?></a>
			<br />
			<a href="https://wordpress.org/support/plugin/true-lazy-analytics/" target="_blank"><?php _e( 'Community Support', 'true-lazy-analytics' ); ?></a>
			<br />
			<a href="https://wordpress.org/support/plugin/true-lazy-analytics/reviews/#new-post" target="_blank"><?php _e( 'Review this plugin', 'true-lazy-analytics' ); ?></a>
			<br />
			<?php echo " <span class='rating-stars'><a href='//wordpress.org/support/plugin/true-lazy-analytics/reviews/?rate=1#new-post' target='_blank' data-rating='1' title='" . __('Poor', 'true-lazy-analytics') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><a href='//wordpress.org/support/plugin/true-lazy-analytics/reviews/?rate=2#new-post' target='_blank' data-rating='2' title='" . __('Works', 'true-lazy-analytics') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><a href='//wordpress.org/support/plugin/true-lazy-analytics/reviews/?rate=3#new-post' target='_blank' data-rating='3' title='" . __('Good', 'true-lazy-analytics') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><a href='//wordpress.org/support/plugin/true-lazy-analytics/reviews/?rate=4#new-post' target='_blank' data-rating='4' title='" . __('Great', 'true-lazy-analytics') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><a href='//wordpress.org/support/plugin/true-lazy-analytics/reviews/?rate=5#new-post' target='_blank' data-rating='5' title='" . __('Fantastic!', 'true-lazy-analytics') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><span>"; ?>			
				</div>
						<!-- .inside -->
					</div>
					<!-- .postbox -->
				</div>
				<!-- .meta-box-sortables -->
			</div>
			<!-- #postbox-container-1 .postbox-container -->
		</div>
		<!-- #post-body .metabox-holder .columns-2 -->
		<br class="clear">
	</div>
	<!-- #poststuff -->
</div> <!-- .wrap -->
	<?php
}


add_action('admin_init', 'tlap_plugin_settings');
function tlap_plugin_settings(){
	
	/* Main settings */	
		register_setting( 
		'tlap_add_analytics_option_main', // Option group
		'tlap_add_analytics_option_main', // Option name
		'tlap_sanitize_callback' // Sanitize
	);
	
	add_settings_section(
		'setting_section_id', // ID
		esc_html__('Main Settings', TLAP_SLUG), // Title
		'', // Callback
		'tlap_page' // Page
	);
	
	add_settings_field(
		'exclude_pages',
		esc_html__('Excluded pages', TLAP_SLUG),
		'tlap_fill_exclude_pages',
		'tlap_page', // Page
		'setting_section_id' // ID
	);
	
	add_settings_field(
		'timer_delay',
		esc_html__('Timer delay', TLAP_SLUG),
		'tlap_fill_timer_delay',
		'tlap_page', // Page
		'setting_section_id' // ID
	);
	
	add_settings_field(
		'lsc_compatibility',
		esc_html__('Compatibility with LiteSpeed Cache plugin', TLAP_SLUG),
		'tlap_fill_lsc_compatibility',
		'tlap_page', // Page
		'setting_section_id' // ID
	);	
		
	/* Counters */
	register_setting( 
		'tlap_add_analytics_option_counters', // Option group
		'tlap_add_analytics_option_counters', // Option name
		'tlap_sanitize_callback' // Sanitize
	);
	
	add_settings_section(
		'setting_section_id', // ID
		esc_html__('Counters', TLAP_SLUG), // Title
		'', // Callback
		'tlap_page_2' // Page
	);
	
	add_settings_field(
		'analytics_id',
		esc_html__('Google Analytics', TLAP_SLUG),
		'tlap_fill_analytics_id',
		'tlap_page_2', // Page
		'setting_section_id' // ID
	);
	add_settings_field(
		'fbpixel_id',
		esc_html__('Facebook Pixel', TLAP_SLUG),
		'tlap_fill_fbpixel_id',
		'tlap_page_2', // Page
		'setting_section_id' // ID
	);
	add_settings_field(
		'hotjar_id',
		esc_html__('Hotjar', TLAP_SLUG),
		'tlap_fill_hotjar_id',
		'tlap_page_2', // Page
		'setting_section_id' // ID
	);
	add_settings_field(
		'liru_en',
		esc_html__('Liveinternet', TLAP_SLUG),
		'tlap_fill_liru_enable',
		'tlap_page_2', // Page
		'setting_section_id' // ID
	);
	
			/* Metrica */
	register_setting( 
		'tlap_add_analytics_option_metrica', // Option group
		'tlap_add_analytics_option_metrica', // Option name
		'tlap_sanitize_callback' // Sanitize
	);
	
	add_settings_section(
		'setting_section_id', // ID
		esc_html__('Yandex Metrica', TLAP_SLUG), // Title
		'', // Callback
		'tlap_page_3' // Page
	);
	
	add_settings_field(
		'yametrika_id',
		esc_html__('ID Yandex Metrica', TLAP_SLUG),
		'tlap_fill_yametrika_id',
		'tlap_page_3', // Page
		'setting_section_id' // ID
	);
	
	add_settings_field(
		'yametrika_webvisor',
		esc_html__('Yandex Metrica WebVisor', TLAP_SLUG),
		'tlap_fill_yametrika_webvisor',
		'tlap_page_3', // Page
		'setting_section_id' // ID
	);
	
	add_settings_field(
		'yametrika_cdn',
		esc_html__('Loading code', TLAP_SLUG),
		'tlap_fill_yametrika_cdn',
		'tlap_page_3', // Page
		'setting_section_id' // ID
	);
	
			/* Speed Up */
	register_setting( 
		'tlap_add_analytics_option_speedup', // Option group
		'tlap_add_analytics_option_speedup', // Option name
		'tlap_sanitize_callback' // Sanitize
	);
	
	add_settings_section(
		'setting_section_id', // ID
		esc_html__('', TLAP_SLUG), // Title
		'', // Callback
		'tlap_page_4' // Page
	);
	
	add_settings_field(
		'speedup_id',
		esc_html__('', TLAP_SLUG),
		'tlap_fill_speedup_id',
		'tlap_page_4', // Page
		'setting_section_id' // ID
	);
	
			/* License */
	register_setting( 
		'tlap_add_analytics_option_license', // Option group
		'tlap_add_analytics_option_license', // Option name
		'tlap_sanitize_callback' // Sanitize
	);
	
	add_settings_section(
		'setting_section_id', // ID
		esc_html__('License', TLAP_SLUG), // Title
		'', // Callback
		'tlap_page_5' // Page
	);
	add_settings_field(
		'license',
		esc_html__('API Key', TLAP_SLUG),
		'tlap_fill_license',
		'tlap_page_5', // Page
		'setting_section_id' // ID
	);
}


/*
****************************************************************
	Main Settings	
****************************************************************
*/
## fill option exclude page
function tlap_fill_exclude_pages(){
	$val = get_option('tlap_add_analytics_option_main') ? get_option('tlap_add_analytics_option_main') : null;
	$val = ( isset( $val['tlap_excludepage'] ) ) ? $val['tlap_excludepage'] : null;
	?>
<span class="tlap-field-premium-icon"><input size="80" type="text" name="tlap_add_analytics_option_main[tlap_excludepage]" value="<?php echo esc_attr( $val ) ?>" placeholder="<?php echo __('Еnter the Page IDs (separated by commas), for example: 345,1145,3778', 'true-lazy-analytics'); ?>" disabled="disabled" />&#9;</span>
<div><?php echo __('Excluded pages - pages on which the code of analytics systems will not be displayed. For example, on pages with the <code>&lt;meta name=&quot;robots&quot; content=&quot;noindex&quot; /&gt;</code> tag', 'true-lazy-analytics'); ?></div>
	<?php
}

## fill option timer delay
function tlap_fill_timer_delay(){
	$val = get_option('tlap_add_analytics_option_main') ? get_option('tlap_add_analytics_option_main') : null;	
	$val = ( isset( $val['tlap_timer_delay'] ) ) ? $val['tlap_timer_delay'] : 5000;
	?>
<span><input size="80" type="text" name="tlap_add_analytics_option_main[tlap_timer_delay]" value="<?php echo esc_attr( $val ) ?>" placeholder="5000" />&#9;</span>
<div><?php echo __('Timer delay (default 5000 microseconds)', 'true-lazy-analytics'); ?></div>
	<?php
}

## fill option lsc compatibility
function tlap_fill_lsc_compatibility(){
	$val = get_option('tlap_add_analytics_option_main') ? get_option('tlap_add_analytics_option_main') : null;
	$val = (isset($val['tlap_lsc_compatibility']) && $val['tlap_lsc_compatibility'] === 1) ? 'checked' : '';
	?>
	<label><input type="checkbox" name="tlap_add_analytics_option_main[tlap_lsc_compatibility]" value="1" <?php echo $val; ?> /></label>	
	<?php
}

/*
****************************************************************
	Counters	
****************************************************************
*/
# fill option analytics id
function tlap_fill_analytics_id(){
	$val = get_option('tlap_add_analytics_option_counters') ? get_option('tlap_add_analytics_option_counters') : null;
	$val = isset($val) ? $val['tlap_analytics_id'] : null;
	?>	
	<input size="20" type="text" name="tlap_add_analytics_option_counters[tlap_analytics_id]" value="<?php echo esc_attr( $val ) ?>" placeholder="<?php echo __('UA-XXX or G-XXX', 'true-lazy-analytics'); ?>" /> <?php echo __('Google Analytics counter ID from analytics.google.com', 'true-lazy-analytics'); ?>
	<div><?php echo __('<a href="https://i.imgur.com/4yVgsV2.png" target="_blank">Where do I get Google Analytics ID?</a>', 'true-lazy-analytics'); ?></div>
	<?php
}

# fill option Facebook Pixel
function tlap_fill_fbpixel_id(){
	$val = get_option('tlap_add_analytics_option_counters') ? get_option('tlap_add_analytics_option_counters') : null;
	$val = isset($val) ? $val['tlap_fbpixel_id'] : null;
	?>	
<input size="20" type="text" name="tlap_add_analytics_option_counters[tlap_fbpixel_id]" value="<?php echo esc_attr( $val ) ?>" placeholder="111111111111" />
	<?php
}

# fill option Hotjar
function tlap_fill_hotjar_id(){
	$val = get_option('tlap_add_analytics_option_counters') ? get_option('tlap_add_analytics_option_counters') : null;
	$val = isset($val) ? $val['tlap_hotjar_id'] : null;
	?>	
<input size="20" type="text" name="tlap_add_analytics_option_counters[tlap_hotjar_id]" value="<?php echo esc_attr( $val ) ?>" placeholder="1234567"  />
	<?php
}

# fill option liru enable
function tlap_fill_liru_enable(){
	$val = get_option('tlap_add_analytics_option_counters') ? get_option('tlap_add_analytics_option_counters') : null;	
	$val = (isset($val['checkbox_liru']) && $val['checkbox_liru'] === 1) ? 'checked' : '';
	?>
	<label><input type="checkbox" name="tlap_add_analytics_option_counters[checkbox_liru]" value="1" <?php echo $val; ?> /> <?php echo __('Enable Liveinternet counter', 'true-lazy-analytics'); ?> </label>
	<div><?php echo __('Attention! The counter will be added to the page automatically, but will be hidden using the "display:none" property. It will not affect its performance.', 'true-lazy-analytics'); ?></div>
	<div><?php echo __('Your site must be registered with the service www.liveinternet.ru.', 'true-lazy-analytics'); ?>
	<?php $link = preg_replace('#^https?://#', '', get_home_url( null, '', '' )); echo sprintf( __( 'Check your stats <a target="_blank" href="https://www.liveinternet.ru/stat/%1$s">https://www.liveinternet.ru/stat/%1$s/</a>.', 'true-lazy-analytics' ), $link ); ?></div>
	<?php
}
/*
****************************************************************
	Metrica	
****************************************************************
*/
# fill option yametrika id
function tlap_fill_yametrika_id(){
	$val = get_option('tlap_add_analytics_option_metrica') ? get_option('tlap_add_analytics_option_metrica') : null;
	$val = isset($val) ? $val['tlap_yametrika_id'] : null;
	?>	
	<input size="20" type="text" name="tlap_add_analytics_option_metrica[tlap_yametrika_id]" value="<?php echo esc_attr( $val ) ?>" placeholder="12345678" /> <?php echo __('Yandex Metrica counter ID from metrika.yandex.ru', 'true-lazy-analytics'); ?>
	<div></div>
	<div><?php echo __('<a href="https://i.imgur.com/ltomthu.jpg" target="_blank">Where do I get Yandex Metrica ID?</a>', 'true-lazy-analytics'); ?></div>

	<?php
}

# fill option yametrika webvisor
function tlap_fill_yametrika_webvisor(){
	$val = get_option('tlap_add_analytics_option_metrica') ? get_option('tlap_add_analytics_option_metrica') : null;
	$val = (isset($val['tlap_yametrika_webvisor']) && $val['tlap_yametrika_webvisor'] === 1) ? 'checked' : '';
	?>	
	<label><input type="checkbox" name="tlap_add_analytics_option_metrica[tlap_yametrika_webvisor]" value="1" <?php echo $val; ?> /> <?php echo __('Enable Yandex Metrica WebVisor', 'true-lazy-analytics'); ?> </label>	
	<?php
}


function tlap_fill_yametrika_cdn( ) {
    $val = get_option('tlap_add_analytics_option_metrica') ? get_option('tlap_add_analytics_option_metrica') : 0;
	$val = ( isset( $val['tlap_yametrika_cdn'] ) ) ? $val['tlap_yametrika_cdn'] : 0;	
    ?>
    <span class="tlap-field-soon-icon" onclick="return notAvailableMsg(this);">
		<input type="radio" name="tlap_add_analytics_option_metrica[tlap_yametrika_cdn]" value="0" <?php checked( $val, 0 ); ?> checked><?php echo __( 'using CDN', 'true-lazy-analytics' ); ?>&nbsp;&nbsp;
		<input type="radio" name="tlap_add_analytics_option_metrica[tlap_yametrika_cdn]" value="1" <?php checked( $val, 1 ); ?> disabled><?php echo __( 'direct', 'true-lazy-analytics' ); ?>
	</span>
<div><?php echo __('Attention! By default, the code is added via cdn (to account for traffic from countries where Yandex services are blocked)', 'true-lazy-analytics'); ?></div>
    <?php
}
/*
****************************************************************
	Speed Up	
****************************************************************
*/                                                                                                
# fill option Speed Up Website
function tlap_fill_speedup_id(){
	?>
<div style="width: 480px">
<p style="text-indent: 10px;text-align: justify;">Этот плагин создан для ускорения Ваших сайтов и увеличения баллов в тесте PageSpeed. Вы можете ускорить сайт используя отложенную загрузку кода счётчиков систем аналитики.</p>
<p style="text-indent: 10px;text-align: justify;">Если у Вас сложный проект, созданный на основе конструкторов страниц (таких как Elementor) или Вы просто хотите провести дополнительную оптимизацию сайта, то можете обратится за помощью к специалистам, кликнув по баннеру ниже.
</p>
</div>
<div>
	<iframe src="https://wp-booster.com/landing/order-boost-site-wide.html" style="width: 480px; height: 400px;" scrolling="no" frameborder="0"></iframe>
</div>
	<?php
}
/*
****************************************************************
	License	
****************************************************************
*/
function tlap_fill_license(){
	$val = get_option('tlap_add_analytics_option_license') ? get_option('tlap_add_analytics_option_license') : null;
	$val = isset($val) ? $val['tlap_license'] : null;
	?>	
	<input size="20" type="text" name="tlap_add_analytics_option_license[tlap_license]" value="<?php echo esc_attr( $val ) ?>" placeholder="xxxxxxxx" /> <?php echo __('Your API key', 'true-lazy-analytics'); ?>
	<?php
}



## sanitize
function tlap_sanitize_callback( $options ){ 
	// очищаем
	foreach( $options as $name => & $val ){

		if( $name == 'tlap_excludepage' )			
		$val = htmlspecialchars($val, ENT_QUOTES);
		
		if( $name == 'tlap_timer_delay' )			
		$val = htmlspecialchars($val, ENT_QUOTES);
		
		if( $name == 'tlap_lsc_compatibility' )			
		$val = intval( $val );
		
		if( $name == 'tlap_analytics_id' )			
		$val = htmlspecialchars($val, ENT_QUOTES);
		
		if( $name == 'tlap_fbpixel_id' )			
		$val = htmlspecialchars($val, ENT_QUOTES);
		
		if( $name == 'tlap_hotjar_id' )			
		$val = htmlspecialchars($val, ENT_QUOTES);
		
		if( $name == 'checkbox_liru' )
		$val = intval($val);
		
		if( $name == 'tlap_yametrika_id' )			
		$val = htmlspecialchars($val, ENT_QUOTES);
		
		if( $name == 'tlap_yametrika_webvisor' )
		$val = intval( $val );
		
		if( $name == 'tlap_yametrika_cdn' )
		$val = intval($val);
		
		if( $name == 'tlap_license' )
		$val = intval($val);
	}
	return $options;
}

## default options
function tlap_plugin_default_values(){
	$defaults = array(
		'tlap_add_analytics_option_main' => array(
			'tlap_excludepage' => '',
			'tlap_timer_delay' => '5000',
			'tlap_lsc_compatibility' => '',
		),
		'tlap_add_analytics_option_counters' => array(
			'tlap_analytics_id' => '',
			'tlap_fbpixel_id' => '',
			'tlap_hotjar_id' => '',
			'checkbox_liru' => '',
		),
		'tlap_add_analytics_option_metrica' => array(
			'tlap_yametrika_id' => '',
			'tlap_yametrika_webvisor' => 0,
			'tlap_yametrika_cdn' => 0,
		),
		'tlap_add_analytics_option_license' => array(
			'tlap_license' => '',
		),
	);
	
	foreach ( $defaults as $section => $fields ) {
		add_option( $section, $fields,'', false );
	}
}
register_activation_hook( TLAP_FILE, 'tlap_plugin_default_values' );
