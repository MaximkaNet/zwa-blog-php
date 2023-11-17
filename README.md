# Title: 
Personal blog
# Goal:
For users to be able to create and comment on posts, thus sharing information.
# Roles: 
- Unathorized user
- Authorized user
   * User
   * Moderator
   * Admin

# Description

### Functions


### Users functions:
- User
    * Create comments
- Moderator
    * Delete comments
- Admin
   * Add comments
   * Create new posts
   * Edit your posts

### Pages

- Home page
- Post page
- Admin panel
- Editor

## Data structures

Description of data structures in the project

## User

User data structure

| key      | type        | description |
| -------- | ----------- | ----------- |
| id       | int         | -           |
| username | varchar(50) | -           |
| email    | varchar(20) | -           |
| password | varchar(50) | Hash        |

## Post

Post attach to user

| key     | type     | description  |
| ------- | -------- |:------------:|
| id      | int      |      -       |
| date    | datetime |      -       |
| content | TEXT     |      -       |
| user_id | INT      |      -       |

## Comment

Comments attach to post

| key     | type     | description |
| ------- | -------- | ----------- |
| id      | int      | -           |
| user_id | int      | -           |
| post_id | int      | -           |
| content | text     | -           |
| date    | datetime | -           |