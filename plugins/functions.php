<?php

/**
 * Understrap functions and definitions
 *
 * @package understrap
 */

// Exit if accessed directly only.
defined('ABSPATH') || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

$understrap_includes = array(
    '/not_watch.php',
    '/enqueue.php',
    '/post-types/post-types.php',
    '/carbon-fields.php',
    '/contact-form-7.php',
    '/breadcrumbs.php',
    '/turn-off-editor.php',
    '/turn-off-post.php',
    '/turn-off-comments.php'
);
add_theme_support('title-tag');
foreach ($understrap_includes as $file) {
    $filepath = locate_template('inc' . $file);
    if (!$filepath) {
        trigger_error(sprintf('Error locating /inc%s for inclusionadd_filter( \'woocommerce_calculated_total\', \'change_calculated_total\', 10, 2 );',
            $file), E_USER_ERROR);
    }
    require_once $filepath;
}


add_action('admin_head', function(){
    wp_enqueue_style("style-admin", get_template_directory_uri()."/styles/admin.css");
});


// START post-types//
flush_rewrite_rules();

// END post-types //

register_nav_menus(array(
    'primary' => __('Main menu'), // Главное меню
    'footer' => __('Footer menu')
));

add_theme_support('post-thumbnails');
the_post_thumbnail('event', 'procedury', 'salons', 'blog');

add_filter('transient_shipping-transient-version', function($value, $name) { return false; }, 10, 2);
add_filter( 'woocommerce_package_rates', 'coupon_free_shipping_customization', 20, 2 );
function coupon_free_shipping_customization( $rates, $package ) {
    $has_free_shipping = false;

   if ((int)WC()->cart->cart_contents_total>7000){
       $has_free_shipping=true;

   }

    foreach( $rates as $rate_key => $rate ){
        if( $has_free_shipping ){

            if( $rate->method_id == 'flat_rate' or $rate->instance_id==15 or $rate->instance_id==19){

                $rates[$rate_key]->label .= '-[free]';
                // Set rate cost
                $rates[$rate_key]->cost = 0;

                // Set taxes rate cost (if enabled)
                $taxes = array();
                foreach ($rates[$rate_key]->taxes as $key => $tax){
                    if( $rates[$rate_key]->taxes[$key] > 0 )
                        $taxes[$key] = 0;
                }
                $rates[$rate_key]->taxes = $taxes;
            }

        }
    }
    return $rates;
}
add_image_size('document-size', 270, 372);
add_shortcode( 'product_slider', 'product_slider_shortcode' );

function product_slider_shortcode( $atts ){
    $staff_title   = carbon_get_the_post_meta('shortcode_slidertitle', $type = null);

    $staff_complex = carbon_get_the_post_meta('shortcode_slidercomplex', $type = null);
    ob_start();
?>
	<section class="wrap-cestrificats">
			<div class="row">
				<div class="col-12 text-center">
					<h2><?= $staff_title?></h2>
				</div>
				<div class="col-12">
					<div class="reviews carousel owl-carousel detail-info__slider">
                        <?php  foreach ($staff_complex as $c_item) { ?>
							<article class="comment">
								<div class="comment-content">
									<div class="comment-content__textt">
                                        <?= $c_item['quote'] ?>
									</div>
									<div class="comment-content__author">
										<span><?= $c_item['name'] ?></span>
									</div>
									<div class="comment-content__extratext">
										<ul>
                                            <?php foreach($c_item['list'] as $item): ?>
											<li><?=$item['quote'] ?></li>
                                            <?php endforeach; ?>

										</ul>
									</div>
								</div>
								<div class="comment-img">
									<img src="<?= wp_get_attachment_image_url($c_item['img'], 'full'); ?>" draggable="false" style="">
								</div>
							</article>
                        <?php } ?>

					</div>

				</div>
			</div>
	</section>
    <?php

    $content = ob_get_contents();


    ob_end_clean();
    return $content;
}
// Add buttons to html editor
add_action('admin_print_footer_scripts', 'eg_quicktags');
function eg_quicktags()
{
    ?>
  <script type="text/javascript" charset="utf-8">
      edButtons[edButtons.length] = new edButton('ed_mark', 'Подсветить текст', '<span>', '</span>', 'p-span');
      edButtons[edButtons.length] = new edButton('ed_mark_metro_br', 'Добавить значок Метро (коричневый)', '<i class="m-i m-brown" aria-hidden="true"> M </i>', '', 'metro-b');
      edButtons[edButtons.length] = new edButton('ed_mark_metro_bl', 'Добавить значок Метро (синий)', '<i class="m-i m-light-blue" aria-hidden="true"> M </i>', '', 'metro-bl');
  </script>
    <?php
}

add_action('template_redirect', 'bt_remove_woocommerce_styles_scripts', 999);
/**
 * Remove Woo Styles and Scripts from non-Woo Pages
 * @link https://gist.github.com/DevinWalker/7621777#gistcomment-1980453
 * @since 1.7.0
 */
function bt_remove_woocommerce_styles_scripts()
{

    // Skip Woo Pages
    if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
        return;
    }
    // Otherwise...
    remove_action('wp_enqueue_scripts', [WC_Frontend_Scripts::class, 'load_scripts']);
    remove_action('wp_print_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
    remove_action('wp_print_footer_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
}

function slug_disable_woocommerce_block_styles()
{
    if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
        return;
    }
    wp_dequeue_style('wc-block-style');

}


add_action('wp_enqueue_scripts', 'slug_disable_woocommerce_block_styles');


add_filter('woocommerce_is_purchasable', 'prefix_wc_is_purchasable', 10, 2);

function prefix_wc_is_purchasable( $is_purchasable, $object ) {
    if ( get_post_status( $object->get_id() ) == "draft"  )
        return true;

    if ( get_post_status( $object->get_id() ) !== "publish"  )
        return false;
    else
        return true;
}


function wpassist_remove_block_library_css()
{
    wp_dequeue_style('wp-block-library');
}

add_action('wp_enqueue_scripts', 'wpassist_remove_block_library_css');

function getImgPreg($matches)
{

    $links = substr($matches[0], 9, -1);
    $arrayLinks = explode(',', $links);
    $html = '';

    foreach ($arrayLinks as $value) {
        parse_str(parse_url($value, PHP_URL_QUERY), $paramsLink);
        $foto .= '<div class="video" data-id="' . $paramsLink[v] . '">
                                                        <div class="video__preview">
                                                            <span class=" lazyload" data-bg="https://i1.ytimg.com/vi/' .
            $paramsLink[v] . '/hqdefault.jpg" ></span>
                                                        </div>
                                                        <div class="video__action">
                                                            <div class="video__button">
                                                                <img src="/wp-content/themes/perfleour/img/svg/ic_play.svg" />
                                                                <span>Смотреть видео</span>
                                                            </div>
                                                        </div>
                                                    </div>';
    }
    $html = '<div class="box-responsiv-video">' . $foto . '</div>';
    return $html;
}

function replaceImgSrc($matches)
{
    return 'data-lazyload="" data-src=';
}

//session start

add_action('init', 'start_session', 1);

function start_session()
{
    if (!session_id()) {
        session_start();
    }
}

//проверить наличие товара в корзине
function woo_in_cart($product_id)
{
    global $woocommerce;
    foreach ($woocommerce->cart->get_cart() as $key => $val) {
        $_product = $val['data'];
        if ($product_id == $_product->id) {
            return true;
        }
    }

    return false;
}

function get_count_cart($product_id)
{
    global $woocommerce;
    foreach ($woocommerce->cart->get_cart() as $key => $val) {
        $_product = $val['data'];
        if ($product_id == $_product->id) {
            return $val['quantity'];
        }
    }


}

// Убираем доставку с корзины
function disable_shipping_calc_on_cart($show_shipping)
{
    if (is_cart()) {
        return false;
    }
    return $show_shipping;
}

// Прием UTM меток (CRM)
add_action('init', 'CRM_SetCookie');
// Прием UTM меток (CRM)
function CRM_SetCookie()
{
    if (!empty($_GET['utm_source']))
        setcookie('utm_source', $_GET['utm_source'], time() + 3600);
    if (!empty($_GET['utm_medium']))
        setcookie('utm_medium', $_GET['utm_medium'], time() + 3600);
    if (!empty($_GET['utm_campaign']))
        setcookie('utm_campaign', $_GET['utm_campaign'], time() + 3600);
    if (!empty($_GET['utm_content']))
        setcookie('utm_content', $_GET['utm_content'], time() + 3600);
    if (!empty($_GET['utm_term']))
        setcookie('utm_term', $_GET['utm_term'], time() + 3600);
}

add_filter('woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99);
// --------------------------------------------------------
// Изменяем или кастомизируем редактор
// --------------------------------------------------------

// Создаем список размеров шрифта
function scanwp_font_size($initArray)
{
    $initArray['fontsize_formats'] =
        "9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 30px";
    return $initArray;
}

add_filter('tiny_mce_before_init', 'scanwp_font_size');
function myplugin_addbuttons()
{
    // Don't bother doing this stuff if the current user lacks permissions
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
        return;

    // Add only in Rich Editor mode
    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_buttons', 'register_myplugin_button');
    }
}

// Настраиваем визуальный редактор
function register_myplugin_button($buttons)
{
    $buttons = array(
        'alignleft',
        'aligncenter',
        'alignright',
        'bold',
        'italic',
        'underline',
        'strikethrough',
        'justifyleft',
        'justifycenter',
        'justifyright',
        'justifyfull',
        'bullist',
        'numlist',
        'outdent',
        'indent',
        'cut',
        'copy',
        'paste',
        'undo',
        'redo',
        'link',
        'unlink',
        'image',
        'cleanup',
        'help',
        'code',
        'hr',
        'removeformat',
        'formatselect',
        // 'fontselect',
        'fontsizeselect',
        // 'styleselect',
        // 'sub',
        // 'sup',
        'forecolor',
        // 'backcolor',
        // 'forecolorpicker',
        // 'backcolorpicker',
        // 'charmap',
        'visualaid',
        // 'anchor',
        // 'newdocument', стирает весь контент
        'blockquote',
        // 'separator',
    );
    return $buttons;
}

// init process for button control
add_action('init', 'myplugin_addbuttons');

// Создаем кастомный выбор цветов
function my_mce4_options($init)
{
    $custom_colours = '
        "fe8f87", "Цвет темы 1",
        "4c4c58", "Цвет темы 2 (Темный)",
    ';

    // build colour grid default+custom colors
    $init['textcolor_map'] = '[' . $custom_colours . ']';

    // change the number of rows in the grid if the number of colors changes
    // 8 swatches per row
    $init['textcolor_rows'] = 1;

    return $init;
}

add_filter('tiny_mce_before_init', 'my_mce4_options');

// --------------------------------------------------------
// END Изменяем или кастомизируем редактор
// --------------------------------------------------------

function getSize()
{
    $data = array();
    $term = get_queried_object();

// To be sure that is a WP_Term Object to avoid errors
    if (is_a($term, 'WP_Term')) :

// Setup your custom query
        $loop = new WP_Query(array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'tax_query' => array(array(
                'taxonomy' => 'product_cat', // The taxonomy name
                'field' => 'term_id', // Type of field ('term_id', 'slug', 'name' or 'term_taxonomy_id')
                'terms' => $term->term_id, // can be an integer, a string or an array
            )),
        ));

        if ($loop->have_posts()) :
            while ($loop->have_posts()) : $loop->the_post();

                $size = carbon_get_post_meta(get_the_ID(), 'volume_text');

                if (!in_array($size, $data) && !empty($size)) {
                    array_push($data, $size);
                }
            endwhile;
            wp_reset_postdata(); // Remember to reset
        endif; endif;
    return $data;
}

/**
 * Remove all possible fields
 **/
// Hook in
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields($fields)
{
    //name
    $fields['billing']['billing_first_name']['label'] = '';
    $fields['billing']['billing_first_name']['placeholder'] = 'Ваше имя';
    $fields['billing']['billing_first_name']['priority'] = 10;

    //last name
    $fields['billing']['billing_last_name']['label'] = '';
    $fields['billing']['billing_last_name']['placeholder'] = 'Ваша фамилия';
    $fields['billing']['billing_last_name']['priority'] = 20;

    //phone
    $fields['billing']['billing_phone']['label'] = '';
    $fields['billing']['billing_phone']['placeholder'] = 'Телефон';
    $fields['billing']['billing_phone']['priority'] = 30;
    $fields['billing']['billing_email']['class'][0] = 'phone-header-before';

    //country
    $fields['billing']['billing_country']['label'] = '';
    $fields['billing']['billing_country']['placeholder'] = 'Страна';
    $fields['billing']['billing_country']['priority'] = 40;

    //city
    $fields['billing']['billing_city']['label'] = '';
    $fields['billing']['billing_city']['placeholder'] = 'Страна';
    $fields['billing']['billing_city']['priority'] = 50;

    $fields['billing']['billing_address_1']['label'] = '';
    $fields['billing']['billing_address_1']['placeholder'] = 'Адрес доставки';
    $fields['billing']['billing_address_1']['priority'] = 110;

    $fields['billing']['billing_postcode']['label'] = '';
    $fields['billing']['billing_postcode']['required'] = true;
    $fields['billing']['billing_postcode']['placeholder'] = 'Почтовый индекс';


    return $fields;
}

add_action('woocommerce_checkout_create_order', 'change_total_on_checking', 20, 1);
function change_total_on_checking($order)
{

    $total = $order->get_total();
    $subtotal = $order->get_subtotal();
    $shiping = $order->get_shipping_total();

    if ($_SESSION['not-summ-delivery']) {
        $order->set_total($total - $shiping);
    }

}

add_action('carbon_fields_register_fields', 'crb_attach_term_meta');
function crb_attach_term_meta()
{
    Container::make('term_meta', __('Print image'))
        ->where('term_taxonomy', '=', 'product_cat')
        ->add_fields(array(
            Field::make('checkbox', 'crb_show_content', 'Использовать для применения')
                ->set_option_value('Да')

        ));
}

function qot_add_editor_styles()
{
    add_editor_style('/styles/admin.css');
}

add_action('after_setup_theme', 'qot_add_editor_styles');

function true_filter_func_wooaddtobusket()
{
    $getCount = wp_kses_data(WC()->cart->get_cart_contents_count());
    echo $getCount;
    die;
}

add_action('wp_ajax_wooaddtobusket', 'true_filter_func_wooaddtobusket');
add_action('wp_ajax_nopriv_wooaddtobusket', 'true_filter_func_wooaddtobusket');


function getBasket()
{
   $check=woo_in_cart($_POST['parent']);
   if($check){
       WC()->cart->add_to_cart( $_POST['id'] );
       exit(json_encode(array(
           'status'=>true,
           'count' => WC()->cart->get_cart_contents_count(),
           'total' => WC()->cart->get_cart_subtotal()
       )));
   }
   else{
       exit(json_encode(array(
           'status'=>false,
           'msg' => '<div class="custom-alert"><div class="custom-alert__title">Внимание</div><div class="custom-alert__text">Для того что бы добавить акционный товар, сначала нужно добавить в корзину основной</div></div>'
       )));
   }


}

add_action('wp_ajax_sales', 'getBasket');
add_action('wp_ajax_nopriv_sales', 'getBasket');

function checktoradio()
{
    // echo '<script type="text/javascript">jQuery("#tax_catschecklist input, #categorychecklist input, .cat-checklist input").each(function(){this.type="radio"});</script>';
    echo '<script type="text/javascript">jQuery("#tax_citychecklist input, #categorychecklist input, .cat-checklist input").each(function(){this.type="radio"});</script>';
}

add_action('admin_footer', 'checktoradio');

add_filter('woocommerce_show_variation_price', '__return_true');

remove_filter('the_content', 'wpautop'); // Отключаем автоформатирование в полном посте
remove_filter('the_excerpt', 'wpautop'); // Отключаем автоформатирование в кратком(анонсе) посте
remove_filter('comment_text', 'wpautop'); // Отключаем автоформатирование в комментариях

add_filter('woocommerce_billing_fields', 'custom_woocommerce_billing_fields');

//custom fields nocall
function custom_woocommerce_billing_fields($fields)
{

    $fields['billing_nocall'] = array(
        'label' => __('not call manager', 'woocommerce'), // Add custom field label
        'placeholder' => false,
        'required' => false, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'checkbox', // add field type
        'class' => array('checkbox-nocall')    // add class name
    );

    return $fields;
}

add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);

function change_existing_currency_symbol($currency_symbol, $currency)
{
    switch ($currency) {
        case 'RUB':
            $currency_symbol = ' руб';
            break;
    }
    return $currency_symbol;
}
function remove($id){
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        if ( $cart_item['product_id'] == $id ) {
            WC()->cart->remove_cart_item( $cart_item_key );
        }
    }
}
//удаление товаров из корзины
add_action('wp_ajax_submitForm', 'my_submitForm');
add_action('wp_ajax_nopriv_submitForm', 'my_submitForm');
function my_submitForm()
{
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $sales = carbon_get_post_meta($cart_item['product_id'],'sales_products_association' );
        if($sales){
            foreach ($sales as $c_item) {
                $id_sales = $c_item['id'];
            }
        }

        if ($cart_item['product_id'] == $_POST["data"]) {

            WC()->cart->remove_cart_item($cart_item_key);
            remove($id_sales);
        }
    }
    exit(json_encode(array(
        'count' => WC()->cart->get_cart_contents_count(),
        'totalPriceCart' => WC()->cart->get_cart_subtotal(),
    )));
}

//form-signup
add_action('wp_ajax_formSignup', 'my_formSignup');
add_action('wp_ajax_nopriv_formSignup', 'my_formSignup');
function my_formSignup()
{

    $queryData = array(
        'fields' => array(
            'TITLE' => 'форма - Подпишись на новости и акции perfleor',
            'NAME' => $_POST['data'][0]['value'],
            'UF_CRM_1607414024830' => $_SERVER['HTTP_REFERER'],
            'ASSIGNED_BY_ID' => 153,
            /*        'UTM_CAMPAIGN' => $utm_campaign,
                    'UTM_CONTENT' => $utm_content,
                    'UTM_MEDIUM' => $utm_medium,
                    'UTM_SOURCE' => $utm_source,
                    'UTM_TERM' => $utm_term,*/
            'PHONE' => array(array('VALUE' => $_POST['data'][1]['value'], 'VALUE_TYPE' => 'HOME')),
            'SOURCE_ID' => 26,
        ),
        'params' => array("REGISTER_SONET_EVENT" => "Y")
    );

    sendContactForm($email, $queryData, true);

    exit(json_encode(array('status' => true,
        'msg' => '<div class="success"><div class="success-title">Спасибо</div><div class="success-desc">За подписку!</div></div>')));


}


//qualityControllUpload

add_action('wp_ajax_qualityControllUpload', 'qualityControllUpload');
add_action('wp_ajax_nopriv_qualityControllUpload', 'qualityControllUpload');

function qualityControllUpload()
{

    $imgGetFromJS = $_POST['data'];
    $ps = str_replace('data:image/jpeg;base64,', '', $imgGetFromJS);
    $ps = str_replace('data:image/png;base64,', '', $ps);
    $ps = str_replace(' ', '+', $ps);
    $img = base64_decode($ps);

    //$image_url = 'adress img';
    $upload_dir = wp_upload_dir();
    $image_data = $img;
    $filename = md5($_POST['fileName']) . '.jpg';

    if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null);

    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => 'Вложение письма формы контроля качества',
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $file);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);

    wp_update_attachment_metadata($attach_id, $attach_data);

    exit(json_encode([
        'full' => wp_get_attachment_image_url($attach_id, 'full'),
        'url' => wp_get_attachment_image_url($attach_id, 'thumbnail'),
        'attach_id' => $attach_id,
    ]));
}

//qualityControllRemoveAttach

add_action('wp_ajax_qualityControllRemoveAttach', 'qualityControllRemoveAttach');
add_action('wp_ajax_nopriv_qualityControllRemoveAttach', 'qualityControllRemoveAttach');

function qualityControllRemoveAttach()
{

    $attachment_id = (int)$_POST['id'];
    wp_delete_attachment($attachment_id, true);

    return ['status' => true];

}

//qualityControllSend

add_action('wp_ajax_qualityControllSend', 'qualityControllSend');
add_action('wp_ajax_nopriv_qualityControllSend', 'qualityControllSend');

function qualityControllSend()
{

    //$admin_email ='mihey_b@mail.ru,leads@perfleor.ru,s.nikulina@perfleor.ru,a.ilushkina@perfleor.ru';
    $admin_email = ADMIN__EMAIL;
    $form_subject = 'Perfleor.ru - Контроль качества';
    $project_name = 'perfleor.ru';
    $from = 'no-reply@perfleor.ru';

    parse_str($_POST['data'], $dataForm);

    if (empty($dataForm['client-name']) or empty($dataForm['client-phone']) or
        empty($dataForm['client-email'])) {  // если пустой пароль при добавлении
        exit(json_encode(array('status' => false, 'msg' => 'Все поля обязательны для заполнения')));
    }

    $c = true;

    $lang = array(
        'ru' => array(
            'client-name' => 'Имя',
            'client-phone' => 'Телефон',
            'client-email' => 'Email',
            'message' => 'Текст',
        )
    );

    foreach ($dataForm as $key => $value) {
        if ($value != "" && $key != "project_name" && $key != "admin_email" && $key != "form_subject") {
            $message .= "
            " . (($c = !$c) ? '<tr>' : '<tr style="background-color: #f8f8f8;">') . "
                <td style='padding: 10px; border: #e9e9e9 1px solid;'><b>" . $lang['ru'][$key] . "</b></td>
                <td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
            </tr>
            ";
        }
    }

    if (!empty($_POST['image'])) {
        $image = array();
        foreach ($_POST['image'] as $item) {
            $image[] = '<a href="' . $item[1] . '" ><img src="' . $item[0] . '" ></a>';
        }
        $imageHtml = '<div> Изображения: <br> ' . implode(" ", $image) . '</div>';
    }


    $message = "<table style='width: 100%;'>$message</table> <br> $imageHtml";

    function adopt($text)
    {
        return '=?UTF-8?B?' . Base64_encode($text) . '?=';
    }

    $headers = "MIME-Version: 1.0" . PHP_EOL .
        "Content-Type: text/html; charset=utf-8" . PHP_EOL .
        'From: ' . adopt($from) . ' <' . $admin_email . '>' . PHP_EOL .
        'Reply-To: ' . $admin_email . '' . PHP_EOL;


    if (mail($admin_email, adopt($form_subject), $message, $headers)) {
        exit(json_encode(array('status' => true, 'msg' => 'Успешно')));
    }

}

//Обновление оформления заказа
add_action('wp_ajax_woo_checkout_update', 'my_woo_checkout_update');
add_action('wp_ajax_nopriv_woo_checkout_update', 'my_woo_checkout_update');

function my_woo_checkout_update()
{

    ob_start();

    foreach (WC()->cart->get_coupons() as $code => $coupon) {
        echo '<div class="cart-discount coupon-' . $code . '">';
        echo '<span class="coupone-title" >Купон ' . $code . '</span>';
        echo '<span class="total-price-aside">' . wc_cart_totals_coupon_html($coupon) . '</span>';
        echo '</div>';
    }

    $coupone = ob_get_contents();

    ob_end_clean();

    if ($_POST['delivery'] == 1) {

        $total = (WC()->cart->get_total(false)) - (WC()->cart->get_shipping_total());

        WC()->cart->set_total($total);
        $_SESSION['not-summ-delivery'] = 1;
    } else {
        $_SESSION['not-summ-delivery'] = 0;
    }


    $data = array(
        'costProduct' => WC()->cart->get_cart_subtotal(),
        'coupon' => $coupone,
        'costDelivery' => WC()->cart->get_cart_shipping_total(),
        'total' => WC()->cart->get_totals(),
        'sale' => ( (WC()->cart->get_total(false)) - (WC()->cart->get_shipping_total()) ),

    );


    exit(json_encode($data));
}


//Обновление товаров из корзины
add_action('wp_ajax_reload', 'my_reload');
add_action('wp_ajax_nopriv_reload', 'my_reload');
function my_reload()
{

    $product_id = $_POST['product_id'];
    $quantity   = $_POST['quantity'];

    if(isset($product_id) && !empty($product_id)){
        WC()->cart->add_to_cart( $product_id, $quantity );
    }

    ob_start();

    if (!empty(WC()->cart->get_cart())): ?>

      <div class="mini-cart-overlay">
        <div class="table_ table--cart">
            <?php

            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

                $item_name = $cart_item['data']->get_title();
                $quantity = $cart_item['quantity'];
                $status=$cart_item['data']->get_status();
                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                //  $cart_item['product_id'];
                ?>
                <div class="table__tr">
                <div class="table__td">
                    <a class="good-image" href="/products/<?= $cart_item['data']->slug ?>">
                                <span class="bgimage"
                                    style="background-image: url(<?= $image_url =
                                        wp_get_attachment_image_url($cart_item['data']->get_image_id(),
                                            'full') ?>)"></span>
                    </a>
                </div>
                <div class="table__td">
                    <a class="good-title" href="/products/<?= $cart_item['data']->slug ?>"><?= $item_name ?></a>
                    <span class="good-subtotal" ><?=WC()->cart->get_product_subtotal( $_product,$cart_item['quantity'] )?></span>
                </div>
                <div class="table__td">

                            <span class="quantity">
                                <span class="quantity__dec " data-id="<?= $cart_item['product_id'] ?>">-</span>
                                <span class="quantity__total "><?= $quantity ?></span>
                                <span class="quantity__inc " data-id="<?= $cart_item['product_id'] ?>">+</span>
                            </span>

                </div>
                <div class="table__td">
                    <span class="action__remove" data-id="<?= $cart_item['product_id'] ?>">x</span>
                </div>
                </div>
                <?php
            } ?>

        </div>
      </div>

      <div class="cart-footer" >
        <div class="cart-footer__total">
            <span>Товаров на сумму</span> <span class="mini-cart-total" ><?=WC()->cart->get_cart_subtotal()?></span>
        </div>
        <div class="cart-footer__info">При заказе от 7000р доставка по России бесплатно!</div>
      </div>

    <?php
    endif;
    $content = ob_get_contents();
    ob_end_clean();

    exit(json_encode(array(
        'content' => $content,
        'count' => wp_kses_data(WC()->cart->get_cart_contents_count())
    )));
}
function get_parent_sales($id){
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1
    );
    $loop = new WP_Query( $args );
    if ( $loop->have_posts() ) {
        while ( $loop->have_posts() ) : $loop->the_post();
            $sales = carbon_get_post_meta(get_the_id(),'sales_products_association' );
            if($sales){
                foreach ($sales as $i=>$c_item) {

                     if($c_item['id']==$id){
                       return get_the_id() ;
                     }

                }
            }
        endwhile;
    }
    wp_reset_postdata();
}
function crunchify_stop_loading_wp_embed_and_jquery() {
    if (!is_admin()) {
        wp_deregister_script('wp-embed');
    }
}
add_action('init', 'crunchify_stop_loading_wp_embed_and_jquery');
//увеличение колличества товара в корзине
add_action('wp_ajax_cart_item_inc', 'my_cart_item_inc');
add_action('wp_ajax_nopriv_cart_item_inc', 'my_cart_item_inc');
function my_cart_item_inc()
{

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if ($cart_item['product_id'] == $_POST["data"]) {

            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            if ($_product->status=="draft"){
              if (get_count_cart(get_parent_sales($_product->id)) <= $cart_item['quantity'])
              {
                  exit(json_encode(array(
                      'status' => false,

                  )));
              }
            }
            WC()->cart->set_quantity($cart_item_key, $cart_item['quantity'] + 1);
            $totalPriceProduct = WC()->cart->get_product_subtotal($_product, $cart_item['quantity'] + 1);
            break;
        }
    }

    exit(json_encode(array(
        'status'=>true,
        'count' => WC()->cart->get_cart_contents_count(),
        'totalPriceProduct' => $totalPriceProduct,
        'totalPriceCart' => WC()->cart->get_cart_subtotal(),
    )));
}

//уменьшить колличество товара в корзине
add_action('wp_ajax_cart_item_dec', 'my_cart_item_dec');
add_action('wp_ajax_nopriv_cart_item_dec', 'my_cart_item_dec');
function my_cart_item_dec()
{

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if ($cart_item['product_id'] == $_POST["data"]) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

            WC()->cart->set_quantity($cart_item_key, $cart_item['quantity'] - 1);
            $totalPriceProduct = WC()->cart->get_product_subtotal($_product, $cart_item['quantity'] - 1);
            break;
        }
    }
    exit(json_encode(array(
        'count' => WC()->cart->get_cart_contents_count(),
        'totalPriceProduct' => $totalPriceProduct,
        'totalPriceCart' => WC()->cart->get_cart_subtotal(),
    )));
}


add_filter('woocommerce_thankyou_order_received_text', 'woo_change_order_received_text', 10, 2);
function woo_change_order_received_text($str, $order)
{

    $data = $order->get_data();

    $nocall_checkbox = get_post_meta($data['id'], '_billing_nocall', true);
    $payment_method = $data['payment_method'];

    //оплата через менеджера
    if ($payment_method == 'cod') {
        $new_str = 'Заказ №' . $data['id'] . ' на сумму ' . $data['total'] .
            'р получен на сайте perfleor.ru. В ближайшее время мы свяжемся с Вами для подтверждения заказа.';
    }

    //оплата online
    if ($payment_method == 'cpgwwc') {

        if ($nocall_checkbox == '1') {
            $new_str = 'Заказ №' . $data['id'] . ' на сумму ' . $data['total'] .
                'р оплачен на сайте perfleor.ru. Заказ будет передан в доставку в течение одного рабочего дня';
        } else {
            $new_str = 'Заказ №' . $data['id'] . ' на сумму ' . $data['total'] .
                'р оплачен на сайте perfleor.ru. В ближайшее время мы свяжемся с Вами для уточнения деталей доставки.';
        }

    }

    return $new_str;
}

add_action('woocommerce_order_status_processing', 'my_custom_tracking');
function my_custom_tracking($order_id)
{
    // Подключаемся к серверу CRM
    define('CRM_HOST', 'perfleor.bitrix24.ru'); // Ваш домен CRM системы
    define('CRM_PORT', '443'); // Порт сервера CRM. Установлен по умолчанию
    define('CRM_PATH', '/crm/configs/import/lead.php'); // Путь к компоненту lead.rest


    // Авторизуемся в CRM под необходимым пользователем:
    // 1. Указываем логин пользователя Вашей CRM по управлению лидами
    define('CRM_LOGIN', 'project4@crmprogress.ru');
    // 2. Указываем пароль пользователя Вашей CRM по управлению лидами
    define('CRM_PASSWORD', 'project4crmprogress');

    // Получаем информации по заказу
    $order = new WC_Order($order_id);
    $order_data = $order->get_data();

    $d_s = print_r($order_data, true);
    $fd = fopen("hello.txt", 'w') or die("не удалось создать файл");

    fwrite($fd, $d_s);
    fclose($fd);

    // Получаем базовую информация по заказу
    $order_id = $order_data['id'];
    $order_currency = $order_data['currency'];
    $order_payment_method_title = $order_data['payment_method_title'];
    $order_payment_method = $order_data['payment_method'];
    $order_shipping_totale = $order_data['shipping_total'];
    $order_discount = $order_data['discount_total'];
    $order_total = $order_data['total'];

    $coupon = '';
    foreach ($order_data['coupon_lines'] as $coup) {
        $coupon .= $coup->get_code();
    }

    $order_base_info = "Общая информация по заказу
    ID заказа: $order_id
    Валюта заказа: $order_currency
    Метода оплаты: $order_payment_method_title
    Стоимость доставки: $order_shipping_totale
    Промокод: $coupon
    Итого с доставкой: $order_total";


    // Получаем информация по клиенту
    $order_customer_id = $order_data['customer_id'];
    $order_customer_ip_address = $order_data['customer_ip_address'];
    $order_billing_first_name = $order_data['billing']['first_name'];
    $order_billing_last_name = $order_data['billing']['last_name'];
    $order_billing_email = $order_data['billing']['email'];
    $order_billing_phone = $order_data['billing']['phone'];
    $order_billing_city = $order_data['billing']['city'];
    $order_billing_postcode = $order_data['billing']['postcode'];
    $order_billing_address = $order_data['billing']['address_1'];
    $comments = $order_data['customer_note'];

    $order_client_info = "<hr><strong>Информация по клиенту</strong><br>
    ID клиента = $order_customer_id<br>
    IP адрес клиента: $order_customer_ip_address<br>
    Имя клиента: $order_billing_first_name<br>
    Фамилия клиента: $order_billing_last_name<br>
    Email клиента: $order_billing_email<br>
    Адрес клиента: $order_billing_address<br>
    Город клиента: $order_billing_city<br>
    Индекс: $order_billing_postcode<br>
    Телефон клиента: $order_billing_phone<br>";

    // Получаем информацию по доставке
    $order_shipping_address_1 = $order_data['shipping']['address_1'];
    $order_shipping_address_2 = $order_data['shipping']['address_2'];
    $order_shipping_city = $order_data['shipping']['city'];
    $order_shipping_state = $order_data['shipping']['state'];
    $order_shipping_postcode = $order_data['shipping']['postcode'];
    $order_shipping_country = $order_data['shipping']['country'];

    $order_shipping_info = "<hr><strong>Информация по доставке</strong><br>
    Страна доставки: $order_shipping_state<br>
    Город доставки: $order_shipping_city<br>
    Индекс: $order_shipping_postcode<br>
    Адрес доставки 1: $order_shipping_address_1<br>
    Адрес доставки 2: $order_shipping_address_2<br>";

    // Получаем информации по товару
    $order->get_total();
    $line_items = $order->get_items();
    $price_all_products = 0;
    foreach ($line_items as $item) {
        $product = $order->get_product_from_item($item);
        $sku = $product->get_sku(); // артикул товара
        $id = $product->get_id(); // id товара
        $price = $product->get_price(); // цена товара
        $name = $product->get_name(); // название товара
        $description = $product->get_description(); // описание товара
        $stock_quantity = $product->get_stock_quantity(); // кол-во товара на складе
        $qty = $item['qty']; // количество товара, которое заказали
        $total = $order->get_line_total($item, true,
            true); // стоимость всех товаров, которые заказали, но без учета доставки
        $price_all_products += ((float)$price) * ((int)$qty);


        $product_info[] = "

  Название товара: $name
  Цена: $price
  Количество: $qty";
    }

    $product_base_info = implode(' ', $product_info);

    $shipping_method_name = '';
    $shipping_method_id = '';
    $shipping_method_price = 0;
    foreach ($order_data['shipping_lines'] as $ship) {
        $shipping_method_name .= $ship->get_name();
        $shipping_method_price = (float)$ship->get_total();
        $shipping_method_id = $ship->get_method_id();
    }

    $subject = "Заказ с сайта №".$order_id ; //  /inc/not_watch.php

    $delivery_paid = 0;
    $need_callback = get_post_meta($order_data['id'], '_billing_nocall', true);

    if ($order_payment_method == 'cpgwwc') {
        if ($shipping_method_id !== 'edostavka-express-light-door-door' &&
            $shipping_method_id !== 'edostavka-express-light-door-stock') {
            $delivery_paid = 1;
        }
    }

    //$subtotal_product_price = (float)$order_total - $shipping_method_price;
    $subtotal_product_price = $price_all_products;


    // Формируем параметры для создания лида в переменной $postData = array
    $postData = array(
        "fields" => array(
            'TITLE' => $subject,
            'NAME' => $order_billing_first_name,
            'LAST_NAME' => $order_billing_last_name,
            'OPPORTUNITY' => $price_all_products-$order_discount,
            // Сумма заказа без учета доставки $order_total (с учетом доставки)
            'UF_CRM_1610595172103' => $coupon,
            'UF_CRM_1606909498737' => $order_payment_method_title,
            'UF_CRM_1610631924394' => $order_billing_city,
            'UF_CRM_1610631577757' => $order_billing_postcode,
            'UF_CRM_1607413501303' => $coupon ?? "Не применен",
            'UF_CRM_1642589852163' => $price_all_products-$order_discount,
            'UF_CRM_1642590452014' => $product_info,
            'UF_CRM_1642590471298' => $price_all_products,
            'UF_CRM_1643376120550' =>  $order_data['total'],
            'UF_CRM_1642589796763' => $order_discount,
            'UF_CRM_1608137991341' => $order_billing_address,
            'UF_CRM_1607429778032[]' => $product_base_info,
            'UF_CRM_1614868781245' => $order_discount,
            'UF_CRM_1642589944293' => $order_payment_method_title,
            'UF_CRM_1614880259189' => $comments,
            'UF_CRM_1622739879896' => $shipping_method_name, // Способ доставки
            'UF_CRM_1622746479' => $shipping_method_price, // Стоимость доставки
            'UF_CRM_1622749397' => (int)$delivery_paid, // Оплачена доставка или нет
            'UF_CRM_1622793449' => ((int)$need_callback == 0) ? 1 : 0, // Звонок менеджера
            'SOURCE_ID' => 25,
            //'CATEGORY_ID' => 1,
            'ASSIGNED_BY_ID' => 153,
            'PHONE' => array(array('VALUE' => $order_billing_phone, 'VALUE_TYPE' => 'HOME')),
        ),
        'params' => array("REGISTER_SONET_EVENT" => "Y")
    );
    $data['site_key']=$_COOKIE["site_key"];
    $data["visitor_id"]=$_COOKIE["visitor_id"];
    $data["hit_id"]=$_COOKIE["hit_id"];
    $data["session_id"]=$_COOKIE["session_id"];
    $data["consultant_server_url"]=$_COOKIE["consultant_server_url"];
    $data["comagic_id"]=$_COOKIE["comagic_id"];
    $data["form_name"]=$subject;
    $data["name"]= $order_billing_first_name." ".$order_billing_last_name;
    $data["phone"]=$order_billing_phone;
    $data["text"]=$product_base_info;
    $data["is_sale"]=true;
    $data["sale_cost"]=$price_all_products-$order_discount;
    sendCoMagick($data);

    $answer = searchDublicate($order_billing_phone);
    if (count($answer['result']['CONTACT']) > 0) {
        $postData['fields']['CONTACT_ID'] = $answer['result']['CONTACT'][0];

        $add_get_params = [
            'id' => $answer['result']['CONTACT'][0]
        ];

        $result = bitrixSend("crm.contact.get", $add_get_params);
        $postData['fields']['ASSIGNED_BY_ID'] = $result['result']['ASSIGNED_BY_ID'];

    }

    bitrixSend("crm.lead.add", $postData);

}
add_filter( 'wpcf7_recaptcha_threshold',

    function( $threshold ) {
        $threshold = 0.3; // decrease threshold to 0.3

        return $threshold;
    },

    10, 1
);

add_action( 'init', 'allow_origin' );
function allow_origin() {
    header("Access-Control-Allow-Origin: *");
}
add_action('wpcf7_mail_sent', 'your_wpcf7_mail_sent_function');
function your_wpcf7_mail_sent_function($contact_form)
{

    $posted_data = $contact_form->posted_data;
   if (268 == $contact_form->id) {
        $submission = WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        $name = $posted_data['your-name'];
        $coments = $posted_data['textarea-764'];
        $tel = $posted_data['your-tel'];
        $link = $posted_data['your-page-link'];
        $utm_source = $posted_data['utm_source'];
        $utm_medium = $posted_data['utm_medium'];
        $utm_campaign = $posted_data['utm_campaign'];
        $utm_content = $posted_data['utm_content'];
        $utm_term = $posted_data['utm_term'];
        $data['site_key']=$_COOKIE["site_key"];
        $data["visitor_id"]=$_COOKIE["visitor_id"];
        $data["hit_id"]=$_COOKIE["hit_id"];
        $data["session_id"]=$_COOKIE["session_id"];
        $data["consultant_server_url"]=$_COOKIE["consultant_server_url"];
        $data["comagic_id"]=$_COOKIE["comagic_id"];
        $data["form_name"]='форма - Воспользуйтесь консультацией специалиста';
        $data["name"]=$name;
        $data["phone"]=$tel;
        $data["text"]='форма - Воспользуйтесь консультацией специалиста'." ".$coments;
        sendCoMagick($data);

        $queryData = array(
            'fields' => array(
                'TITLE' => 'форма - Воспользуйтесь консультацией специалиста',
                'NAME' => $name,
                'COMMENTS' => $coments,
                'UF_CRM_1607414024830' => $_SERVER['HTTP_REFERER'],
                'UF_CRM_1651733563'=> $posted_data['roistat-promo-code'],
                'UTM_CAMPAIGN' => $utm_campaign,
                'UTM_CONTENT' => $utm_content,
                'UTM_MEDIUM' => $utm_medium,
                'UTM_SOURCE' => $utm_source,
                'UTM_TERM' => $utm_term,
                'ASSIGNED_BY_ID' => 153,
                'PHONE' => array(array('VALUE' => $tel, 'VALUE_TYPE' => 'HOME')),
                'SOURCE_ID' => 12,
            ),
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        );

        sendContactForm($tel, $queryData);
    }

    if (300 == $contact_form->id) {
        $submission = WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        $name = $posted_data['your-name'];
        $tel = $posted_data['your-tel'];
        $link = $posted_data['your-page-link'];
        $utm_source = $posted_data['utm_source'];
        $utm_medium = $posted_data['utm_medium'];
        $utm_campaign = $posted_data['utm_campaign'];
        $utm_content = $posted_data['utm_content'];
        $utm_term = $posted_data['utm_term'];
        $data['site_key']=$_COOKIE["site_key"];
        $data["visitor_id"]=$_COOKIE["visitor_id"];
        $data["hit_id"]=$_COOKIE["hit_id"];
        $data["session_id"]=$_COOKIE["session_id"];
        $data["consultant_server_url"]=$_COOKIE["consultant_server_url"];
        $data["comagic_id"]=$_COOKIE["comagic_id"];
        $data["form_name"]='форма - Заказать Perfleor? страница Продукта';
        $data["name"]=$name;
        $data["phone"]=$tel;
        $data["text"]='форма - Заказать Perfleor? страница Продукта';
        sendCoMagick($data);
        $queryData = array(
            'fields' => array(
                'TITLE' => 'форма - Заказать Perfleor? страница Продукта',
                'NAME' => $name,
                'UF_CRM_1607414024830' => $_SERVER['HTTP_REFERER'],
                'UF_CRM_1651733563'=> $posted_data['roistat-promo-code'],
                'ASSIGNED_BY_ID' => 153,
                'UTM_CAMPAIGN' => $utm_campaign,
                'UTM_CONTENT' => $utm_content,
                'UTM_MEDIUM' => $utm_medium,
                'UTM_SOURCE' => $utm_source,
                'UTM_TERM' => $utm_term,
                'PHONE' => array(array('VALUE' => $tel, 'VALUE_TYPE' => 'HOME')),
                'SOURCE_ID' => 12,
            ),
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        );

        sendContactForm($tel, $queryData);
    }


    if (270 == $contact_form->id) {
        $submission = WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        $name = $posted_data['your-name'];
        $email = $posted_data['your-email'];
        $link = $posted_data['your-page-link'];
        $utm_source = $posted_data['utm_source'];
        $utm_medium = $posted_data['utm_medium'];
        $utm_campaign = $posted_data['utm_campaign'];
        $utm_content = $posted_data['utm_content'];
        $utm_term = $posted_data['utm_term'];
        $data['site_key']=$_COOKIE["site_key"];
        $data["visitor_id"]=$_COOKIE["visitor_id"];
        $data["hit_id"]=$_COOKIE["hit_id"];
        $data["session_id"]=$_COOKIE["session_id"];
        $data["consultant_server_url"]=$_COOKIE["consultant_server_url"];
        $data["comagic_id"]=$_COOKIE["comagic_id"];
        $data["form_name"]='форма - Подпишись на новости и акции perfleor';
        $data["name"]=$name;
        $data["email"]=$email;
        $data["text"]='форма - Подпишись на новости и акции perfleor';
        sendCoMagick($data);


        $queryData = array(
            'fields' => array(
                'TITLE' => 'форма - Подпишись на новости и акции perfleor',
                'NAME' => $name,
                'UF_CRM_1607414024830' => $_SERVER['HTTP_REFERER'],
                'ASSIGNED_BY_ID' => 153,
                'UTM_CAMPAIGN' => $utm_campaign,
                'UTM_CONTENT' => $utm_content,
                'UTM_MEDIUM' => $utm_medium,
                'UTM_SOURCE' => $utm_source,
                'UTM_TERM' => $utm_term,
                'PHONE' => array(array('VALUE' => $email, 'VALUE_TYPE' => 'HOME')),
                'SOURCE_ID' => 26,
            ),
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        );

        sendContactForm($email, $queryData, true);
    }

    if (184 == $contact_form->id) {
        $submission = WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        $name = $posted_data['your-name'];
        $tel = $posted_data['your-tel'];
        $link = $posted_data['your-page-link'];
        $utm_source = $posted_data['utm_source'];
        $utm_medium = $posted_data['utm_medium'];
        $utm_campaign = $posted_data['utm_campaign'];
        $utm_content = $posted_data['utm_content'];
        $utm_term = $posted_data['utm_term'];
        $data['site_key']=$_COOKIE["site_key"];
        $data["visitor_id"]=$_COOKIE["visitor_id"];
        $data["hit_id"]=$_COOKIE["hit_id"];
        $data["session_id"]=$_COOKIE["session_id"];
        $data["consultant_server_url"]=$_COOKIE["consultant_server_url"];
        $data["comagic_id"]=$_COOKIE["comagic_id"];
        $data["form_name"]='форма - Есть Вопросы?';
        $data["name"]=$name;
        $data["phone"]=$tel;
        $data["text"]='форма - Есть Вопросы?';
        sendCoMagick($data);
        $queryData = array(
            'fields' => array(
                'TITLE' => 'форма - Есть Вопросы?',
                'NAME' => $name,
                'UF_CRM_1607414024830' => $_SERVER['HTTP_REFERER'],
                'UF_CRM_1651733563'=> $posted_data['roistat-promo-code'],
                'ASSIGNED_BY_ID' => 153,
                'UTM_CAMPAIGN' => $utm_campaign,
                'UTM_CONTENT' => $utm_content,
                'UTM_MEDIUM' => $utm_medium,
                'UTM_SOURCE' => $utm_source,
                'UTM_TERM' => $utm_term,
                'PHONE' => array(array('VALUE' => $tel, 'VALUE_TYPE' => 'HOME')),
                'SOURCE_ID' => 12,
            ),
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        );

        sendContactForm($tel, $queryData);
    }

    if (101 == $contact_form->id) {
        $submission = WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        $name = $posted_data['your-name'];
        $tel = $posted_data['your-tel'];
        $link = $posted_data['your-page-link'];
        $utm_source = $posted_data['utm_source'];
        $utm_medium = $posted_data['utm_medium'];
        $utm_campaign = $posted_data['utm_campaign'];
        $utm_content = $posted_data['utm_content'];
        $utm_term = $posted_data['utm_term'];
        $data['site_key']=$_COOKIE["site_key"];
        $data["visitor_id"]=$_COOKIE["visitor_id"];
        $data["hit_id"]=$_COOKIE["hit_id"];
        $data["session_id"]=$_COOKIE["session_id"];
        $data["consultant_server_url"]=$_COOKIE["consultant_server_url"];
        $data["comagic_id"]=$_COOKIE["comagic_id"];
        $data["form_name"]='Pop-UP - Заказ обратного звонка';
        $data["name"]=$name;
        $data["phone"]=$tel;
        $data["text"]='Pop-UP - Заказ обратного звонка';
        sendCoMagick($data);
        $queryData = array(
            'fields' => array(
                'TITLE' => 'Pop-UP - Заказ обратного звонка',
                'NAME' => $name,
                'UF_CRM_1607414024830' => $_SERVER['HTTP_REFERER'],
                'UF_CRM_1651733563'=> $posted_data['roistat-promo-code'],
                'UTM_CAMPAIGN' => $utm_campaign,
                'UTM_CONTENT' => $utm_content,
                'UTM_MEDIUM' => $utm_medium,
                'UTM_SOURCE' => $utm_source,
                'UTM_TERM' => $utm_term,
                'ASSIGNED_BY_ID' => 153,
                'PHONE' => array(array('VALUE' => $tel, 'VALUE_TYPE' => 'HOME')),
                'SOURCE_ID' => 12,
            ),
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        );

        sendContactForm($tel, $queryData);
    }

    if (2545 == $contact_form->id) {
        $submission = WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        $name = $posted_data['your-name'];
        $tel = $posted_data['your-tel'];
        $link = $posted_data['your-page-link'];
        $utm_source = $posted_data['utm_source'];
        $utm_medium = $posted_data['utm_medium'];
        $utm_campaign = $posted_data['utm_campaign'];
        $utm_content = $posted_data['utm_content'];
        $utm_term = $posted_data['utm_term'];
        $data['site_key']=$_COOKIE["site_key"];
        $data["visitor_id"]=$_COOKIE["visitor_id"];
        $data["hit_id"]=$_COOKIE["hit_id"];
        $data["session_id"]=$_COOKIE["session_id"];
        $data["consultant_server_url"]=$_COOKIE["consultant_server_url"];
        $data["comagic_id"]=$_COOKIE["comagic_id"];
        $data["form_name"]='Форма - Стань партнером';
        $data["name"]=$name;
        $data["phone"]=$tel;
        $data["text"]='Форма - Стань партнером';
        sendCoMagick($data);
        $queryData = array(
            'fields' => array(
                'TITLE' => 'Форма - Стань партнером',
                'NAME' => $name,
                'UF_CRM_1607414024830' => $_SERVER['HTTP_REFERER'],
                'UF_CRM_1651733563'=> $posted_data['roistat-promo-code'],
                'UTM_CAMPAIGN' => $utm_campaign,
                'UTM_CONTENT' => $utm_content,
                'UTM_MEDIUM' => $utm_medium,
                'UTM_SOURCE' => $utm_source,
                'UTM_TERM' => $utm_term,
                'ASSIGNED_BY_ID' => 153,
                'PHONE' => array(array('VALUE' => $tel, 'VALUE_TYPE' => 'HOME')),
                'SOURCE_ID' => 12,
            ),
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        );

        sendContactForm($tel, $queryData);
    }

    if (3761 == $contact_form->id) {
        $submission = WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        $name = $posted_data['your-name'];
        $surname = $posted_data['your-surname'];
        $tel = $posted_data['your-phone'];
        $data['site_key']=$_COOKIE["site_key"];
        $data["visitor_id"]=$_COOKIE["visitor_id"];
        $data["hit_id"]=$_COOKIE["hit_id"];
        $data["session_id"]=$_COOKIE["session_id"];
        $data["consultant_server_url"]=$_COOKIE["consultant_server_url"];
        $data["comagic_id"]=$_COOKIE["comagic_id"];
        $data["form_name"]="Зашел. Основы трихологии. Что нужно знать парикмахеру";
        $data["name"]=$name." ".$surname;
        $data["phone"]=$posted_data["your-phone"];
        $data["text"]="Зашел. Основы трихологии. Что нужно знать парикмахеру";
        sendCoMagick($data);

        $queryData = array(
            'fields' => array(
                'NAME' => $name  ,
                "LAST_NAME"=>$surname,
                'PHONE' => array(array('VALUE' => $posted_data["your-phone"], 'VALUE_TYPE' => 'HOME')),
            ),
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        );

     sendAnketa($tel, $queryData);
    }

    if (5 == $contact_form->id) {
        $submission = WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        $name = $posted_data['your-name'];
        $tel = $posted_data['your-tel'];
        $coments = $posted_data['textarea-701'];
        $link = $posted_data['your-page-link'];
        $utm_source = $posted_data['utm_source'];
        $utm_medium = $posted_data['utm_medium'];
        $utm_campaign = $posted_data['utm_campaign'];
        $utm_content = $posted_data['utm_content'];
        $utm_term = $posted_data['utm_term'];
        $data['site_key']=$_COOKIE["site_key"];
        $data["visitor_id"]=$_COOKIE["visitor_id"];
        $data["hit_id"]=$_COOKIE["hit_id"];
        $data["session_id"]=$_COOKIE["session_id"];
        $data["consultant_server_url"]=$_COOKIE["consultant_server_url"];
        $data["comagic_id"]=$_COOKIE["comagic_id"];
        $data["form_name"]='Страница Контакты - Обратная связь';
        $data["name"]=$name;
        $data["phone"]=$tel;
        $data["text"]='Страница Контакты - Обратная связь'." ".$coments;
        sendCoMagick($data);
        $queryData = array(
            'fields' => array(
                'TITLE' => 'Страница Контакты - Обратная связь',
                'NAME' => $name,
                'UF_CRM_1607414024830' => $_SERVER['HTTP_REFERER'],
                'ASSIGNED_BY_ID' => 153,
                'COMMENTS' => $coments,
                'UTM_CAMPAIGN' => $utm_campaign,
                'UTM_CONTENT' => $utm_content,
                'UTM_MEDIUM' => $utm_medium,
                'UTM_SOURCE' => $utm_source,
                'UTM_TERM' => $utm_term,
                'PHONE' => array(array('VALUE' => $tel, 'VALUE_TYPE' => 'HOME')),
                'UF_CRM_1651733563'=> $posted_data['roistat-promo-code'],

                'SOURCE_ID' => 12,
            ),
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        );

        sendContactForm($tel, $queryData);
    }

    if (4260 == $contact_form->id) { // form quiz
        $submission = WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        $name = $posted_data['user-name'];
        $tel = $posted_data['user-phone'];
        $result = $posted_data['result-html'];
        $data['site_key']=$_COOKIE["site_key"];
        $data["visitor_id"]=$_COOKIE["visitor_id"];
        $data["hit_id"]=$_COOKIE["hit_id"];
        $data["session_id"]=$_COOKIE["session_id"];
        $data["consultant_server_url"]=$_COOKIE["consultant_server_url"];
        $data["comagic_id"]=$_COOKIE["comagic_id"];
        $data["form_name"]='Анкета квиз B2C';
        $data["name"]=$name;
        $data["phone"]=$tel;
        $data["text"]='Анкета квиз B2C'." ".$result;

        sendCoMagick($data);
        $_SESSION['result'] = $result;
        $queryData = array(
            'fields' => array(
                'TITLE' => 'Анкета квиз B2C',
                'NAME' => $name,
                'UF_CRM_1607414024830' => $_SERVER['HTTP_REFERER'],
                'ASSIGNED_BY_ID' => 153,
                'COMMENTS' => $result,
                'UTM_CAMPAIGN' => $utm_campaign,
                'UTM_CONTENT' => $utm_content,
                'UTM_MEDIUM' => $utm_medium,
                'UTM_SOURCE' => $utm_source,
                'UTM_TERM' => $utm_term,
                'PHONE' => array(array('VALUE' => $tel, 'VALUE_TYPE' => 'HOME')),
                'SOURCE_ID' => 12,
            ),
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        );
        sendContactForm($tel, $queryData);
    }

}

function searchDublicate($phone)
{
    $add_duplicate_params = [
        'entity_type' => 'CONTACT',
        'type' => 'PHONE',
        'values' => [$phone]
    ];
    return bitrixSend("crm.duplicate.findbycomm", $add_duplicate_params);
}

function bitrixSend($method, $params)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://perfleor.bitrix24.ru/rest/31/no1x3i9g2nrqskpy/' . $method,
        CURLOPT_POSTFIELDS => http_build_query($params)
    ));

    $result = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($result, true);

    return $result;
}

function bitrixSendAnketa($method, $params)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://perfleor.bitrix24.ru/rest/31/no1x3i9g2nrqskpy/' . $method,
        CURLOPT_POSTFIELDS => http_build_query($params)
    ));

    $result = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($result, true);
    $contact_id=$result['result'];
    $queryData = array(
        'fields' => array(
            'TITLE' =>"Зашел. Основы трихологии. Что нужно знать парикмахеру",
            'CONTACT_ID' =>$contact_id,
            "STAGE_ID"=> 7,
            "ASSIGNED_BY_ID"=> 153,
        ),
        'params' => array("REGISTER_SONET_EVENT" => "Y")
    );

    die(var_dump(bitrixSend('crm.deal.add', $queryData)));


    return $result;
}

function sendContactForm($search, $data, $isEmail = false)
{

    $add_duplicate_params = [
        'entity_type' => 'CONTACT',
        'values' => [$search]
    ];

    if ($isEmail) {
        $add_duplicate_params['type'] = 'EMAIL';
    } else {
        $add_duplicate_params['type'] = 'PHONE';
    }

    $answer = bitrixSend("crm.duplicate.findbycomm", $add_duplicate_params);

    if (count($answer['result']['CONTACT']) > 0) {
        $data['fields']['CONTACT_ID'] = $answer['result']['CONTACT'][0];

        $add_get_params = [
            'id' => $answer['result']['CONTACT'][0]
        ];

        $result = bitrixSend("crm.contact.get", $add_get_params);
        $data['fields']['ASSIGNED_BY_ID'] =153;

    }

    bitrixSend("crm.lead.add", $data);

}

function sendCoMagick($data){
    $url = $data['consultant_server_url'].'api/add_offline_message/';
    $data = array(
        'site_key' =>$data['site_key'], //Значение без изменений из служебного поля site_key
        'visitor_id' => $data['visitor_id'], //Значение без изменений из служебного поля visitor_id
        'hit_id' => $data['hit_id'], //Значение без изменений из служебного поля hit_id
        'session_id' => $data['session_id'], //Значение без изменений из служебного поля session_id
        'form_name' => $data['form_name'],
        'name' => $data['name'] ?? "", //Имя клиента
        'email' => $data['email']??"", //E-mail
        'phone' => $data['phone']??"", //Номер телефона
        'text' => $data['text']??"", //Текст заявки
        'is_sale' =>$data['is_sale'] ? true:false,
        'sale_cost' => $data['sale_cost']
    );

    /* Если все поля в html-разметке формы называются так же, как этого требует CoMagic, можно написать "$data = $_POST".
    В противном случае потребуются дополнительные преобразования. */
    $options = array( 'http' =>
        array(
            'header' => "Content-type: application/x-www-form-urlencoded; charset=UTF-8",
            'method' => "POST",
            'content' => http_build_query($data)
        )
    );
   // print $options['http']['content'];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $resultArray = json_decode($result, true);

}
function sendAnketa($search, $data, $isEmail = false)
{
    bitrixSendAnketa("crm.contact.add", $data);
}

//валидация номера тел в заказе
add_action('woocommerce_checkout_process', 'is_phone');

function is_phone()
{
    $phone_number = $_POST['billing_phone'];

    $regexp = "/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/";

    if (!preg_match($regexp, $phone_number)) {
        wc_add_notice(__('Некорректный номер телефона'), 'error');
    }
}


function wc_remove_checkout_fields($fields)
{

    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_email']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_address_2']);
    //unset( $fields['billing']['billing_postcode'] );
    //unset( $fields['shipping']['shipping_company'] );
    //unset( $fields['shipping']['shipping_phone'] );
    //unset( $fields['shipping']['shipping_first_name'] );
    //unset( $fields['shipping']['shipping_last_name'] );
    //unset( $fields['shipping']['shipping_address_2'] );
    //unset( $fields['shipping']['shipping_postcode'] );

    // Order fields
    unset($fields['order']['order_comments']);

    return $fields;
}

add_filter('woocommerce_checkout_fields', 'wc_remove_checkout_fields');


// array in logfile

function loger($arr)
{
    $d_s = print_r($arr, true);
    $fd = fopen("hello.txt", 'w') or die("не удалось создать файл");
    fwrite($fd, $d_s);
    fclose($fd);
}

function getCriticalCss(){

    if(is_front_page()){
        return include(__DIR__.'/styles/lib/critical/home.min.css');
    }

    if(is_product()){
        return include(__DIR__.'/styles/lib/critical/card.min.css');
    }

    if(is_cart() || is_checkout()){
        return include(__DIR__.'/styles/lib/critical/cart.min.css');
    }

    if(is_page('quality-control')){}

    if(is_page('quiz')){
        return include(__DIR__.'/styles/lib/critical/quiz.min.css');
    }

    if(is_page('our-partners')){}



    return include(__DIR__.'/styles/lib/critical/home.min.css');



}


/* START Выводим название купона  */
add_filter('woocommerce_get_order_item_totals', 'add_coupons_codes_line_to_order_totals_lines', 10, 5);
function add_coupons_codes_line_to_order_totals_lines($total_rows, $order, $tax_display)
{
    // Exit if there is no coupons applied
    if (sizeof($order->get_used_coupons()) == 0)
        return $total_rows;

    $new_total_rows = []; // Initializing
    foreach ($total_rows as $key => $total) {
        $new_total_rows[$key] = $total;
        if ($key == 'discount') {
            $applied_coupons = $order->get_used_coupons();
            if (count($order->get_used_coupons()) == 1) {
                $new_total_rows['coupon_codes'] = array(
                    'label' => __('Применённый купон:', 'woocommerce'),
                    'value' => implode(', ', $applied_coupons),
                );
            }

            if (count($order->get_used_coupons()) > 1) {
                $new_total_rows['coupon_codes'] = array(
                    'label' => __('Применённые купоны:', 'woocommerce'),
                    'value' => implode(', ', $applied_coupons),
                );
            }
        }
    }

    //перезванивать
    $data = $order->get_data();
    $nocall_checkbox = get_post_meta($data['id'], '_billing_nocall', true);

    $new_total_rows['nocall'] = array(
        'label' => __('Перезвонить:', 'woocommerce'),
        'value' => ($nocall_checkbox ? 'Нет' : 'Да'),
    );

    return $new_total_rows;
}
/* END Выводим название купона  */


