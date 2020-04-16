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
- Request: view images with certain tags
  - Type: POST, form



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
- Select image + information/metadata for an image with specific id
```

```

- Select images with certain combination of tags

- Insert image

- Delete image


## Code Planning (Milestone 1)
> Plan what top level PHP pages you'll need.

- Home page: Gallery
- Submit page: add images and details to the gallery
- Single image view: view an image at full size along with details and information about it
- About page: information about the photo gallery

Filters for tags will be implemented in the home page and shown/hidden using JavaScript.

> Plan what partials you'll need.

I plan to use a partial for the header and footer.

I'm not sure if I will need to reuse other code in separate pages, so those may be the only partials.

> Plan any PHP code you'll need.

Example:
```
Put all code in between the sets of backticks: ``` code here ```
```


# Complete & Polished Website (Final Submission)

## Gallery Step-by-Step Instructions (Final Submission)
> Write step-by-step instructions for the graders.
> For each set of instructions, assume the grader is starting from index.php.

Viewing all images in your gallery:
1.
2.

View all images for a tag:
1.
2.

View a single image and all the tags for that image:
1.
2.

How to upload a new image:
1.
2.

How to delete an image:
1.
2.

How to view all tags at once:
1.
2.

How to add a tag to an existing image:
1.
2.

How to remove a tag from an existing image:
1.
2.


## Reflection (Final Submission)
> Take this time to reflect on what you learned during this assignment. How have you improved since starting this class?
