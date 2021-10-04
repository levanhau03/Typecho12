<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
    <section class="container">
        <div class="content-wrap">
            <div class="content">
                <header class="article-header">
                    <h1 class="article-title"><a href="<?php $this->permalink(); ?>"><?php $this->title() ?></a></h1>
                    <div class="article-meta">
                        <span class="item"><?php $this->date('Y-m-d'); ?></span>
                        <span class="item">Danh mục: <?php $this->category(' / ');?></span>
                        <span class="item post-views">xem (<?php get_post_view($this) ?>)</span>
                        <span class="item">bình luận (<?php $this->commentsNum('0', '1', '%d'); ?>)</span>
                    </div>
                </header>
                <article class="article-content">
                    <?php parseContent($this); ?>
                </article>

                <div class="post-copyright">Không được sao chép mà không có sự cho phép | Trang hiện tại:<a href="<?php $this->options ->siteUrl(); ?>"><?php $this->options->title();?></a> &raquo; <a href="<?php $this->permalink(); ?>"><?php $this->title() ?></a></div>
                <div class="article-tags">Nhãn: <?php $this->tags(' ', true, '<a>Không nhãn hiệu</a>'); ?></div>
<?php if($this->options->authordesc && !empty($this->options->authordesc) ): ?>
                <div class="article-author">
                    <div class="avatar"><?php $this->author->gravatar('50', 'g'); ?></div><h4><i class="fa fa-user" aria-hidden="true"></i><?php $this->author(); ?></h4>
                    <span><?php $this->options->authordesc(); ?></span>
                </div>
<?php endif; ?>
                <nav class="article-nav">
                    <span class="article-nav-prev">Trước<br><?php $this->thePrev(); ?></span>
                    <span class="article-nav-next">Kế tiếp<br><?php $this->theNext(); ?></span>
                </nav>
<?php $this->related(8,'author')->to($relatedPosts); ?>
<?php if($relatedPosts->have()):?>
                <div class="relates"><div class="title"><h3>Gợi ý liên quan</h3></div>
                    <ul>
<?php while($relatedPosts->next()): ?>
                        <li><a href="<?php $relatedPosts->permalink();?>" title="<?php $relatedPosts->title();?>"><?php $relatedPosts->title();?></a></li>
<?php endwhile; ?>
                    </ul>
                </div>
<?php endif?>

<?php $this->need('comments.php'); ?>
            </div>
        </div>
<?php $this->need('sidebar.php'); ?>
    </section>
<?php $this->need('footer.php'); ?>