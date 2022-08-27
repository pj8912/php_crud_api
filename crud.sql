
CREATE TABLE posts(
	id int AUTO_INCREMENT PRIMARY KEY not null,
	title varchar(250) COLLATE not null,
	body text not null,
    author varchar(255) not null,
	created_at datetime default current_timestamp
);
