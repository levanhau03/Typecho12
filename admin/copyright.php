<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>
<div class="typecho-foot" role="contentinfo">
    <div class="copyright">
        <a href="http://typecho.org" class="i-logo-s">Typecho</a>
        <p><?php _e('Được hỗ trợ bởi <a href="http://typecho.org">%s</a>, phiên bản %s', $options->software, $options->version); ?></p>
    </div>
    <nav class="resource">
        <a href="http://docs.typecho.org"><?php _e('Tài liệu'); ?></a> &bull;
        <a href="http://forum.typecho.org"><?php _e('Diễn đàn'); ?></a> &bull;
        <a href="https://github.com/typecho/typecho/issues"><?php _e('Báo lỗi'); ?></a> &bull;
        <a href="http://extends.typecho.org"><?php _e('Tải xuống'); ?></a>
    </nav>
</div>
