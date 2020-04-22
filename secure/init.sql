-- TODO: Put ALL SQL in between `BEGIN TRANSACTION` and `COMMIT`
BEGIN TRANSACTION;
-- TODO: create tables
CREATE TABLE images (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  file_name TEXT NOT NULL,
  file_ext TEXT NOT NULL,
  title TEXT,
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
-- IMAGES
INSERT INTO images (
    id,
    file_name,
    file_ext,
    title,
    artist_id,
    width,
    height,
    description,
    contact
  )
VALUES
  (
    1,
    "1.jpg",
    "jpg",
    "Floral embroidery",
    1,
    "11",
    "14",
    "Orange Flower and Bee",
    "cindyhuang.me"
  );
INSERT INTO images (
    id,
    file_name,
    file_ext,
    title,
    artist_id,
    width,
    height,
    description,
    contact
  )
VALUES
  (
    2,
    "2.jpg",
    "jpg",
    "Floaral Embroidery",
    1,
    "5",
    "5",
    "Some flowers",
    "cindyhuang.me"
  );
INSERT INTO images (
    id,
    file_name,
    file_ext,
    title,
    artist_id,
    width,
    height,
    description,
    contact
  )
VALUES
  (
    3,
    "3.jpg",
    "jpg",
    "Lemon Embroidery",
    1,
    "5",
    "5",
    "A cool lemon",
    "cindyhuang.me"
  );
INSERT INTO images (
    id,
    file_name,
    file_ext,
    title,
    artist_id,
    width,
    height,
    description,
    contact
  )
VALUES
  (
    4,
    "4.jpg",
    "jpg",
    "Figure drawing",
    1,
    "28",
    "14",
    "A drawing of my teacher Mr. Hardy",
    "cindyhuang.me"
  );
INSERT INTO images (
    id,
    file_name,
    file_ext,
    title,
    artist_id,
    width,
    height,
    description,
    contact
  )
VALUES
  (
    5,
    "5.jpg",
    "jpg",
    "Mural",
    1,
    "11",
    "14",
    "A nice painting on the wall",
    "cindyhuang.me"
  );
INSERT INTO images (
    id,
    file_name,
    file_ext,
    title,
    artist_id,
    width,
    height,
    description,
    contact
  )
VALUES
  (
    6,
    "6.jpg",
    "jpg",
    "~Reflections~",
    1,
    "24",
    "12",
    "Still life",
    "cindyhuang.me"
  );
INSERT INTO images (
    id,
    file_name,
    file_ext,
    title,
    artist_id,
    width,
    height,
    description,
    contact
  )
VALUES
  (
    7,
    "7.jpg",
    "jpg",
    "Playground",
    1,
    "24",
    "17",
    "Pencil drawing of a playground",
    "cindyhuang.me"
  );
INSERT INTO images (
    id,
    file_name,
    file_ext,
    title,
    artist_id,
    width,
    height,
    description,
    contact
  )
VALUES
  (
    8,
    "8.jpg",
    "jpg",
    "Roses",
    1,
    "18",
    "12",
    "A nice painting of roses",
    "cindyhuang.me"
  );
INSERT INTO images (
    id,
    file_name,
    file_ext,
    title,
    artist_id,
    width,
    height,
    description,
    contact
  )
VALUES
  (
    9,
    "9.jpg",
    "jpg",
    "Succulents",
    1,
    "9",
    "12",
    "A nice painting of plants",
    "cindyhuang.me"
  );
INSERT INTO images (
    id,
    file_name,
    file_ext,
    title,
    artist_id,
    width,
    height,
    description,
    contact
  )
VALUES
  (
    10,
    "10.jpg",
    "jpg",
    "Tunnel Book",
    1,
    "6",
    "4",
    "Paper book",
    "cindyhuang.me"
  );
-- TAG TYPES
INSERT INTO tag_types (id, name)
VALUES
  (1, "Class");
INSERT INTO tag_types (id, name)
VALUES
  (2, "Medium");
-- TAGS
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
-- IMAGE TAGS
  -- 3 tags for one image
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (1, 1, 2);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (2, 1, 9);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (3, 1, 11);
-- multiple tags per image
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (4, 2, 11);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (5, 2, 3);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (6, 3, 5);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (7, 3, 10);
-- other image tags
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (8, 4, 10);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (9, 5, 4);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (10, 6, 7);
INSERT INTO image_tags (id, image_id, tag_id)
VALUES
  (11, 8, 9);
-- ARTISTS
INSERT INTO artists (id, name)
VALUES
  (1, "Cindy Huang");
COMMIT;
