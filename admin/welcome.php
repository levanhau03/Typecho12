<?php
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="main">
    <div class="body container">
        <?php include 'page-title.php'; ?>
        <div class="row typecho-page-main" role="main">
            <div class="col-mb-12">
                <div id="typecho-welcome" class="message">
                    <form action="<?php $options->adminUrl(); ?>" method="get">
                    <h3><?php _e('Bạn có thể sử dụng quản lý "%s": ', $options->title); ?></h3>
                    <ol>
                        <li><a class="operate-delete" href="<?php $options->adminUrl('profile.php#change-password'); ?>"><?php _e('Bạn nên thay đổi mật khẩu mặc định của mình'); ?></a></li>
                        <?php if($user->pass('contributor', true)): ?>
                        <li><a href="<?php $options->adminUrl('write-post.php'); ?>"><?php _e('Viết bài đầu tiên'); ?></a></li>
                        <li><a href="<?php $options->siteUrl(); ?>"><?php _e('Xem trang web của tôi'); ?></a></li>
                        <?php else: ?>
                        <li><a href="<?php $options->siteUrl(); ?>"><?php _e('Xem trang web của tôi'); ?></a></li>
                        <?php endif; ?>
                    </ol>
                    <p><button type="submit" class="btn primary"><?php _e('Hãy để tôi bắt đầu sử dụng nó trực tiếp &raquo;'); ?></button></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
include 'footer.php';
?>
