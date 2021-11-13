1. Install dependencies

```bash
npm i -D nodemon
npm i koa koa-router koa-body koa-bodyparser koa-views koa-static mysql2 sequelize ejs koa-override
```

2. Design user and recipe model

```mermaid
classDiagram
    class User
    User --> EmailVerification
    User --> UserRole
    User --> LoginHistory
    User "1" --> "*" Recipe
    User --> Role
    User: +Integer id
    User: +String first_name
    User: +String last_name
    User: +String email
    User: -String password
    User: +Bool is_comfirmed
    User: +String avatar
    User: +Bool locked
    User: +Date created_at
    User: +Date updated_at
    User: +Integer role_id
    User: +getRecipes() List~Recipe~
    User: +getLoginHistory() List~LoginHistory~
    User: +sendVerificationEmail()
    User: +LogLoginHistory()
    class EmailVerification {
        +Integer id
        +String url
        +Integer user_id
        +Date expired_at
        +Date created_at
        +Date updated_at
        +verify() Bool
        +generateVerifyLink() String
        +regenerate() String
    }
    class LoginHistory{
        +Integer id
        +String ip
        +String user_agent
        +date login_time
        +integer user_id
        +Date created_at
        +Date updated_at
        +login()
    }

    class Recipe
    Recipe --> Category
    Recipe --> CookingStyle
    Recipe --> Status
    Recipe: +Integer id
    Recipe: +String name
    Recipe: +Bool preserve
    Recipe: +CookingStyle cooking_style
    Recipe: +Category category
    Recipe: +String image
    Recipe: +Text description
    Recipe: +Integer duration
    Recipe: +Status status
    Recipe: +Integer UserId
    Recipe: +Date created_at
    Recipe: +Date updated_at

    class Status {
      <<enumeration>>
      PASS
      REJECTED
      PENDING
    }

    class Category{
      <<enumeration>>
      BREAKFAST
      LUNCH
      DINNER
    }
    class CookingStyle{
      <<enumeration>>
      BROILING
      GRILLING
      ROASTING
      BAKING
      SAUTEING
      POACHING
      SIMMERING
      BOILING
    }

    class Role
    Role --> RolePermission
    Role --> UserRole
    Role: Integer id
    Role: String title
    Role: String slug
    Role: String description
    Role: Bool active
    Role: Date created_at
    Role: Date updated_at


    class UserRole{
      +Integer userId
      +Integer roleId
      +Date created_at
      +Date updated_at
    }

    class Permission
    Permission --> RolePermission
    Permission: Integer id
    Permission: String title
    Permission: String slug
    Permission: String description
    Permission: Bool active
    Permission: Date created_at
    Permission: Date updated_at


    class RolePermission{
      +Integer role_id
      +Integer permission_id
      +Date created_at
      +Date updated_at
    }

    class Comment
    Comment <-- Recipe
    Comment <-- Comment
    Comment <-- User
    Comment: +Integer id
    Comment: +Integer UserId
    Comment: +Integer object_id
    Comment: +String object_type
    Comment: +String comment
    Comment: Date created_at
    Comment: Date updated_at

    class Favourite
    Favourite --o User
    Favourite --o Recipe
    Favourite --o Comment
    Favourite: +Integer id
    Favourite: +Integer UserId
    Favourite: +Integer object_id
    Favourite: +String object_type
    Favourite: +Date created_at
    Favourite: +Date updated_at
```

3. Create a database and assign a new user to it.

```bash
create database nodejs_recipe_template default charset=utf8mb4;
create user 'recipe'@'localhost' identified by 'secret';
grant all privileges on nodejs_recipe_template.* to 'recipe'@'localhost';
flush privileges;
```

4. Initiate sequelize file and generate model files

> [Sequelize DataTypes](https://sequelize.org/master/variable/index.html#static-variable-DataTypes)

```bash
npx sequelize init
npx sequelize model:generate --name Role --attributes title:string,slug:string,description:string,active:boolean
npx sequelize model:generate --name Permission --attributes title:string,slug:string,description:string,active:boolean
npx sequelize migration:generate --name role_and_permission_association
npx sequelize model:generate --name User --attributes first_name:string,last_name:string,email:string,password:string,is_comfirmed:boolean
npx sequelize migration:generate --name user_and_role_association
npx sequelize model:generate --name EmailVerification  --attributes url:string,expired_at:date,UserId:integer
npx sequelize migration:generate --name user_and_role_association
npx sequelize migration:generate --name add_avatar_to_user
npx sequelize model:generate --name Recipe --attributes name:string,preserve:boolean,cooking_style:string,category:string,image:string,description:text,duration:integer,status:string,UserId:integer
npx sequelize model:generate --name Comment --attributes UserId:integer,object_id:integer,object_type:string,comment:string
npx sequelize model:generate --name Favourite --attributes UserId:integer,object_id:integer,object_type:string
npx sequelize seed:generate --name create_random_recipe
npx sequelize seed:generate --name generate-user
npx sequelize db:seed --seed 20211019075939-generate-user
```

https://medium.com/@andrewoons/how-to-define-sequelize-associations-using-migrations-de4333bf75a7

Sequelize associate multiple tables to 1 table with 2 foreign keys

### Make a fold for uploaded images

```
mkdir static/uploads
```

### Enviration

```
.env

PORT=8001

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
```
