## Columns provider

### Example

```php
$qb = new QueryBuilder();
$qb->select([
    ["created_at" => "creation_date"],
    "posts" => ["id"],
    "categories" => [
        ["id" => "category_id"],
        ["name" => "category_name"]
    ],
])->from("posts")
    ->innerJoin("posts", "category_id", "categories", "id")
    ->innerJoin("posts", "user_id", "users", "id")
    ->where([
        "id" => 1,
        QueryBuilder::OP_AND,
        "id2" => 1,
        QueryBuilder::OP_AND,
        "posts" => [
            "id" => 1,
            QueryBuilder::OP_AND,
            "title" => "Test"
        ],
    ])
    ->setFirstResults(0)
    ->setMaxResults(10);
echo var_export($qb->getSQL(), true);
```
Generate
```mysql
SELECT `created_at` as `creation_date`,
       `posts`.`id`,
       `categories`.`id` as `category_id`,
       `categories`.`name` as `category_name` 
FROM `posts` INNER JOIN `categories` 
    ON `posts`.`category_id` = `categories`.`id` 
    INNER JOIN `users` 
        ON `posts`.`user_id` = `users`.`id` 
WHERE 
    `id` = :id AND 
    `id2` = :id2 AND 
    `posts`.`id` = :posts_id AND 
    `posts`.`title` = :posts_title 
LIMIT 10 OFFSET 0;
```
### Simple example

We have `invoces` table and columns: `id`, `title`, `price`

In this example, we may not use it, 
but if we need to get the columns in a form: `table`.`column`, 
then we can use the following construct.

```php
$table = "invoices";
$columns = ["id", "title", "price"];
$columns_provider = new ColumnsProvider();
$columns_provider->addTable($table);
$columns_provider->setColumns($columns);

$result = $columns_provider->generate();
```

Result:

```php
[
    "invoices" => [
        "id",
        "title",
        "price"    
    ]
]
```

```mysql
SELECT 
    `posts`.`id` as `post_id`,
    `posts`.`title` as `post_title`,
    `posts`.`content` as `post_content`,
    `posts`.`rating` as `post_rating`,
    `posts`.`count_saved` as `post_count_saved`,
    `posts`.`status` as `post_status`,
    `users`.`id` as `user_id`,
    `users`.`email` as `user_email`,
    `users`.`first_name` as `user_first_name`,
    `users`.`last_name` as `user_last_name`,
    `categories`.`id` as `category_id`,
    `categories`.`name` as `category_name`,
    `categories`.`display_name` as `category_display_name` 
FROM `posts` 
    INNER JOIN (`users`,`categories`) 
        ON (`posts`.`user_id`=`users`.`id` 
                AND `posts`.`category_id`=`categories`.`id`) 
WHERE `posts`.`id`=:posts_id
```

## Multiple tables

### Generating columns with entity name

```php
$columns_provider = new ColumnsProvider();
// Add table
$columns_provider->addTableName("posts");
// Set columns for table
$columns_provider->setColumns([
    "id", "title", "content"
]);
// Set entity name
// This name will be used in aliases
// For example: we have column `id`.
// This `id` column have alise `id` as `post_id`
$columns_provider->setEntityName("post");

// Another entity
$columns_provider->addTableName("categories");
// This is another example.
// We have column `displayName`,
// then we have alise `categories`.`displayName` as `category_displayName`
$columns_provider->setEntityName("category");
$columns_provider->setColumns([
    "id", "name", "displayName"
]);
```

### Generating columns without entity name.

In this example, we will not use the setEntityName method. 
Therefore, the columns will be generated with the table name, 
that is, in this format: `<table name>.<column> as <table name>_<column>`.

For example:

```php
$columns_provider = new ColumnsProvider();
// Add table
$columns_provider->addTableName("posts");
// Set columns for table
$columns_provider->setColumns([
    "id", "title", "content"
]);
// Set entity name
// This name will be used in aliases
// For example: we have column `id`.
// This `id` column have alise `id` as `post_id`
$columns_provider->setEntityName("post");

// Another entity
$columns_provider->addTableName("categories");
// In this example we have column `displayName`,
// then we have alise `categories`.`displayName` as `categories_displayName`
$columns_provider->setColumns([
    "id", "name", 
    ["displayName" => "display_name"] // alise `displayName` as `display_name`
]);

```

### Generate result

Below is an example of the generated columns.
```php

$columns = $columns_provider->generate();

$columns = [
    0 => "id",
    1 => "name",
    2 => ["displayName" => "categories_display_name"] // alias in query 
]

```

### Parse associative result with entity name

Data from database.

In this example we have generated aliases in `<entity name>_<column>` format.

```php
$result_from_database = [
    "post_id" => 0,
    "post_title" => "title",
    /* ... */
    "user_id" => 0,
    "user_first_name" => "foo"
    /* assoc result: <table>_<field> => <value> */
];
```

Parse function

```php
$result = ColumnsProvider::parse($result_from_database);
```

Data before parsing

```php
$result = [
    "post" => [
        "id" => 0,
        "title" => "..."
        /* ... */
    ],
    "user" => [
        "id" => 0,
        "first_name" => "Maks"
        /* ... */
    ],
    "category" => [
        "id" => 0,
        "name" => "dev"
        /* ... */
    ]
];
```

### Parse associative result without entity name

Data from database.

In this example we have generated aliases in `<table name>_<column>` format.

```php
$result_from_database = [
    "posts_id" => 0,
    "posts_title" => "title",
    /* ... */
    "users_id" => 0,
    "users_first_name" => "foo"
    /* assoc result: <table>_<field> => <value> */
];
```

Parse function

```php
$result = ColumnsProvider::parse($result_from_database);
```

Data before parsing

```php
$result = [
    "posts" => [
        "id" => 0,
        "title" => "..."
        /* ... */
    ],
    "users" => [
        "id" => 0,
        "first_name" => "Maks"
        /* ... */
    ],
    "categories" => [
        "id" => 0,
        "name" => "dev"
        /* ... */
    ]
];
```