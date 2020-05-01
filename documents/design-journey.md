# Project 3: Design Journey

Be clear and concise in your writing. Bullets points are encouraged.

**Everything, including images, must be visible in VS Code's Markdown Preview.** If it's not visible in Markdown Preview, then we won't grade it.

# Design & Plan (Milestone 1)

## Describe your Gallery (Milestone 1)
> What will your gallery be about? 1 sentence.

I am planning to create an art gallery to showcase the artwork of students at my highschool.

> Will you be using your existing Project 1 or Project 2 site for this project? If yes, which project?

I will not be using my existing project.

> If using your existing Project 1 or Project 2, please upload sketches of your final design here.


## Target Audience(s) (Milestone 1)
> Tell us about your target audience(s).

The target audience is people in the Westford Academy community, a high school in Massachusetts. This includes people such as students, teachers, administration, parents, or other people interested in the arts department at the town. Normally the town hosts art shows at the school or other spaces in the town, but since large events are cancelled this could be a way to still showcase student artwork and maybe show more work than normally can be shown at in-person events. These people could be looking for inspiration for their own artwork, but their primary desire when viewing the gallery is simply to appreciate the work and talent of students at the school. These viewers would be curious about who created it, how the artist created the pieces (what media, what timeframe), the meaning or inspiration behind it, and the scale of the art since it will not be an in-person gallery. These are much like what someone would want out of viewing a museum gallery.

The other audience would be the art teachers and students who want to display their (or their students') artwork. They want to be able to easily add to the gallery since they are proud of their work and want to showcase it. They should also be able to annotate it with any titles they have or descriptions to best frame their artwork in context. Some artists write artist statements to fully describe its meaning and intent, or they might want to make the art stand alone.

## Design Process (Milestone 1)
> Document your design process. Show us the evolution of your design from your first idea (sketch) to design you wish to implement (sketch). Show us the process you used to organize content and plan the navigation (card sorting), if applicable.
> Label all images. All labels must be visible in VS Code's Markdown Preview.
> Clearly label the final design.
### Full gallery design
![Home screen sketch](/documents/homedesign.jpg)
I explored different layouts to view all of the images in the gallery. I decided to go with a grid that shows the whole images but is not categorized, since I felt the categorization functionality can already be achieved through filtering by tags.

### Filtering by tags
![Filter by tags sketch](/documents/tags.jpg)
I chose the third iteration of a side menu with tags so that the tags can be clearly categorized by type. The tags would also be easily accessible if placed in a side menu rather than at the top.

### Viewing single image
![Full size image view sketch](/documents/singleimg.jpg)
Above, I thought about what content is needed when displaying a single artwork based on the target audiences' desired outline above. I decided to go with a simple, straightforward view with the art and description side by side. Since people might also want to browse all images without returning to the main gallery each time, I think it would help to allow users to click through them at full size using arrows buttons.

### Adding an image
![Adding image flow sketch](/documents/adddesign.jpg)
This is a flow where users can add an image. Since there is quite a bit of (optional) information they can add, I decided to separate it into a different page. This also makes sure it does not detract from the focus on the images in the home screen.

### Deleting an image
![Deleting image entry point sketch](/documents/deletedesign.jpg)
Deleting should be more of a hidden action, so I decided to make the entry point inside a options menu in the full size view of each image.

### FINAL DESIGN
![Final design sketch](/documents/finaldesign.jpg)


## Design Patterns (Milestone 1)
> Explain how your site leverages existing design patterns for image galleries.
> Identify the parts of your design that leverage existing design patterns and justify their usage.
> Most of your site should leverage existing patterns. If not, fully explain why your design is a special case (you need to have a very good reason here to receive full credit).

My design leverages existing design patterns in that the normally gallery view is layed out in a grid format to see many images at once. Clicking on an image expands it to a larger view with a single image as well as other information or metadata about the image, much like any photo gallery.

Adding an image is easily accessibly on the home page and will be emphasized in the final product using color. However, I kept the entry point still in the corner so that it does not distract from the images which should be the primary focus.

Deleting an image should be less frequent and less encouraged, so I decided to only allow users to remove images by expanding to the single image view, clicking a generic options menu, then deleting from there. This is because we don't wan't most people to see right away that deleting is possible as I want to encourage users to add rather than remove them.

It also follows existing design patterns for the single image view of having the hierarchy of the image very large and the title and creator emphasized as well, along with other metadata below and less prominent.

## Requests (Milestone 1)
> Identify and plan each request you will support in your design.
> List each request that you will need (e.g. view image details, view gallery, etc.)
> For each request, specify the request type (GET or POST), how you will initiate the request: (form or query string param URL), and the HTTP parameters necessary for the request.

- Request: view full size artwork with details
  - Type: GET, string param URL
  - Params: id _or_ image_id (images.id in DB)
- Request: submit an artwork
  - Type: POST, form
- Request: delete an artwork
  - Type: POST, form
- Request: delete a tag from artwork
  - Type: POST, form
- Request: add a tag to artwork
  - Type: POST, form
- Request: view images with certain tags
  - Type: GET, link with string param



## Database Schema Design (Milestone 1)
> Plan the structure of your database. You may use words or a picture.
> Make sure you include constraints for each field.

> Hint: You probably need `images`, `tags`, and `image_tags` tables.

images: stores information about each artwork in the gallery.
```
images (
  id: INTEGER {PK, NOT, AI, U},
  file_name: TEXT {NOT},
  file_ext: TEXT {NOT},
  title: TEXT,
  artist_id: INTEGER,
  width: TEXT,
  height: TEXT,
  description: TEXT,
  contact: TEXT
)
```

tags: stores the different tags that images can have and the category or type (tags are categoriaed as either a class, medium, or subject)
```
tags (
  id: INTEGER {PK, NOT, AI, U},
  tag: TEXT {NOT, U},
  type_id: TEXT {NOT}
);
```

image_tags: stores connections between images and tags
```
image_tags(
  id: INTEGER {PK, NOT, AI, U},
  image_id: INTEGER {NOT},
  tag_id: INTEGER {NOT}
);
```

artists: stores information about artists
```
artists(
  id: INTEGER {PK, NOT, AI, U},
  name: TEXT {NOT},
);
```

tag_types: stores information about which types of tags are allowed
```
tag_types (
  id: INTEGER {PK, NOT, AI, U},
  name: TEXT {NOT},
)
```


## Database Query Plan (Milestone 1)
> Plan your database queries. You may use natural language, pseudocode, or SQL.
> Using your request plan above, plan all of the queries you need.

- Select all images
```
SELECT * FROM images;
```
- Select image for an image with `id = image_id`
```
SELECT file_name, file_ext FROM images WHERE id = image_id;

```
- Select  information/metadata for an image with `id = image_id`
```
SELECT artists.name, images.width, images.height, images.description FROM images LEFT INNER JOIN artists ON
images.artist_id = artists.id WHERE images.id = image_id;
```
- Select tags for an image with `id = image_id`
```
SELECT tags.tag, tags.id FROM tags INNER JOIN image_tags ON image_tags.tag_id = tags.id INNER JOIN images ON image_tags.image_id = images.id WHERE images.id = :image_id;
```
- Select images with certain tag
```
SELECT images.file_name, images_file_ext FROM images INNER JOIN image_tags ON image_tags.tag_id = tags.id INNER JOIN images ON image_tags.image_id = images.id WHERE image_tags.tag_id = tag_id GROUP BY images.id;
```

- Insert image
```
INSERT INTO images (id, file_name, file_ext, ...) VALUES (id, file_name, file_ext, ...);

INSERT INTO image_tags (id, image_id, tag_id) VALUES (id, image_id, tag_id);
```
- Delete image with `id = id`
```
DELETE FROM images WHERE id = id;

DELETE FROM image_tags WHERE image_id = id;
```
- Add tag to image
```
INSERT INTO image_tags (image_id, tag_id) VALUES (:image_id, :tag_id);
```
- Create new tag
```
INSERT INTO tags (tag, type_id) VALUES (:tag, :type);
```
- Select tags of a certain type
```
SELECT * FROM tags WHERE type_id = :type_id;
```



## Code Planning (Milestone 1)
> Plan what top level PHP pages you'll need.

- Home page: Gallery
- Submit page: add images and details to the gallery
- Single image view: view an image at full size along with details and information about it
- About page: information about the photo gallery

Filters for tags will be implemented in the home page and shown/hidden using JavaScript.

> Plan what partials you'll need.

I plan to use a partial for the header and footer.

I will also write PHP code for SQL queries in init.php so that the code for the actual web pages will be cleaner and more readable.
> Plan any PHP code you'll need.

Home page:
- reuse the topnav and header
- query all images
- iterate through images and separate into arrays for each column with as close to even number of images in each
- display each image in the column
- each image has an href to the `work.php` with string parameters in the url with the image id

Single image view:
- if there was a GET request, get the image id from the string parameters
- from that, query all the information about the image
- display using HTML/CSS/PHP

Delete image, tag, or add tag:
- each option in the settings menu links to a modal popup with a form
- select options in the form and click submit, which sends post request to `index.php` or `work.php`
- execute correct query with information input

Insert image:
- check if there was a post request, use form inputs to insert into correct databases

# Complete & Polished Website (Final Submission)

## Gallery Step-by-Step Instructions (Final Submission)
> Write step-by-step instructions for the graders.
> For each set of instructions, assume the grader is starting from index.php.

Viewing all images in your gallery:
1. Scroll down on the home page to view all images
2. Return to full gallery at any time by clicking the page header (Virtual Art Show)

View all images for a tag:
1. Choose a tag in the menu on the left
2. All images for the tag will appear
3. Click the page title to go back to full gallery

View a single image and all the tags for that image:
1. Click on an image in the gallery
2. View image larger with details on the right
3. Click on a link for the tags to go to images with that tag
4. Click the X in the top right to go back to the gallery

How to upload a new image:
1. Click the Submit Artwork tab in the top navigation
2. Upload the file and enter any additional details (all are optional)
3. Add tags by selecting the radio buttons or checkboxes or inputting a new tag
4. Click submit
5. Return to gallery to see the uploaded image

How to delete an image:
1. Click an image to view full details
2. Click the options menu (the three dots on the right side)
3. Choose "Delete image"
4. Confirm by saying "I'm Sure"

How to view all tags at once:
1. All tags are in the filters menu on the left side

How to add a tag to an existing image:
1. Click an image to view full details
2. Click the options menu (the three dots on the right side)
3. Choose "Add tag"
4. To add an existing tag, choose from the select and click Add Tag.
5. To add a new tag, enter the name in the text input (the line).
6. You must select the category for the new tag, either a class name or art medium.
7. Click Add Tag to finish
8. The new tag should be listed in the image details!

How to remove a tag from an existing image:
1. Click an image to view full details
2. Click the options menu (the three dots on the right side)
3. Choose "Remove tags"
4. Check the checkboxes for the tags you want to remove
5. Click "Remove"
6. The tags you chose should be removed from the image details!


## Reflection (Final Submission)
> Take this time to reflect on what you learned during this assignment. How have you improved since starting this class?

This assignment challenged me a lot, since there were a lot of moving parts and backend logic. However, it was really fun and rewarding to create the image gallery, since my idea was something that I actually cared about and could see being an actual website that people use! It was also cool to combine knowledge about forms, HTTP requests, and SQL queries to create the functionality that I wanted. I learned a lot about the HTML/CSS and visual design, and so much about databases and SQL queries!

It was really cool to see it coming together, and I feel like I improved on all aspects of web development.
