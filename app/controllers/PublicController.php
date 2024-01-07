<?php

namespace app\controllers;

use app\core\Application;
use app\core\exception\ApplicationException;
use app\core\Router;
use domain\categories\Category;
use domain\exception\UserException;
use domain\posts\Post;
use domain\users\User;
use app\domain\users\UserService;

class PublicController
{
    /**
     * Home page controller
     * @return void
     */
    public static function home(string $page = null): void
    {
        try {
            // Get page
            if (isset($page)) {
                $str_page = htmlspecialchars($page);
                if(preg_match("/page(-?\d+)/", $page, $matches)){
                    $page_num = $matches[1];
                    echo "<script>console.log(\"Page: $page_num\");</script>";
                }
            }
            $post = new Post();
            $post->setTitle("The first post");
            $user = new User();
            $user->setFirstName("Maksym");
            $post->setContent("<p>Test content</p>");
            $posts = [
                $post,
                $post,
                $post,
            ];
            Application::getWebsiteSettings()->setPage('Home');
            require_once $_SERVER["DOCUMENT_ROOT"] . "/views/index.php";
        } catch (ApplicationException $exception){
            throw $exception;
        }
    }

    /**
     * Search controller
     * @return void
     */
    public static function search(): void
    {
        try {
            Application::getWebsiteSettings()->setPage('Search');
        } catch (ApplicationException $exception){
            throw $exception;
        }
    }

    /**
     * Single post controller
     * @param int $post_id
     * @return void
     */
    public static function single(int $post_id): void
    {
        try {
            $post = new Post();
            $post->setTitle("The post");
            $post->setContent("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus deserunt doloremque dolorum ducimus laborum sequi soluta! Accusamus delectus enim exercitationem nam natus necessitatibus nobis nulla perspiciatis provident qui? Error est fuga laborum nam nemo optio? Adipisci atque consequuntur, debitis dignissimos eveniet ipsa maiores maxime, necessitatibus optio perferendis quae quas, quia quod reprehenderit tempora vitae voluptatem. Dolores eos excepturi fugiat fugit magnam minima molestias officiis sit velit? Adipisci asperiores atque consectetur cumque delectus, distinctio doloremque ducimus esse et, excepturi fuga itaque laudantium nemo nobis odit optio perspiciatis quisquam quo quos rerum tempora tempore tenetur ut veniam vitae! Delectus magni quidem voluptas. Asperiores doloribus eius fugiat minima possimus rem temporibus, totam! Deserunt fugit illum magni maiores nemo odio officiis omnis provident vel vitae. Accusantium animi, assumenda doloremque eligendi eos esse exercitationem facilis fugit hic id illum in laboriosam porro provident, qui quisquam, repellat repudiandae similique unde vero? Accusantium amet at aut commodi, consectetur deleniti deserunt dicta dignissimos dolore doloribus eum expedita facere iusto libero modi molestias mollitia nesciunt numquam officiis pariatur placeat reprehenderit, repudiandae saepe sed sequi tenetur totam unde veritatis vitae, voluptatibus. Accusantium animi architecto aspernatur consequatur, debitis earum enim est excepturi laborum maxime minima nostrum obcaecati pariatur placeat quibusdam rerum!");
            $user = new User();
            $user->setId(0);
            $user->setLastName("Zavada");
            $user->setFirstName("Maksym");
            $category = new Category();
            $category->setName("design");
            $category->setDisplayName("Design");
            $post->setCategory($category);
            Application::getWebsiteSettings()->setPage('Single post');
            Application::getMenu("main")->setCurrent($category->getName());
            require_once $_SERVER["DOCUMENT_ROOT"] . "/views/single.php";
        } catch (ApplicationException $exception){
            throw $exception;
        }
    }

    /**
     * Category controller
     * @param string $key
     * @return void
     */
    public static function articlesByCategory(string $key, string $page = null): void
    {
        try {
            $category_key = htmlspecialchars($key);
            // Get page
            if (isset($page)) {
                $str_page = htmlspecialchars($page);
                if(preg_match("/page(-?\d+)/", $page, $matches)){
                    $page_num = $matches[1];
                    echo "<script>console.log(\"Page: $page_num\");</script>";
                }
            }
            if(!Application::getMenu("main")->setCurrent($category_key))
            {
                header("Location: ". Router::link("/", Application::getRouter()->getPrefix()));
                return;
            }
            $current_menu_item = Application::getMenu("main")->getCurrent();
            Application::getWebsiteSettings()->setPage(isset($current_menu_item) ? $current_menu_item->getDisplayName() : "Unknown");
            $post = new Post();
            $post->setTitle("The first post");
            $user = new User();
            $user->setFirstName("Maksym");
            $user->setLastName("Zavada");
            $post->setContent("<p>Test content</p>");
//            $posts = ;
            require_once "../views/index.php";
        } catch (ApplicationException $exception){
            throw $exception;
        } catch (\Exception $e){
            throw $e;
        }
    }

    /**
     * User profile controller
     * @param int $id
     * @return void
     * @throws ApplicationException
     * @throws UserException
     */
    public static function userProfile(int $id): void
    {
        try
        {
//            $service = new UserService();
//
//            $user = $service->getOne($id);
            $user = [
                "id" => 0,
                "first_name" => "Maksym",
                "last_name" => "Zavada",
                "email" => "m.zavada2005@ukr.net",
                "avatar" => "avatar1.ico",
                "role" => "admin"
            ];

            Application::getWebsiteSettings()->setPage("User");

            require_once $_SERVER["CONTEXT_DOCUMENT_ROOT"] . "/views/profile.php";
        }
        catch (ApplicationException $exception)
        {
            if($exception->getCode() == 404)
                require_once $_SERVER["CONTEXT_DOCUMENT_ROOT"] . "/views/404.php";
            else
                throw $exception;
        }

    }
}