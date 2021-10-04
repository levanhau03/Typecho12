<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function themeConfig($form)
{
    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        null,
        _t('Địa chỉ LOGO trang web'),
        _t('Điền vào địa chỉ URL hình ảnh tại đây để thêm LOGO phía trước tiêu đề trang web')
    );

    $form->addInput($logoUrl);

    $sidebarBlock = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'sidebarBlock',
        [
            'ShowRecentPosts'    => _t('Hiển thị các bài viết mới nhất'),
            'ShowRecentComments' => _t('Hiển thị các câu trả lời gần đây'),
            'ShowCategory'       => _t('Hiển thị danh mục'),
            'ShowArchive'        => _t('Hiển thị kho lưu trữ'),
            'ShowOther'          => _t('Hiển thị mục khác')
        ],
        ['ShowRecentPosts', 'ShowRecentComments', 'ShowCategory', 'ShowArchive', 'ShowOther'],
        _t('Màn hình thanh bên')
    );

    $form->addInput($sidebarBlock->multiMode());
}

/*
function themeFields($layout)
{
    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        null,
        _t('站点LOGO地址'),
        _t('在这里填入一个图片URL地址, 以在网站标题前加上一个LOGO')
    );
    $layout->addItem($logoUrl);
}
*/
