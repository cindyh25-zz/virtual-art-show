-- TODO: Put ALL SQL in between `BEGIN TRANSACTION` and `COMMIT`
BEGIN TRANSACTION;
-- TODO: create tables
CREATE TABLE images (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  file_name TEXT NOT NULL,
  file_ext TEXT NOT NULL,
  artist_id INTEGER,
  width TEXT,
  height TEXT,
  description TEXT,
  contact TEXT
);
CREATE TABLE tags (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  tag TEXT NOT NULL UNIQUE,
  type_id INTEGER NOT NULL
);
CREATE TABLE image_tags (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  image_id INTEGER NOT NULL,
  tag_id TEXT NOT NULL
);
CREATE TABLE artists (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  name TEXT NOT NULL
);
CREATE TABLE tag_types (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  name TEXT NOT NULL
);
-- CREATE TABLE `examples` (
-- 	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
-- 	`name`	TEXT NOT NULL
-- );
-- TODO: initial seed data
-- INSERT INTO `examples` (id,name) VALUES (1, 'example-1');
-- INSERT INTO `examples` (id,name) VALUES (2, 'example-2');
INSERT INTO images (id, file_name, file_ext)
VALUES
  (1, "1.jpg", "jpg");
INSERT INTO images (id, file_name, file_ext)
VALUES
  (2, "2.jpg", "jpg");
INSERT INTO images (id, file_name, file_ext)
VALUES
  (3, "3.jpg" "jpg");
INSERT INTO images (id, file_name, file_ext)
VALUES
  (4, "4.jpg", "jpg");
INSERT INTO images (id, file_name, file_ext)
VALUES
  (5, "5.jpg", "jpg");
INSERT INTO images (id, file_name, file_ext)
VALUES
  (6, "6.jpg", "jpg");
INSERT INTO images (id, file_name, file_ext)
VALUES
  (7, "7.jpg", "jpg");
INSERT INTO images (id, file_name, file_ext)
VALUES
  (8, "8.jpg", "jpg");
INSERT INTO images (id, file_name, file_ext)
VALUES
  (9, "9.jpg", "jpg");
INSERT INTO images (id, file_name, file_ext)
VALUES
  (10, "10.jpg" "jpg");
INSERT INTO tag_types (id, name)
VALUES
  (1, "class");
INSERT INTO tag_types (id, name)
VALUES
  (2, "medium");
INSERT INTO tags (id, tag, type_id)
VALUES
  (1, "AP Studio", 1);
INSERT INTO tags (id, tag, type_id)
VALUES
  (2, "Advanced Art Honors", 1);
INSERT INTO tags (id, tag, type_id)
VALUES
  (3, "Visual Art 1", 1);
INSERT INTO tags (id, tag, type_id)
VALUES
  (4, "Visual Art 2", 1);
INSERT INTO tags (id, tag, type_id)
VALUES
  (5, "Foundations of Art", 1);
INSERT INTO tags (id, tag, type_id)
VALUES
  (6, "Intro to Painting", 1);
INSERT INTO tags (id, tag, type_id)
VALUES
  (7, "Photography", 1);
INSERT INTO tags (id, tag, type_id)
VALUES
  (8, "Acrylic", 2);
INSERT INTO tags (id, tag, type_id)
VALUES
  (9, "Watercolor", 2);
INSERT INTO tags (id, tag, type_id)
VALUES
  (10, "Pencil", 2);
INSERT INTO tags (id, tag, type_id)
VALUES
  (11, "Ink", 2);
-- 3 tags for one image
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (1, 1, 2);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (1, 1, 9);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (1, 1, 11);
-- multiple tags per image
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (1, 2, 11);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (1, 2, 3);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (1, 3, 5);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (1, 3, 10);
-- other image tags
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (1, 4, 10);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (1, 5, 4);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (1, 6, 7);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (1, 8, 9);
COMMIT;
