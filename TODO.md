# Topic
Prace obsahuje 7 stranek (Hlavní stranka, stranka přispěvku, 
stranka s formou registrace a prihlahování, 
admin panela, editor pro přispěvky). 
Cílem je umožnit lidem dostavat 
zajimavou informace o 
programovaní `napriklad: web designu`
~~a zivotě jiných lidí, které se taky věnují IT~~.
Zaroven, aby spravce a moderatoři mohli 
pracovat v hezkém prostředí.

# Goals:
Main goals of this project.

- Login / Registration
- Create a new post
- Comment to the post only registerd users
- Editor
- Add to saved posts in user (staring post, save to storage)

### Login / registration
On this page user will be login or registered on website.
All data save to database and system create session.
For will be validated.
If user already logined, system propose continue work with this session or log out.

### User roles
- User (Can add comments)
- Moderator (Can remove comments and add posts)
- Author

### Operations with post

#### If user authorized:
- Add / Edit post

#### Unathorized user can:
- See post, download files from the post

### Operations with comment

#### If user authorized, he can:
- Add comment
- Edit them
- Delete selected comment
- Replay to another comment

#### Unauthorized users can:
- See all comments to post

### Editor
In editor user can operate with post. Write text and add photos or files.

## Pages

| Path                            |      Page name       | Description |
|---------------------------------|:--------------------:|:------------|
| /                               |         Home         | -           |
| /login                          |      Login form      |             |
| /signup                         |  Registration form   |             |
| /search                         |        Search        | Search page |
| /articles/:article_id           | Single article page  | -           |
| /flows/:flow_name               | Articles by category | -           |
| /users/:user_id                 |     User account     | -           |
| /admin                          |   Admin dashboard    | -           |
| /admin/create-article           |     Make article     | -           |
| /admin/edit-article/:article_id |     Post editor      | -           |
| /admin/settings                 |  Edit your account   | -           |


## Api

Base route `/api`

| Endpoint                |   Description    |
|:------------------------|:----------------:|
| /posts/:post_id/like    |   Like a post    |
| /posts/:post_id/save    |   Save a post    |
| /posts/:post_id/comment |  Make a comment  |
| /users/:user_id/avatar  |  Change avatar   |
| /users/:user_id/edit    | Change user info |
| /users/:user_id/delete  | Change user info |


# Mysql ORM 

```php
$post = [
    'id' => 1,
    'title' => 'First article',
    'main_paragraph' => 'Lorem ipsum dolor sit amet',
    'date' => date('d.m.y'),
    'user_id' => 1,
    'rating' => 1,
    'count_saved' => 0
]
$user = [
    'id' => 1,
    'first_name' => 'Petr',
    'last_name' => 'Petr',
    'avatar' => 'avatar1.ico'
]
$result = [
    'id' => 1,
    'title' => 'First article',
    'main_paragraph' => 'Lorem ipsum dolor sit amet',
    'date' => date('d.m.y'),
    'user' => [
        'id' => 1,
        'first_name' => 'Petr',
        'last_name' => 'Petr',
        'avatar' => 'avatar1.ico'
    ],
    'rating' => 1,
    'count_saved' => 0
]
```