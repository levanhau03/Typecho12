<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
?>
<div class="main">
    <div class="container typecho-dashboard">
        <?php include 'page-title.php'; ?>
        <div class="row typecho-page-main">
            <div class="col-mb-12 welcome-board" role="main">
                <p><?php _e('Hiện tại có <em>%s</em> bài viết, <em>%s</em> bình luận về bạn trong <em>%s</em> chuyên mục.',
                        $stat->myPublishedPostsNum, $stat->myPublishedCommentsNum, $stat->categoriesNum); ?>
                    <br><?php _e('Nhấp vào liên kết bên dưới để bắt đầu nhanh chóng:'); ?></p>

                <ul id="start-link" class="clearfix">
                    <?php if ($user->pass('contributor', true)): ?>
                        <li><a href="<?php $options->adminUrl('write-post.php'); ?>"><?php _e('Đăng một bài viết mới'); ?></a></li>
                        <?php if ($user->pass('editor', true) && 'on' == $request->get('__typecho_all_comments') && $stat->waitingCommentsNum > 0): ?>
                            <li>
                                <a href="<?php $options->adminUrl('manage-comments.php?status=waiting'); ?>"><?php _e('Bình luận đang chờ xử lý'); ?></a>
                                <span class="balloon"><?php $stat->waitingCommentsNum(); ?></span>
                            </li>
                        <?php elseif ($stat->myWaitingCommentsNum > 0): ?>
                            <li>
                                <a href="<?php $options->adminUrl('manage-comments.php?status=waiting'); ?>"><?php _e('Đang chờ phê duyệt'); ?></a>
                                <span class="balloon"><?php $stat->myWaitingCommentsNum(); ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($user->pass('editor', true) && 'on' == $request->get('__typecho_all_comments') && $stat->spamCommentsNum > 0): ?>
                            <li>
                                <a href="<?php $options->adminUrl('manage-comments.php?status=spam'); ?>"><?php _e('Bình luận spam'); ?></a>
                                <span class="balloon"><?php $stat->spamCommentsNum(); ?></span>
                            </li>
                        <?php elseif ($stat->mySpamCommentsNum > 0): ?>
                            <li>
                                <a href="<?php $options->adminUrl('manage-comments.php?status=spam'); ?>"><?php _e('Bình luận spam'); ?></a>
                                <span class="balloon"><?php $stat->mySpamCommentsNum(); ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($user->pass('administrator', true)): ?>
                            <li><a href="<?php $options->adminUrl('themes.php'); ?>"><?php _e('Thay đổi ngoại hình'); ?></a></li>
                            <li><a href="<?php $options->adminUrl('plugins.php'); ?>"><?php _e('Quản lý plugin'); ?></a></li>
                            <li><a href="<?php $options->adminUrl('options-general.php'); ?>"><?php _e('Cài đặt hệ thống'); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <!--<li><a href="<?php $options->adminUrl('profile.php'); ?>"><?php _e('Cập nhật hồ sơ của tôi'); ?></a></li>-->
                </ul>
            </div>

            <div class="col-mb-12 col-tb-4" role="complementary">
                <section class="latest-link">
                    <h3><?php _e('Các bài viết đã xuất bản gần đây'); ?></h3>
                    <?php \Widget\Contents\Post\Recent::alloc('pageSize=10')->to($posts); ?>
                    <ul>
                        <?php if ($posts->have()): ?>
                            <?php while ($posts->next()): ?>
                                <li>
                                    <span><?php $posts->date('n.j'); ?></span>
                                    <a href="<?php $posts->permalink(); ?>" class="title"><?php $posts->title(); ?></a>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li><em><?php _e('Không có bài viết nào vào lúc này'); ?></em></li>
                        <?php endif; ?>
                    </ul>
                </section>
            </div>

            <div class="col-mb-12 col-tb-4" role="complementary">
                <section class="latest-link">
                    <h3><?php _e('Câu trả lời gần đây'); ?></h3>
                    <ul>
                        <?php \Widget\Comments\Recent::alloc('pageSize=10')->to($comments); ?>
                        <?php if ($comments->have()): ?>
                            <?php while ($comments->next()): ?>
                                <li>
                                    <span><?php $comments->date('n.j'); ?></span>
                                    <a href="<?php $comments->permalink(); ?>"
                                       class="title"><?php $comments->author(false); ?></a>:
                                    <?php $comments->excerpt(35, '...'); ?>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li><?php _e('Chưa có câu trả lời'); ?></li>
                        <?php endif; ?>
                    </ul>
                </section>
            </div>

            <div class="col-mb-12 col-tb-4" role="complementary">
                <section class="latest-link">
                    <h3><?php _e('Nhật ký mới nhất chính thức'); ?></h3>
                    <div id="typecho-message">
                        <ul>
                            <li><?php _e('Đang tải...'); ?></li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
?>

<script>
    $(document).ready(function () {
        var ul = $('#typecho-message ul'), cache = window.sessionStorage,
            html = cache ? cache.getItem('feed') : '',
            update = cache ? cache.getItem('update') : '';

        if (!!html) {
            ul.html(html);
        } else {
            html = '';
            $.get('<?php $options->index('/action/ajax?do=feed'); ?>', function (o) {
                for (var i = 0; i < o.length; i++) {
                    var item = o[i];
                    html += '<li><span>' + item.date + '</span> <a href="' + item.link + '" target="_blank">' + item.title
                        + '</a></li>';
                }

                ul.html(html);
                cache.setItem('feed', html);
            }, 'json');
        }

        function applyUpdate(update) {
            if (update.available) {
                $('<div class="update-check message error"><p>'
                    + '<?php _e('Phiên bản bạn hiện đang sử dụng %s'); ?>'.replace('%s', update.current) + '<br />'
                    + '<strong><a href="' + update.link + '" target="_blank">'
                    + '<?php _e('Phiên bản chính thức mới nhất là %s'); ?>'.replace('%s', update.latest) + '</a></strong></p></div>')
                    .insertAfter('.typecho-page-title').effect('highlight');
            }
        }

        if (!!update) {
            applyUpdate($.parseJSON(update));
        } else {
            $.get('<?php $options->index('/action/ajax?do=checkVersion'); ?>', function (o, status, resp) {
                applyUpdate(o);
                cache.setItem('update', resp.responseText);
            }, 'json');
        }
    });

</script>
<?php include 'footer.php'; ?>
