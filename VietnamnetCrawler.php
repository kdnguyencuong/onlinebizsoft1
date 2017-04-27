<?php 
	require_once (__DIR__.'/crawler.php');

	class VietnamnetCrawler extends Crawler{
		function __construct($url){
			$this->_element_parent = './/div [@class="ArticleDetail"]';
			$this->_title = './/h1 [@class="title"]';
			$this->_date = './/span [@class="ArticleDate"]';
			$this->_content = './/div [@id="ArticleContent"]';

			parent::save($url);
		}
	}
//'.//div [@class="ArticleDetail"]'
	//'.//h1 [@class="title"]'
	//'.//span [@class="ArticleDate"]'
	//'.//div [@id="ArticleContent"]'

	// $vietnamnet = new VietnamnetCrawler('http://vietnamnet.vn/vn/the-gioi/binh-luan-quoc-te/donald-trump-bi-an-truc-quyen-luc-moi-tai-nha-trang-368141.html');

?>