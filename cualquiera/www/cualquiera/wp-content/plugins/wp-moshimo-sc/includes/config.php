<?php
/* config */

$shopid = get_option('moshimo_shopid');
define('ShopID', $shopid);

$moshimo_authcode = get_option('moshimo_authcode');
define('MoshimoAuthCode', $moshimo_authcode);

$moshimo_exists_stock = get_option('moshimo_exists_stock');
if(empty($moshimo_exists_stock)) { $moshimo_exists_stock = 1; }
define('ExistsStock', $moshimo_exists_stock);

// 商品ベースURL
$article_base_url = "http://www.moshimo.com/article/".ShopID."/";
define('ARTICLE_BASE_URL', $article_base_url);

// 商品画像ベースURL
$image_base_url = "http://image.moshimo.com/item_image/";
define('IMAGE_BASE_URL', $image_base_url);

$detail_url = "http://www.moshimo.com/article/" . $shopid . "/";

define('MAX_TABLE_WIDTH', "700");

// キャッシュの生存期間
$cache_life_time = get_option('cache_life_time');

if(empty($cache_life_time)) {
	define('CacheLifeTime', "3600");
} else {
	define('CacheLifeTime', $cache_life_time);
}