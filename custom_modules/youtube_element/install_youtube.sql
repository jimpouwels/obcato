CREATE TABLE youtube_elements_metadata (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) default NULL,
  embed VARCHAR(255) default NULL,
  element_id INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT youtube_metadata_elements FOREIGN KEY youtube_metadata_elements (element_id)
	REFERENCES elements (id)
	ON DELETE CASCADE
	ON UPDATE CASCADE
);