<?php

function moshimo_shortcode($atts, $content = null) {
	global $ARTICLE_BASE_URL;
	extract(shortcode_atts(array(
		'words' => '',
		'tags' => '',
		'article_id' => '',
		'article_category_code' => '',
		'is_newly' => '',
		'is_salable' => '',
		'stock_status' => '',
		'exists_stock' => 1,
		'recommended_sales_price_from' => '',
		'recommended_sales_price_to' => '',
		'list_per_page' => 5,
		'page_index' => '',
		'sort_order' => 'sales',
		'css' => '',
		'imgsize' => '',
		'cols' => 5,
		'disp' => 'list' 
	), $atts));

	$content = do_shortcode($content);

	if(isset($content)) {
		$params = moshimoParam($content);
	}
	foreach($params as $key => $val ) {
		$$key = $val;
	}

	// Get Article Info from MoshimoAPI
	$obj = new ProductList();
	$api_params = array(
		"words" => $words,
		"tags" => $tags,
		"require_tag_list" => 1,
		"is_newly" => $is_newly,
		"is_salable" => $is_salable,
		"is_delivery_sameday" => $is_delivery_sameday,
		"is_free_shipping" => $is_free_shipping,
		"stock_status" => $stock_status,
		"exists_stock" => $exists_stock,
		"fixed_price_from" => $fixed_price_from,
		"fixed_price_to" => $fixed_price_to,
		"default_profit_price_from" => $default_profit_price_from,
		"default_profit_price_to" => $default_profit_price_to,
		"default_profit_rate_from" => $default_profit_rate_from,
		"default_profit_rate_to" => $default_profit_rate_to,
		"recommended_sales_price_from" => $recommended_sales_price_from,
		"recommended_sales_price_to" => $recommended_sales_price_to,
		"minimum_price_from" => $minimum_price_from,
		"minimum_price_to" => $minimum_price_to,
		"wholesale_price_from" => $wholesale_price_from,
		"wholesale_price_to" => $wholesale_price_to,
		"has_shop_price" => $has_shop_price,
		"field" => $field,
		"article_id" => $article_id,
		"article_category_code" => $article_category_code,
		"article_group_id" => $article_group_id,
		"payment_type" => $payment_type,
		"start_date_from" => $start_date_from,
		"start_date_to" => $start_date_to,
		"list_per_page" => $list_per_page,
		"page_index" => $page_index,
		"sort_order" => $sort_order
		);

	$item_array = $obj->apiSearch($api_params);

	if($disp == "ranking") { 
		$html_moshimo = "<div class=\"sc-title\">$print_words</div>\n";
	}
	$html_moshimo .= show_table($list_per_page, $cols, $item_array, $disp, $imgsize, $css);

	return $html_moshimo;
}

function moshimoParam($text) {
//	$text = str_replace(" ","\n",$text);
	$text = str_replace("<br />","",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\r","\n",$text);
	$array = explode("\n", $text);
	$arrParam = array();
	foreach($array as $line) {
		list($key, $val) = explode(":", $line);
		$param = array($key => $val);
		$arrParam = $arrParam + $param;
	}
	return $arrParam;
}

// table tags output
function show_table($list_per_page,$cols,$items,$disp,$imgsize='',$css='') {
	global $detail_url;

	$yoko = $cols;
	$tate = ceil($list_per_page/$yoko);

	$td_width = floor(100/$yoko)-1;

	$img_size_max = ceil( MAX_TABLE_WIDTH/$yoko );
	if($img_size_max > 150) {
		$list_imgsize = "r";
	} else {
		$list_imgsize = "m";
	}

	switch($disp) {
		case "ranking":
			if(!$css){ $css = "ranking"; }
			$yoko = 1;
			$tate = $list_per_page;
			if(empty($imgsize)) {
				$imgsize = "m";
			}
			break;
		case "orderlink":
			if(!$css){ $css = "orderlink"; }
			$yoko = 1;
			$tate = 1;
			if(empty($imgsize)) {
				$imgsize = "l";
			}
			break;
		case "list":
			if(!$css){ $css = "list"; }
			if($list_per_page > 5) {
				if(empty($cols)) {
					$yoko = 5;
				}
			} else {
				$yoko = $list_per_page;
			}
			if(empty($imgsize)) {
				$imgsize = $list_imgsize;
			}
			break;
		default:
			if(!$css){ $css = "list"; }
			$imgsize = "m";
	}
	$html = "<div class=\"msc-table\">\n<table class=\"$css\">\n";

	$a = 0;
	for($i = 0; $i < $tate; $i++) {
		$td = "";
		$html .= "<tr>\n";

		for($j = 0; $j < $yoko; $j++) {
			$ranking = $a + 1;
			$name = $items[$a]["NAME"];
			$article_id = $items[$a]["ARTICLEID"];
			$imagecode = $items[$a]["IMAGECODE"];
			$price = number_format($items[$a]["SHOPPRICE"]);
			$description = $items[$a]["DESCRIPTION"];
			$spec = $items[$a]["SPEC"];
			$stock_status_word = $items[$a]["STOCKSTATUSWORD"];
			$tag = $items[$a]["TAG"];
			$img = "http://image.moshimo.com/item_image/$imagecode/1/$imgsize.jpg";
			$link = $detail_url.$article_id;

			if($article_id) {
				$td .= "<td width=\"" . $td_width . "%\">\n";

				// ranking
				if($disp == "ranking") {
					$td .= "<div class=\"sc_ranking\">【 $ranking 位】</div>\n";
				}

				$td .= "<div class=\"sc_img\"><a href=\"$link\" target=\"_blank\"><img src=\"$img\" alt=\"$name\" /></a></div>\n";
				$td .= "<div class=\"sc_name\"><a href=\"$link\" target=\"_blank\">$name</a></div>\n";
				$td .= "<div class=\"sc_price\">販売価格:<br /><span>" . $price . "</span>円</div>\n";

				// orderlink
				if($disp == "orderlink") {
					$description = mb_strimwidth(strip_tags($description),0,400,"...");
					$td .= "<div class=\"sc_description\">" . $description . "</div>\n";
					$td .= "<span class=\"sc-botan\"><a href=\"https://www.moshimo.com/cart/add?shop_id=" . ShopID . "&article_id=$article_id\" target=\"_blank\">カートに入れる</a></span>";
				}

				$td .= "<div class=\"sc_stock\">【" . $stock_status_word . "】</div>\n";
				$td .= "</td>\n";
				$a++;
			} else {
				$td .= "<td>&nbsp;</td>\n";
			}
		}
		$html .= $td;
		$html .= "</tr>\n";
	}
	$html .= "</table>\n</div>\n";
	$html .= "<div class=\"pbwpmsc\"><a href=\"http://sc.wp-moshimo.com\">powered by d-craft</a></div>\n";
	return $html;
}

add_shortcode('moshimo', 'moshimo_shortcode');

function get_moshimo_posts_init_options() {
	if (!get_option('moshimo_installed')) {
		update_option('moshimo_shopid', "");
		update_option('moshimo_authcode', "");
		update_option('moshimo_exists_stock', "1");
		update_option('cache_life_time', "3600");
	}
}
register_activation_hook(__FILE__, 'get_moshimo_posts_init_options');

function get_moshimo_posts_add_admin_menu() {
	add_submenu_page('plugins.php', 'もしもAPIの設定', 'もしもAPIの設定', 8, __FILE__, 'get_moshimo_posts_admin_page');
}
add_action('admin_menu', 'get_moshimo_posts_add_admin_menu');

function get_moshimo_posts_admin_page() {
	if ($_POST['posted'] == 'Y') {
		update_option('moshimo_shopid', intval($_POST['shopid']));
		update_option('moshimo_authcode', stripslashes($_POST['authcode']));
		update_option('moshimo_exists_stock', intval($_POST['exists_stock']));
		update_option('moshimo_installed', 1);
		update_option('cache_life_time', intval($_POST['cache_life_time']));
	}
?>
<?php if($_POST['posted'] == 'Y') : ?><div class="updated"><p><strong>設定を保存しました</strong></p></div><?php endif; ?>
<div class="wrap">
	<h2>もしもAPIの設定</h2>
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="posted" value="Y">
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="shopid">もしもショップID<label></th>
				<td>
					<input name="shopid" type="text" id="shopid" value="<?php echo get_option('moshimo_shopid'); ?>" class="regular-text code" /><br />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="authcode">もしもAPI認証コード<label></th>
				<td>
					<input name="authcode" type="text" id="authcode" value="<?php echo get_option('moshimo_authcode'); ?>" class="regular-text code" /><br />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="exists_stock">商品表示設定<label></th>
				<td>
					<select name="exists_stock" id="exists_stock">
				<?php
				$options = array(
					array('value' => '1', 'text' => '在庫ありのみ'),
					array('value' => '0', 'text' => '全表示')
				);
				foreach($options as $option) : ?>
					<option value="<?php echo esc_attr($option['value']); ?>"<?php if(get_option('moshimo_exists_stock') == $option['value']) : ?> selected="selected"<?php endif; ?>><?php echo esc_attr($option['text']); ?></option>
				<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="cache_life_time">キャッシュの生存期間（秒）設定　（初期値=3600秒）<label></th>
				<td>
					<input name="cache_life_time" type="text" id="cache_life_time" value="<?php echo get_option('cache_life_time'); ?>" class="regular-text code" /><br />
				</td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="変更を保存" />
		</p>
	</form>
</div>
<?php
}
?>