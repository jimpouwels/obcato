<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once "database/mysql_connector.php";
	include_once "database/dao/authorization_dao.php";
	include_once "core/data/guestbook.php";
	include_once "core/data/guestbook_message.php";
	include_once "libraries/utilities/string_utility.php";

	/*
		This class takes care of all persistance actions for a Template object.
	*/
	class GuestBookDao {
	
		// Holds the list of columns that are to be collected
		private static $myAllColumns = "g.id, g.title, g.closed, g.auto_acknowledge, g.created_at, g.created_by";
	
		/*
			This service is a singleton
		*/
		private static $instance;
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/* 
			Creates a new instance (if not yet exists
			for this DAO
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new GuestBookDao();
			}
			return self::$instance;
		}
		
		/*
			Returns all guestbooks.
		*/
		public function getAllGuestBooks() {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM guestbooks g ORDER BY created_at DESC";
			$result = $mysql_database->executeSelectQuery($query);
			$guestbooks = array();
			while ($row = mysql_fetch_array($result)) {
				$guestbook = GuestBook::constructFromRecord($row);

				array_push($guestbooks, $guestbook);
			}
			return $guestbooks;
		}
		
		/*
			Returns the guestbook with the given ID.
			
			@param $id The ID of the guestbook to find
		*/
		public function getGuestBook($id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT " . self::$myAllColumns . " FROM guestbooks g WHERE g.id = " . $id;
			$result = $mysql_database->executeSelectQuery($query);
			$guestbook = NULL;
			while ($row = mysql_fetch_array($result)) {
				$guestbook = GuestBook::constructFromRecord($row);
				
				break;
			}
			return $guestbook;
		}
		
		/*
			Creates and persists a new guestbook.
		*/
		public function createGuestBook() {
			$new_guestbook = new GuestBook();
			$mysql_database = MysqlConnector::getInstance();			
			$authorization_dao = AuthorizationDao::getInstance();
			$user = $authorization_dao->getUser($_SESSION['username']);
			$new_guestbook->setCreatedById($user->getId());
			$new_id = $this->persistGuestBook($new_guestbook);
			$new_guestbook->setId($new_id);
			return $new_guestbook;
		}
		
		/*
			Persists the given guestbook.
			
			@param $new_guestbook The guestbook to persist
		*/
		public function persistGuestBook($new_guestbook) {
			$mysql_database = MysqlConnector::getInstance(); 

			$query = "INSERT INTO guestbooks (title, closed, auto_acknowledge, created_at, created_by) 
					  VALUES ('Nieuw gastenboek', 0, 1, 
					  now(), " . $new_guestbook->getCreatedById() . ")";
		    
			$mysql_database->executeQuery($query);
			
			return mysql_insert_id();
		}
		
		/*
			Deletes a guestbook.
			
			@param $guestbook_id The ID of the guestbook to delete
		*/
		public function deleteGuestBook($guestbook_id) {
			$mysql_database = MysqlConnector::getInstance(); 

			$query = "DELETE FROM guestbooks WHERE id = " . $guestbook_id;
		    
			$mysql_database->executeQuery($query);
		}
		
		/*
			Updates the given guestbook.
			
			@param $guestbook The guestbook to update
		*/
		public function updateGuestBook($guestbook) {
			$mysql_database = MysqlConnector::getInstance(); 

			$query = "UPDATE guestbooks SET title = '" . $guestbook->getTitle() . "', 
					  closed = " . $guestbook->isClosed() . ", auto_acknowledge = " . 
					  $guestbook->isAutoAcknowledge() . " WHERE id = " . $guestbook->getId();
			  
			$mysql_database->executeQuery($query);
		}
		
		/*
			Returns the message with the given ID.
			
			@param $message_id The ID of the message to find
		*/
		public function getMessage($message_id) {
			$mysql_database = MysqlConnector::getInstance();
			
			$query = "SELECT * FROM guestbook_messages WHERE id = " . $message_id;
			$result = $mysql_database->executeSelectQuery($query);
			$message = NULL;
			while($row = mysql_fetch_array($result)) {
				$message = GuestbookMessage::constructFromRecord($row);
				
				break;
			}
			return $message;
		}
		
		/*
			Returns all messages for the given guestbook id.
			
			@param $guestbook_id The ID of the guestbook to find the messages for
		*/
		public function getMessagesByGuestBook($guestbook_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM guestbook_messages g WHERE g.guestbook_id = " . $guestbook_id . " ORDER BY posted_at DESC";
			$result = $mysql_database->executeSelectQuery($query);
			$messages = array();
			while ($row = mysql_fetch_array($result)) {
				$message = GuestbookMessage::constructFromRecord($row);
				
				array_push($messages, $message);
			}
			return $messages;
		}
		
		/*
			Returns all messages for the given guestbook id within the given range.
			
			@param $guestbook_id The ID of the guestbook to find the messages for
			@param $from The messages from
			@param $to The messages to
		*/
		public function getMessagesForRange($guestbook_id, $from, $to) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT * FROM guestbook_messages g WHERE g.guestbook_id = " . $guestbook_id . 
					 " ORDER BY posted_at DESC LIMIT " . $from . ", " . ($to - $from);
			$result = $mysql_database->executeSelectQuery($query);
			$messages = array();
			while ($row = mysql_fetch_array($result)) {
				$message = GuestbookMessage::constructFromRecord($row);
				
				array_push($messages, $message);
			}
			return $messages;
		}
		
		/*
			Returns the message count for the given guestbook.
		*/
		public function getMessageCount($guestbook_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			
			$query = "SELECT count(*) AS message_count FROM guestbook_messages WHERE guestbook_id = " . $guestbook_id;

			$result = $mysql_database->executeSelectQuery($query);
			$count = 0;
			while ($row = mysql_fetch_assoc($result)) {
				$count = $row['message_count'];
			}
			return $count;
		}
		
		/*
			Deletes the message with the given ID.
			
			@param $message_id
		*/
		public function deleteMessage($message_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			$query = "DELETE FROM guestbook_messages WHERE id = " . $message_id;
			$mysql_database->executeQuery($query);
		}
		
		/*
			Deletes all messages of the given guestbook.
			
			@param $guestbook_id The ID of the guestbook to delete the messages for
		*/
		public function deleteMessages($guestbook_id) {
			$mysql_database = MysqlConnector::getInstance(); 
			$query = "SELECT * FROM guestbook_messages WHERE guestbook_id = " . $guestbook_id;
			$mysql_database->executeQuery($query);
		}
		
		/*
			Persists the given message.
			
			@param $message The message to persist
			@param $guestbook_id The ID of the guestbook to persist the message for
		*/
		public function persistMessage($message, $guestbook_id) {
			$mysql_database = MysqlConnector::getInstance();
			
			$email_value = "NULL";
			if (!is_null($message->getEmailAddress()) && $message->getEmailAddress() != '') {
				$email_value = $message->getEmailAddress();
			}
			
			$acknowledged_value = 0;
			if ($message->isAcknowledged()) {
				$acknowledged_value = 1;
			}
			
			$query = "INSERT INTO guestbook_messages (message, author, email_address" . 
					 ", posted_at, acknowledged, guestbook_id) VALUES ('" . nl2br(StringUtility::escapeXml($message->getMessage())) .
					 "', '" . $message->getAuthor() . "', '" . $email_value . "', now(), " . 
					 $acknowledged_value . ", " . $guestbook_id . ")";
			$mysql_database->executeQuery($query);
		}
		
	}
?>