<?php 
	class Crawler{

		public $content;
		private $__conn = '';
		public $_element_parent = '';
		public $_title = '';
		public $_date = '';
		public $_content = '';

		function save($url){
			$this->get_data($url,$this->_element_parent,$this->_title,$this->_date,$this->_content);
			//var_dump($this->content);
			$this->connect();
			$this->insert('news',$this->content);
			$this->dis_connect();
		}

		function get_data($url,$element_parent,$title,$date,$content) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

			$result = curl_exec($ch);
			curl_close($ch);

			$document = new \DomDocument('1.0', 'UTF-8');
			libxml_use_internal_errors(true);
			$document->loadHTML($result);
			libxml_use_internal_errors(false);
			$xpath = new DomXPath($document);
			$items = $xpath->query($element_parent);
			$data_array = array();
			foreach ($items as $item) {
				$data['title'] = trim($xpath->query($title,$item)->item(0)->nodeValue); 
				$data['url'] = $this->changeTitle($data['title']);
				$ngaythang = trim($xpath->query($date,$item)->item(0)->nodeValue); 
				preg_match('/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/',$ngaythang,$view);
				$cvtime = implode('',$view);
				$str_date = DateTime::createFromFormat('d/m/Y',$cvtime);
				$date_insert = $str_date->format('Y-m-d');
				$data['date'] = $date_insert;
				$data['content'] = trim($xpath->query($content,$item)->item(0)->nodeValue); 
				$data['description'] = substr($data['content'], 0,200);
				$data_array[] =$data;
			}

		    $this->content = $data_array;
		}

		//Hàm kết nối
		function connect(){
			try {

			    $this->__conn = new PDO('mysql:host=localhost;dbname=php_example;charset=utf8','root','');
			    //echo 'Kết nối thành công';

			} catch (PDOException $e) {
			    print "Error!: " . $e->getMessage() . "<br/>";
			    die();
			}
		}

		//Hàm ngắt kết nối
		function dis_connect(){
			$this->__conn = null;
		}

		function insert($table,$data){
			//Lưu trữ danh sách field
			$field_list = '';

			//Lưu trữ danh sách giá trị tương ứng với field
			$value_list = '';

			foreach ($data[0] as $key => $value) {
				//var_dump($value);
				$field_list .= ','.$key;
				$value_list .= ','.$this->__conn->quote($value);
			}

			$field_key = trim($field_list, ',');
			$field_val = trim($value_list, ',');
			//var_dump($field_val);
			//Vì sau vòng lặp các biến $field_list và $value_list sẽ thừa 1 dấu ,nên ra sẽ dùng hàm 
			//trim để xóa đi
			$sql = "INSERT INTO $table($field_key) VALUES ($field_val)";
			//var_dump($sql);
			$stmt = $this->__conn->exec($sql);
			return $stmt;
		}
		function stripUnicode($str){
			if(!$str) return false;
				$unicode = array(
					'a' =>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
					'A' =>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
					'd' =>'đ',
					'D' =>'Đ',
					'e' =>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
					'E' =>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
					'i' =>'í|ì|ỉ|ĩ|ị',
					'I' =>'Í|Ì|Ỉ|Ĩ|Ị',
					'o' =>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
					'O' =>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
					'u' =>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
					'U' =>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
					'y' =>'ý|ỳ|ỷ|ỹ|ỵ',
					'Y' =>'Ý|Ỳ|Ỷ|Ỹ|Ỵ'
				);

				foreach ($unicode as $khongdau => $codau) {
					$arr=explode("|",$codau);
					$str = str_replace($arr,$khongdau,$str);
				}
				return $str;
		}

		function changeTitle($str){
			$str=trim($str);
			if ($str=="") return "";
			$str =str_replace('"','',$str);
			$str =str_replace("'",'',$str);
			$str = $this->stripUnicode($str);
			$str = mb_convert_case($str,MB_CASE_LOWER,'utf-8');
			// MB_CASE_UPPER / MB_CASE_TITLE / MB_CASE_LOWER
			$str = str_replace(' ','-',$str);
			return $str;
		}

	}
	
?>