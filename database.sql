CREATE DATABASE snapshack;
USE snapshack;

CREATE TABLE users (
  id int PRIMARY KEY AUTO_INCREMENT,
  name nvarchar(255) NOT NULL,
  email nvarchar(255),
  pass nvarchar(255) NOT NULL,
  isAdmin boolean default false,
  disabled boolean default false
);
CREATE TABLE board(
  id int PRIMARY KEY AUTO_INCREMENT,
  shortName nvarchar(5),
  fullName nvarchar(100),
  postCount int default 0
);

CREATE TABLE posts (
  id int PRIMARY KEY AUTO_INCREMENT,
  author int,
  title nvarchar(60),
  FOREIGN KEY (author) references users(id),
  content TEXT,
  image TEXT, 
  likes int default 0,
  board int,
  FOREIGN KEY (board) references board(id),
  timeCreated TIMESTAMP default UTC_TIMESTAMP(),
  comments int default 0
);

CREATE TABLE comments (
  id int PRIMARY KEY AUTO_INCREMENT,
  author int,
  target int,
  FOREIGN KEY (target) references posts(id),
  FOREIGN KEY (author) references users(id),
  content TEXT,
  likes int default 0,
  timeCreated TIMESTAMP default UTC_TIMESTAMP()
);

CREATE TABLE likeTracker(
  ip nvarchar(128),
  tip nvarchar(10),
  targetPost int,
  targetComment int,
  FOREIGN KEY (targetPost) references posts(id),
  FOREIGN KEY(targetComment) references comments(id)
);
INSERT INTO board (shortName, fullName) VALUES ('na', 'Nature');
INSERT INTO board (shortName, fullName) VALUES ('pe', 'Pets');
INSERT INTO board (shortName, fullName) VALUES ('wi', 'Wildlife');
INSERT INTO board (shortName, fullName) VALUES ('ma', 'Macro');
INSERT INTO board (shortName, fullName) VALUES ('ca', 'Cars');
INSERT INTO board (shortName, fullName) VALUES ('ar', 'Architecture');
INSERT INTO board (shortName, fullName) VALUES ('sp', 'Street Photography');
INSERT INTO board (shortName, fullName) VALUES ('pr', 'Portraiture');
INSERT INTO board (shortName, fullName) VALUES ('fa', 'Fashion');
INSERT INTO board (shortName, fullName) VALUES ('fo', 'Food Photography');
INSERT INTO board (shortName, fullName) VALUES ('tr', 'Travel');
INSERT INTO board (shortName, fullName) VALUES ('la', 'Landscape');
INSERT INTO board (shortName, fullName) VALUES ('ue', 'Urban Exploration');
INSERT INTO board (shortName, fullName) VALUES ('np', 'Night Photography');
INSERT INTO board (shortName, fullName) VALUES ('ab', 'Abstract');
INSERT INTO board (shortName, fullName) VALUES ('bw', 'Black and White');
INSERT INTO board (shortName, fullName) VALUES ('far', 'Fine Art');
INSERT INTO board (shortName, fullName) VALUES ('do', 'Documentary');
INSERT INTO board (shortName, fullName) VALUES ('sl', 'Still Life');
INSERT INTO board (shortName, fullName) VALUES ('ap', 'Astrophotography');
INSERT INTO board (shortName, fullName) VALUES ('aer', 'Aerial Photography');
INSERT INTO board (shortName, fullName) VALUES ('uw', 'Underwater Photography');
INSERT INTO board (shortName, fullName) VALUES ('spo', 'Sports');
INSERT INTO board (shortName, fullName) VALUES ('ce', 'Concerts and Events');
INSERT INTO board (shortName, fullName) VALUES ('pp', 'Product Photography');


INSERT INTO users(name, pass, isAdmin, disabled) VALUES ('Anonymus', '123', false, true);
INSERT INTO users(name, pass, isAdmin, disabled) VALUES ('Administrator', 'admin', true, false);
INSERT INTO posts(author, title, content, image, board) VALUES (1,'First post real', 'Lorem ipsum', 'notFound.png', 1);
