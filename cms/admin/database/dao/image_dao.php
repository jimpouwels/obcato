<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once CMS_ROOT . "/database/mysql_connector.php";
	include_once CMS_ROOT . "/core/data/image_label.php";
	include_once CMS_ROOT . "/core/data/image.php";
	
	class ImageDao {

		private static $myAllColumns = "i.id, i.title, i.published, i.created_at, i.created_by, i.file_name, i.thumb_file_name";

		private static $instance;

		private function __construct() {
		}

		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new ImageDao();
			}
			return self::$instance;
		}

		public function getImage($image_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM images i WHERE id = " . $image_id;
			$result = $mysql_database->executeQuery($query);
			$image = null;
			while ($row = $result->fetch_assoc()) {
				$image = Image::constructFromRecord($row);
			}
			
			return $image;
		}

		public function updateImage($image) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "UPDATE images SET title = '" . $image->getTitle() . "', 
			          published = " . $image->isPublished() . ", file_name = '" . $image->getFileName() . "'
					  , thumb_file_name = '" . $image->getThumbFileName() . "' WHERE id = " . $image->getId();
			$mysql_database->executeQuery($query);
		}

		public function getAllImages() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM images i";
			$result = $mysql_database->executeQuery($query);
			$images = array();
			
			while ($row = $result->fetch_assoc()) {
				$image = Image::constructFromRecord($row);
				
				array_push($images, $image);
			}
			
			return $images;
		}

		public function getAllImagesWithoutLabel() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM images i LEFT JOIN images_labels ils ON i.id = ils.image_id 
			          WHERE ils.image_id IS NULL";
					  
			$result = $mysql_database->executeQuery($query);
			$images = array();
			
			while ($row = $result->fetch_assoc()) {
				$image = Image::constructFromRecord($row);
				
				array_push($images, $image);
			}
			
			return $images;
		}

		public function searchImages($keyword, $filename, $label_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT DISTINCT " . self::$myAllColumns . " FROM images i";
						
			if (!is_null($label_id) && $label_id != '') {
				$query = $query . ", images_labels ils WHERE ils.label_id = " . $label_id . " AND ils.image_id = i.id";
			}
			if (!is_null($keyword)) {
				$pos = strpos($query, 'WHERE');
				if ($pos) {
					$query = $query . ' AND';
				} else {
					$query = $query . ' WHERE';
				}
				$query = $query . " i.title LIKE '" . $keyword . "%'";
			}
			if (!is_null($filename)) {
				$pos = strpos($query, 'WHERE');
				if ($pos) {
					$query = $query . ' AND';
				} else {
					$query = $query . ' WHERE';
				}
				$query = $query . " i.file_name LIKE '" . $filename . "%'";
			}
			$query = $query . " ORDER BY created_at";
			$result = $mysql_database->executeQuery($query);
			$images = array();
			
			while ($row = $result->fetch_assoc()) {
				$image = Image::constructFromRecord($row);
				
				array_push($images, $image);
			}
			return $images;
		}

		public function createImage() {
			$mysql_database = MysqlConnector::getInstance(); 
			$new_image = new Image();
			$new_image->setPublished(false);
			$new_image->setTitle('Nieuwe afbeelding');
			
			$authorization_dao = AuthorizationDao::getInstance();
			$user = $authorization_dao->getUser($_SESSION['username']);
			$new_image->setCreatedById($user->getId());
			
			$this->persistImage($new_image);
			
			return $new_image;
		}

		public function deleteImage($image) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM images WHERE id = " . $image->getId();
	
			// delete the uploaded images
			if (!is_null($image->getFileName()) && $image->getFileName() != '') {
				$file_path = UPLOAD_DIR . "/" . $image->getFileName();
				$thumb_file_path = UPLOAD_DIR . "/" . $image->getThumbFileName();
				if (file_exists($file_path)) {
					unlink($file_path);
				}
				if (file_exists($thumb_file_path)) {
					unlink($thumb_file_path);
				}
			}
			
			$mysql_database->executeQuery($query);
		}

		private function persistImage($image) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$published_value = $image->isPublished();
			if (!isset($published_value) || $published_value == '') {
				$published_value = 0;
			}
			$query = "INSERT INTO images (title, published, created_at, created_by, file_name, thumb_file_name)
					  VALUES ('" . $image->getTitle() . "', " . $published_value . ", now(), " . 
					  $image->getCreatedBy()->getId() . ", NULL, NULL)";
			$mysql_database->executeQuery($query);
			$image->setId($mysql_database->getInsertId());
		}

		public function createLabel() {
			$new_label = new ImageLabel();
			$new_label->setName("Nieuw label");
			$postfix = 1;
			while (!is_null($this->getLabelByName($new_label->getName()))) {
				$new_label->setName("Nieuw label " . $postfix);
				$postfix++;
			}
			$new_id = $this->persistLabel($new_label);
			$new_label->setId($new_id);
			
			return $new_label;
		}

		public function getAllLabels() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM image_labels";
			$result = $mysql_database->executeQuery($query);
			$labels = array();
			
			while ($row = $result->fetch_assoc()) {
				$label = ImageLabel::constructFromRecord($row);
				
				array_push($labels, $label);
			}
			
			return $labels;
		}

		public function getLabel($id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM image_labels WHERE id = " . $id;
			$result = $mysql_database->executeQuery($query);
			$label = NULL;
			
			while ($row = $result->fetch_assoc()) {
				$label = ImageLabel::constructFromRecord($row);
			}
			
			return $label;
		}

		public function getLabelByName($name) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM image_labels WHERE name = '" . $name . "'";
			$result = $mysql_database->executeQuery($query);
			$label = NULL;
			
			while ($row = $result->fetch_assoc()) {
				$label = ImageLabel::constructFromRecord($row);
			}
			
			return $label;
		}
		
		public function persistLabel($label) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "INSERT INTO image_labels (name) VALUES  ('" . $label->getName() . "')";
		
			$mysql_database->executeQuery($query);
			return $mysql_database->getInsertId();
		}
		
		public function updateLabel($label) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "UPDATE image_labels SET name = '" . $label->getName() . 
					  "' WHERE id = " . $label->getId();
			$mysql_database->executeQuery($query);
		}
		
		public function deleteLabel($label) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM image_labels WHERE id = " . $label->getId();
			
			$mysql_database->executeQuery($query);
		}
		
		public function addLabelToImage($label_id, $image) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "INSERT INTO images_labels (image_id, label_id) VALUES (" . $image->getId() . ", " . $label_id . ")";
			
			$mysql_database->executeQuery($query);
		}
		
		public function deleteLabelForImage($label_id, $image) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "DELETE FROM images_labels WHERE image_id = " . $image->getId() ."
			          AND label_id = " . $label_id;
			
			$mysql_database->executeQuery($query);
		}
		
		public function getLabelsForImage($image_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT il.id, il.name FROM image_labels il, images_labels ils, 
					  images i WHERE ils.image_id = " . $image_id . " AND ils.image_id =
					  i.id AND il.id = ils.label_id";
					  
		    $result = $mysql_database->executeQuery($query);
			$labels = array();
			
			while ($row = $result->fetch_assoc()) {
				$label = ImageLabel::constructFromRecord($row);
				
				array_push($labels, $label);
			}
			
			return $labels;
		}
	}
	
?>