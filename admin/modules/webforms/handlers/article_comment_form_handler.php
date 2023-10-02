<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . 'modules/webforms/handlers/form_handler.php';
require_once CMS_ROOT . 'modules/webforms/handlers/handler_property.php';
require_once CMS_ROOT . 'core/model/webform.php';
require_once CMS_ROOT . 'database/dao/settings_dao.php';

class ArticleCommentFormHandler extends Formhandler {

    public static string $TYPE = 'article_comment_form_handler';
    private MysqlConnector $_mysql_connector;

    public function __construct() {
        parent::__construct();
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public function getRequiredProperties(): array {
        return array(
            new HandlerProperty('name', 'textfield'),
            new HandlerProperty('email', 'textfield'),
            new HandlerProperty('message', 'textarea'),
            new HandlerProperty('parent', 'textfield')
        );
    }

    public function getNameResourceIdentifier(): string {
        return 'webforms_article_comment_form_handler_name';
    }

    public function getType(): string {
        return self::$TYPE;
    }

    public function handle(array $fields, Page $page, ?Article $article): void {
        $name = $this->getFilledInPropertyValue('name');
        $email = $this->getFilledInPropertyValue('email');
        $message = $this->getFilledInPropertyValue('message');
        $parent = $this->getFilledInPropertyValue('parent');
        if ($parent) {
            $parent = null;
        }
        $article_id = $article->getId();

        $query = "INSERT INTO article_comments (`name`, `message`, email_address, created_at, article_id, parent) VALUES (?, ?, ?, now(), ?, ?)";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param("sssii", $name, $message, $email, $article_id, $parent);
        $this->_mysql_connector->executeStatement($statement);
    }
}

?>