<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
    <section class="container">
        <div class="f404">
            <img src="<?php $this->options->themeUrl('img/404.png'); ?>">
            <h1>404 Page Not Found</h1>
            <h2>Không tìm thấy những gì bạn đang tìm kiếm!</h2>
            <p>
                <a class="btn btn-primary" href="<?php $this->options ->siteUrl(); ?>">Quay lại trang chủ</a>
            </p>
        </div>
    </section>
<?php $this->need('footer.php'); ?>