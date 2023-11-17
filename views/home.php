<!DOCTYPE html>
<html lang="en">
<?php include_once "common/head.php"; ?>
<body class="bg-solid">
    <?php include_once "common/navbar.php"; ?>
    <div class="flex-container max-w-800 mg-center">
        <main class="articles flex-container flex-col flex-items-center">
            <?php foreach ($posts as $post): ?>
            <article class="post flex-container flex-col" id="<?php echo $post['id']?>">
                <section class="post-header flex-container flex-items-center">
                    <a href="">
                        <img
                            src="../static/user/<?php echo $post['user']['avatar'] ?>"
                            alt="User icon"
                            class="user-avatar"
                        >
                    </a>
                    <div class="flex-container flex-col">
                        <a href="#user" class="link-white">
                            <span>
                                <?php
                                    echo $post['user']['first_name']
                                        . ' '
                                        . $post['user']['last_name']
                                ?>
                            </span>
                        </a>
                        <span class="post-date"><?php echo $post['date']?></span>
                    </div>
                </section>
                <section class="post-body">
                    <a href="#<?php echo $post['id'] ?>" class="link">
                        <h1 class="post-title"><?php echo $post['title'] ?></h1>
                    </a>
                    <div class="post-short-content">
                        <p><?php echo $post['main_paragraph'] ?></p>
                        <a href="#<?php echo $post['id'] ?>" class="link underline">Read more</a>
                    </div>
                </section>
                <section class="post-footer flex-container">
                    <div
                            class="like post-footer-icon flex-container flex-items-center"
                            data-post-id="<?php echo $post['id']; ?>"
                    >
                        <img src="../assets/images/like.svg" alt="like">
                        <span><?php echo $post['rating'] ?></span>
                    </div>
                    <div class="post-footer-icon flex-container flex-items-center">
                        <img src="../assets/images/save.svg" alt="save">
                        <span><?php echo $post['count_saved'] ?></span>
                    </div>
<!--                    <div class="post-footer-icon flex-container flex-items-center">-->
<!--                        <img src="../assets/images/share.svg" alt="like">-->
<!--                        <span>--><?php ////echo $post['rating'] ?><!--</span>-->
<!--                    </div>-->
                </section>
            </article>
            <?php endforeach; ?>
        </main>
<!--        <aside class="sidebar flex-container">-->
<!--            -->
<!--        </aside>-->
    </div>
    <?php include_once "common/footer.php"; ?>
</body>
</html>