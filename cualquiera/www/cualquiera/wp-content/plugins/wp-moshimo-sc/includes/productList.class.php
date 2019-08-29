<?php
class ProductList {
	function apiSearch($api_params = array()) {
		global $status,$reason,$count;
		$API_BASE_URL = "http://api.moshimo.com/article/search2";
		$api_url = sprintf("%s?authorization_code=%s",$API_BASE_URL,MoshimoAuthCode);

		reset($api_params);
		while(list ($key, $val) = each($api_params) ){
		    if(isset($api_params[$key]) && $api_params[$key] != ""){
				$api_url = sprintf("%s&%s=%s",$api_url, $key, urlencode($api_params[$key]));
			}
		}

		$data = $this->cache_article($api_url);

		foreach($data->Result as $result) {
			$status = $result->Status;
			$reason = $result->Reason;
		}
		$count = $data->Found;

		$item_temp	= array();
		$item_array	= array();

		if(is_object($data->Articles->Article)) {
			foreach($data->Articles->Article as $article) {
		
				$item_temp["ARTICLEID"] = (string)$article->ArticleId;
				$item_temp["NAME"] = (string)$article->Name;
				$item_temp["DESCRIPTION"] = (string)$article->Description;
				$item_temp["SPECIALDESCRIPTION"] = (string)$article->SpecialDescription;
				$item_temp["SPEC"] = (string)$article->Spec;
				$item_temp["CATCHCOPY"] = (string)$article->CatchCopy;
				$item_temp["MAKERNAME"] = (string)$article->MakerName;
				$item_temp["MODELNUMBER"] = (string)$article->ModelNumber;
			
				$array_tags = (array)$article->Tags;
				$item_temp["TAG"] = $array_tags["Tag"];
				$item_temp["TAGLINK"] = $this->tag_to_Link($item_temp["TAG"]);
			
				$item_temp["ISNEWLY"] = (string)$article->IsNewly;
				$item_temp["HEAVYSELLER"] = (string)$article->HeavySeller;
				$item_temp["ISDELIVERYSAMEDAY"] = (string)$article->IsDeliverySameday;
				$item_temp["ISFREESHIPPING"] = (string)$article->IsFreeShipping;
				$item_temp["DODFROM"] = (string)$article->DodFrom;
				$item_temp["DODTO"] = (string)$article->DodTo;
				$item_temp["PREORDERFLAG"] = (string)$article->PreorderFlag;
				$item_temp["PREORDERPERIOD"] = (string)$article->PreorderPeriod;
			
				$item_temp["CATEGORYCODE"] = (string)$article->Category->Code;
				$item_temp["CATEGORYNAME"] = (string)$article->Category->Name;
				$item_temp["CATEGORYLEVEL"] = (string)$article->Category->Level;
			
				$item_temp["CATEGORYPARENT"] = (array)$article->Category->Parents;
			
				$item_temp["GROUPID"] = (string)$article->GroupId;
				$item_temp["IMAGECODE"] = (string)$article->ImageCode;
				$item_temp["IMAGECOUNT"] = (string)$article->ImageCount;
				$item_temp["JANCODE"] = (string)$article->JanCode;
				$item_temp["PAYMENTTYPE"] = (string)$article->PaymentType;
				$item_temp["BUNDLEIMPOSSIBLE"] = (string)$article->BundleImpossible;
				$item_temp["STARTDATE"] = (string)$article->StartDate;
				$item_temp["HASMATERIAL"] = (string)$article->HasMaterial;
				$item_temp["FIXEDPRICE"] = (string)$article->FixedPrice;
				$item_temp["DEFAULTPROFITPRICE"] = (string)$article->DefaultProfitPrice;
				$item_temp["DEFAULTPROFITRATE"] = (string)$article->DefaultProfitRate;
				$item_temp["RECOMMENDEDPRICE"] = (string)$article->RecommendedPrice;
				$item_temp["MINIMUMPRICE"] = (string)$article->MinimumPrice;
				$item_temp["WHOLESALEPRICE"] = (string)$article->WholesalePrice;
				$item_temp["SHOPPRICE"] = (string)$article->ShopPrice;
				$item_temp["STOCKSTATUS"] = (string)$article->StockStatus;
				$item_temp["STOCKSTATUSWORD"] = (string)$article->StockStatusWord;
			
				$item_temp["DISCOUNTRATE"] = $this->discountRate($item_temp["FixedPrice"],$item_temp["ShopPrice"]);
			
				array_push($item_array, $item_temp);
			}
		}
		return $item_array;
	}

	function discountRate($FixedPrice,$ShopPrice) {
		if($FixedPrice != 0 && $FixedPrice > $ShopPrice) {
			$dRate = 100 - floor($ShopPrice/$FixedPrice*100);
			if($dRate < 5) {
				$dRate = 0;
			}
		} else {
			$dRate = 0;
		}
		return $dRate;
	}

	function tag_to_Link($arrTag, $before="<li>", $after="</li>\n") {
		for($i=0;$i<count($arrTag);$i++) {
			if (function_exists('is_ktai') && is_ktai()) {
				$before = "";
				$after = "<br>\n";
				$text = $arrTag[$i];
			} else {
				$text = urlencode($arrTag[$i]);
			}
			$link .= $before . "<a href=\"". get_bloginfo('url') . "/list/?tags=$text\">$arrTag[$i]</a>" . $after;
		}
		return $link;
	}

	//Cache
	function cache_article($param) {

		include_once("Cache/Lite.php");
		$cacheDir = dirname(__FILE__) . "/Cache/tmp/";

		$option = array(
			"cacheDir"	=>$cacheDir,
			"lifeTime"		=>CacheLifeTime,
			"automaticCleaningFactor"	=>'100'
		);
		$cache = new Cache_lite($option);

		$cache->clean(false, 'old');

		if($data = $cache->get($param, "apiSearch")) {
			$xml_data = simplexml_load_string($data);
		} else {
			$xml = $this->get_content($param);
			$cache->save($xml, $param, "apiSearch");
			$xml_data = simplexml_load_string($xml);
		}

		return $xml_data;
	}

	function get_content($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		ob_start();
		curl_exec($ch);
		curl_close($ch);
		$string = ob_get_contents();
		ob_end_clean();
		return $string;
	}

}
?>