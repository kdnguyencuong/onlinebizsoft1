<?php 
	require_once (__DIR__.'/crawler.php');

	class VnexpressCrawler extends Crawler{
		function __construct($url){
			$this->_element_parent = './/div [@id="box_details_news"]';
			$this->_title = './/div [@class="title_news"]/h1';
			$this->_date = './/div [@class="block_timer left txt_666"]';
			$this->_content = './/div [@class="fck_detail width_common block_ads_connect"]';

			parent::save($url);
		}
	}
//'.//div [@class="ArticleDetail"]'
	//'.//h1 [@class="title"]'
	//'.//span [@class="ArticleDate"]'
	//'.//div [@id="ArticleContent"]'

	// $vnexpress = new VnexpressCrawler('http://vnexpress.net/tin-tuc/thoi-su/chu-tich-nuoc-chinh-quyen-biet-lang-nghe-da-khong-co-vu-dong-tam-3576419.html');

?>