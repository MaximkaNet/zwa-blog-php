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

### Joins

Suppose we want to create such a query,

```mysql
SELECT `posts`.`id`,
       `posts`.`title`,
       `posts`.`content`,
       `posts`.`likes`,
       `users`.`id`, -- Nested object
       `users`.`email`,
       `users`.`password`, -- End
FROM `posts` INNER JOIN `users` ON `users`.`id` = `posts`.`user_id`
```

then the implementation in php pseudocode would be as follows:

```php
// Pseudo code
$query = new QueryBuilder()

// Building columns for query
//$keys = User::getPropertyKeys() // Return associative array
$keys = ["id", "name"]
$cols = new ColumnsGenerator($keys)

// Building query
$query->select($cols->generate())
$query->from('posts')
$query->join(
    QueryBuilder::INNER_JOIN, // Join type
    ['users'], // Tables to include
    ['user_id' => 'users.id'] // Relations
)

$query->join(type, tables, relations)
// Join methods:
$query->innerJoin(tables, relations)
$query->leftJoin(tables, relations)
$query->rightJoin(tables, relations)
$query->crossJoin(tables, relations)

// Build a query
$built_query = $query->build()
```

### Columns generator

Generate an array from object

```php
$user_cols = ["id", "email", "pass"];
$category_cols = ["id", "name", "display_name"];
$post_cols = ["id", "title", "content", "author", "category"];

$column_generator = new ColumnsGenerator();
$column_generator->setTable("posts");

// Rule: <overwrite column> => [<nested table> => <nested columns>]
$nested_rule = [
    "author" => ["users" => $user_cols],
    "category" => ["categories" => $category_cols]
];

$column_generator->setRules($nested_rule);

$cols = $column_generator->generateScheme(); // Return array

// Scheme without nested tables

$cols => [
    "id", "title", "content"
]

// Scheme with nested tables

$cols => [
    "id", "display_name", "name"
    // method: setTable
    // It means that the "posts" table is the table that was set using the setTable method.
    "posts" => [
        "id", "title", "content"
    ],
    // method: setRules
    // It means that the "users" table is the table that was set using the setRule method. 
    "users" => [
        "id", "email", "pass"
    ]
    // ...
]

$is_nested = $column_generator->isNested(); // Return true if ColumnGenerator have rules, false if rules is null
```

### Columns building using QueryBuilder

The `buildColumns` private method in `QueryBuilder` class can handle two types of schemas: with and without nested columns. Examples are given below.

#### Query with Nested columns

```php
// Correct
$example_columns = [
    "posts" => [
        "id", // column `id` belong to table `posts`. Will generate `posts`.`id`, it is correct format
        "title",
        "content",
        "likes"
    ],
    // Additional object
    "users" => [
        "id",
        "email",
        "pass",
    ]
];
$query_builder->buildColumns($example_columns);
```

Method `buildColumns` will generate columns like this:

```mysql
SELECT 
    `posts`.`id`,
    `posts`.`title`,
    `posts`.`content`,
    `posts`.`likes`,
    `users`.`id`, -- Additional columns
    `users`.`email`,
    `users`.`pass`
FROM `posts` INNER JOIN `users` ON `users`.`id` = `posts`.`user_id`;
```

#### Simple query

```php
// Also correct, but if columns is not nested
$example_columns = ["id", "title", "content", "likes"];

$query_builder->buildColumns($example_columns);
```

Method `buildColumns` will generate columns like this:

```mysql
SELECT `id`, `title`,`content`,`likes` FROM `posts`;
```