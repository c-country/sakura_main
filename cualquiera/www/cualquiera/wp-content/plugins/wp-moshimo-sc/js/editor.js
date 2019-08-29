/**
 * プラグインのショートコード作成と挿入を行います。
 */
var WpMoshimoSC = function()
{
	var words = null;
	var tags = null;
	var article_id = null;
	var article_category_code = null;
	var is_newly = null;
	var is_salable = null;
	var stock_status = null;
	var exists_stock = null;
	var recommended_sales_price_from = null;
	var recommended_sales_price_to = null;
	var list_per_page = null;
	var page_index = null;
	var sort_order = null;
	var imgsize = null;
	var cols = null;
	var disp = null;

	/**
	 * ショートコードを取得します。
	 *
	 * @return	ショートコード。
	 */
	function getShortCode()
	{
		return "[moshimo]\n"
 + ( 'words:' + words.value + '\n' )
 + ( 'tags:' + tags.value + '\n' )
 + ( 'article_id:' + article_id.value + '\n' )
 + ( 'article_category_code:' + article_category_code.value + '\n' )
 + ( 'is_newly:' + is_newly.value + '\n' )
 + ( 'is_salable:' + is_salable.value + '\n' )
 + ( 'stock_status:' + stock_status.value + '\n' )
 + ( 'exists_stock:' + exists_stock.value + '\n' )
 + ( 'recommended_sales_price_from:' + recommended_sales_price_from.value + '\n' )
 + ( 'recommended_sales_price_to:' + recommended_sales_price_to.value + '\n' )
 + ( 'list_per_page:' + list_per_page.value + '\n' )
 + ( 'page_index:' + page_index.value + '\n' )
 + ( 'sort_order:' + sort_order.value + '\n' )
 + ( 'imgsize:' + imgsize.value + '\n' )
 + ( 'cols:' + cols.value + '\n' )
 + ( 'disp:' + disp.value + '\n' )
 + "[/moshimo]";

	}

	/**
	 * インスタンスを初期化します。
	 */
	this.initialize = function()
	{
		words = document.getElementById( "msc_words" );
		tags = document.getElementById( "msc_tags" );
		article_id = document.getElementById( "msc_article_id" );
		article_category_code = document.getElementById( "msc_article_category_code" );
		is_newly = document.getElementById( "msc_is_newly" );
		is_salable = document.getElementById( "msc_is_salable" );
		stock_status = document.getElementById( "msc_stock_status" );
		exists_stock = document.getElementById( "msc_exists_stock" );
		recommended_sales_price_from = document.getElementById( "msc_recommended_sales_price_from" );
		recommended_sales_price_to = document.getElementById( "msc_recommended_sales_price_to" );
		list_per_page = document.getElementById( "msc_list_per_page" );
		page_index = document.getElementById( "msc_page_index" );
		sort_order = document.getElementById( "msc_sort_order" );
		imgsize = document.getElementById( "msc_imgsize" );
		cols = document.getElementById( "msc_cols" );
		disp = document.getElementById( "msc_disp" );
	};

	/**
	 * 「投稿に挿入」ボタンが押された時に発生します。
	 */
	this.onClickSubmitButton = function()
	{
		var text = getShortCode();
		self.parent.onMoshimoShortCode( text );
	};
};

//唯一の WpMoshimoSC インスタンスを生成
var wpMoshimoSC = new WpMoshimoSC();

window.onload = function()
{
	wpMoshimoSC.initialize();
};