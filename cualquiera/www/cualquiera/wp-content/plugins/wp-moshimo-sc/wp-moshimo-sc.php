<?php
/*
Plugin Name: WP-Moshimo-SC
Plugin URI: http://wp-moshimo.com/
Description: Moshimo Short Code
Version: 2.1
Author: d-craft
Author URI: http://d-craft.net/
*/

require_once("includes/config.php");
require_once("includes/common.php");
require_once("includes/productList.class.php");
require_once("includes/shortcode.php");


class WpMoshimoSC
{
	private $pluginDirUrl;

	public function __construct()
	{
		$this->pluginDirUrl = WP_PLUGIN_URL . '/' . array_pop( explode( DIRECTORY_SEPARATOR, dirname( __FILE__ ) ) ) . "/";

		if( is_admin() )
		{
			add_action( "admin_head_media_upload_moshimosc_form", array( &$this, "onMediaHead"      )     );
			add_action( "media_buttons",                         array( &$this, "onMediaButtons"   ), 20 );
			add_action( "media_upload_moshimosc",                 "media_upload_moshimosc"                 );

			add_filter( "admin_footer", array( &$this, "onAddShortCode" ) );
		}
	}

	public function onAddShortCode()
	{
		if( strpos( $_SERVER[ "REQUEST_URI" ], "post.php"     ) ||
			strpos( $_SERVER[ "REQUEST_URI" ], "post-new.php" ) ||
			strpos( $_SERVER[ "REQUEST_URI" ], "page-new.php" ) ||
			strpos( $_SERVER[ "REQUEST_URI" ], "page.php"     ) )
		{
			echo <<<HTML
<script type="text/javascript">
//<![CDATA
function onMoshimoShortCode( text ) { send_to_editor( text ); }
//]]>
</script>
HTML;
		}
	}

	public function onMediaButtons()
	{
		global $post_ID, $temp_ID;

		$id     = (int)( 0 == $post_ID ? $temp_ID : $post_ID );
		$iframe = apply_filters( "media_upload_moshimosc_iframe_src", "media-upload.php?post_id={$id}&amp;type=moshimosc&amp;tab=moshimosc" );
		$option = "&amp;TB_iframe=true&amp;keepThis=true&amp;height=500&amp;width=640";
		$title  = "WP-MoshimoShortCode";
		$button = "{$this->pluginDirUrl}images/button.png";

		echo '<a href="' . $iframe . $option . '" class="thickbox" title="' . $title . '"><img src="' . $button . '" alt="' . $title . '" /></a>';
	}

	public function onMediaButtonPage()
	{
		echo <<<HTML
<form name="url_editor">
<table>
<tr>
<td>検索キーワード：</td>
<td><input type="text" id="msc_words" size="30" /></td>
</tr>
<tr>
<td>タグ：</td>
<td><input type="text" id="msc_tags" size="30" /></td>
</tr>
<tr>
<td>商品ID：</td>
<td><input type="text" id="msc_article_id" size="30" /></td>
</tr>
<tr>
<td>カテゴリーコード：</td>
<td><input type="text" id="msc_article_category_code" size="30" /></td>
</tr>
<tr>
<td>新着商品：</td>
<td>
<select id="msc_is_newly">
<option value="" selected>指定なし</option>
<option value="1">新着商品のみ</option>
</select>
</td>
</tr>
<tr>
<td>ヒット実績あり：</td>
<td>
<select id="msc_is_salable">
<option value="" selected>指定なし</option>
<option value="1">ヒット商品のみ</option>
</select>
</td>
</tr>
<tr>
<td>在庫ステータス：</td>
<td>
<select id="msc_stock_status">
<option value="0">在庫切れ</option>
<option value="1">在庫わずか</option>
<option value="2" selected>在庫あり</option>
<option value="3">在庫豊富</option>
</select>
</td>
</tr>
<tr>
<td>在庫ありのみ表示：</td>
<td>
<select id="msc_exists_stock">
<option value="1" selected>在庫ありのみを検索する</option>
<option value="2">在庫なしのみを検索する</option>
</select>
</td>
</tr>
<tr>
<td>推奨販売価格下限：</td>
<td><input type="text" id="msc_recommended_sales_price_from" size="30" /></td>
</tr>
<tr>
<td>推奨販売価格上限：</td>
<td><input type="text" id="msc_recommended_sales_price_to" size="30" /></td>
</tr>
<tr>
<td>取得数：</td>
<td><input type="text" id="msc_list_per_page" value="5" size="30" /></td>
</tr>
<tr>
<td>取得ページ：</td>
<td><input type="text" id="msc_page_index" value="0" size="10" />「0」は1ページ目とする。</td>
</tr>
<tr>
<td>ソート順：</td>
<td>
<select id="msc_sort_order">
<option value="newly" selected>新着順</option>
<option value="sales">売上順</option>
<option value="word">キーワード順</option>
<option value="recommendation_price_a">価格安い順</option>
<option value="recommendation_price_d">価格高い順</option>
</select>
</td>
</tr>
<tr>
<td>画像サイズ：</td>
<td>
<select id="msc_imgsize">
<option value="s">58×58px</option>
<option value="m" selected>80×80px</option>
<option value="r">150×150px</option>
<option value="l">300×300px</option>
</select>
</td>
</tr>
<tr>
<td>画像配置の列数：</td>
<td>
<select id="msc_cols">
<option value="1">1列</option>
<option value="2">2列</option>
<option value="3">3列</option>
<option value="4">4列</option>
<option value="5" selected>5列</option>
</select>
</td>
</tr>
<tr>
<td>レイアウトパターン：</td>
<td>
<select id="msc_disp">
<option value="ranking">ランキング形式</option>
<option value="orderlink">カート形式</option>
<option value="list" selected>リスト形式</option>
</select>
</tr>
</table>
	<p>
	<input type="button" class="button-primary" value="投稿に挿入" onclick="javascript:wpMoshimoSC.onClickSubmitButton()" />
	</p>
</form>
HTML;
	}

	public function onMediaHead()
	{
		echo '<script type="text/javascript" src="' . $this->pluginDirUrl . 'js/editor.js"></script>';
	}

	function onModifyMediaTab( $tabs )
	{
		return array( "moshimosc" => "ショートコードの編集" );
	}
}

if( class_exists( WpMoshimoSC ) )
{
	$wpMoshimoSC = new WpMoshimoSC();

	if( is_admin() )
	{
		function media_upload_moshimosc()
		{
			wp_iframe( "media_upload_moshimosc_form" );
		}

		function media_upload_moshimosc_form()
		{
			global $wpMoshimoSC;

			add_filter( "media_upload_tabs", array( &$wpMoshimoSC, "onModifyMediaTab" ) );

			echo "<div id=\"media-upload-header\">\n";
			media_upload_header();
			echo "</div>\n";

			$wpMoshimoSC->onMediaButtonPage();
		}
	}
}

function add_css_to_header() {
	$plugin_dir = str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
	$msc_css_url = WP_PLUGIN_URL . '/' . $plugin_dir . 'css/msc.css';
	echo "<link rel=\"stylesheet\" href=\"" . $msc_css_url . "\" type=\"text/css\" />";
}
add_action('wp_head', 'add_css_to_header');

?>