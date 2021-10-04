<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/* 后台设置 */
function themeConfig($form) {
    //header部分
    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, NULL, _t('Địa chỉ LOGO trang web'), _t('Điền vào địa chỉ URL hình ảnh tại đây để thêm LOGO phía trước tiêu đề trang web'));
    $form->addInput($logoUrl->addRule('xssCheck', _t('Vui lòng không sử dụng các ký tự đặc biệt trong liên kết hình ảnh')));

    $icoUrl = new Typecho_Widget_Helper_Form_Element_Text('icoUrl', NULL, NULL, _t('Địa chỉ favicon trang web'), _t('Điền địa chỉ của favicon.ico vào đây, để trống mặc định'));
    $form->addInput($icoUrl->addRule('xssCheck', _t('Vui lòng không sử dụng các ký tự đặc biệt trong liên kết hình ảnh')));

    $logotext = new Typecho_Widget_Helper_Form_Element_Text('logotext', null, NULL, _t('Mô tả trang web'), _t('Hai dòng bên cạnh logo sẽ bao bọc bằng&lt;br&gt;'));
    $form->addInput($logotext);

    $customcss = new Typecho_Widget_Helper_Form_Element_Textarea('customcss', null, NULL, _t('Style tùy chỉnh'), _t('Đã chứa thẻ style'));
    $form->addInput($customcss);

    $DnsPrefetch = new Typecho_Widget_Helper_Form_Element_Radio('DnsPrefetch',
        array('able' => _t('Cho phép'),
            'disable' => _t('Vô hiệu'),
        ),
        'disable', _t('Tăng tốc phân giải trước DNS'), _t('Bị tắt theo mặc định, nếu được bật, tài nguyên CDN và Gravatar sẽ được tăng tốc'));
    $form->addInput($DnsPrefetch);

    $categorymenu = new Typecho_Widget_Helper_Form_Element_Radio('categorymenu',
        array('able' => _t('Gập lại'),
            'disable' => _t('Mở rộng'),
        ),
        'able', _t('Thu gọn menu danh mục bài viết trên trang chủ (nếu bạn cần menu danh mục phụ, hãy chọn mở rộng)'), _t(''));
    $form->addInput($categorymenu);


    $fatext = new Typecho_Widget_Helper_Form_Element_Textarea('fatext', NULL, NULL, _t('Biểu tượng fa của thanh điều hướng trên cùng'), _t('Biểu tượng danh mục fa trong thanh điều hướng trên cùng,&lt;i class="fa fa-plug"&gt;&lt;/i&gt;<br>Để sử dụng, vui lòng tham khảo <a href="http://www.fontawesome.com.cn/faicons/" target="_blank">danh mục CSS biểu tượng FA</a>'));
    $form->addInput($fatext);


    $pagemenu = new Typecho_Widget_Helper_Form_Element_Radio('pagemenu',
        array('able' => _t('Gập lại'),
            'disable' => _t('Mở rộng'),
        ),
        'able', _t('Thu gọn menu trang độc lập của trang chủ'), _t(''));
    $form->addInput($pagemenu);

    $pagefatext = new Typecho_Widget_Helper_Form_Element_Textarea('pagefatext', NULL, NULL, _t('Biểu tượng fa trang độc lập trong thanh điều hướng trên cùng'), _t('Biểu tượng fa trang độc lập trong thanh điều hướng trên cùng,&lt;i class="fa fa-plug"&gt;&lt;/i&gt; <br>Để sử dụng, vui lòng tham khảo <a href="http://www.fontawesome.com.cn/faicons/" target="_blank">danh mục CSS biểu tượng FA</a>'));
    $form->addInput($pagefatext);

    $tuijian = new Typecho_Widget_Helper_Form_Element_Text('tj_cid', NULL, NULL, _t('Màn hình dính'), _t('Vui lòng nhập cid của bài viết sẽ được hiển thị ở trên cùng'));
    $form->addInput($tuijian);

    //首页文章列表上的导航代码
    $smallbanner = new Typecho_Widget_Helper_Form_Element_Textarea('smallbanner', NULL, NULL, _t('Menu nhỏ ở giữa trang chủ'), _t('Chỉ cần điền vào một định dạng liên kết'));
    $form->addInput($smallbanner);

    $indexpic = new Typecho_Widget_Helper_Form_Element_Radio('indexpic',
        array('able' => _t('Cho phép'),
            'disable' => _t('Vô hiệu'),
        ),
        'able', _t('Hình thu nhỏ hiển thị bài viết trên trang chủ'), _t(''));
    $form->addInput($indexpic);

    //幻灯片
    $Slider = new Typecho_Widget_Helper_Form_Element_Radio('Slider',
        array('SliderTrue'=>_t('Bật'),'SliderFalse'=>_t('Tắt')),
        'SliderTrue',
        _t("Slide switch"),
        _t("Sau khi mở, vui lòng điền vào mã slide bên dưới để xuất bản các slide")
        );
    $form->addInput($Slider);

    $slidercode = new Typecho_Widget_Helper_Form_Element_Textarea('slidercode', NULL, NULL, _t('Mã trang trình bày'), _t('Vui lòng điền vào định dạng sau, chỉ cần điền vào một vài dòng<br>&lt;a href="Liên kết của bạn"&gt; &lt;img src="Đường kết nối tới hình ảnh" width="100%" /&gt;&lt;/a&gt;'));
    $form->addInput($slidercode);

    $infpage = new Typecho_Widget_Helper_Form_Element_Radio('infpage',
        array('able' => _t('Cho phép'),
            'disable' => _t('Vô hiệu'),
        ),
        'disable', _t('Tải không giới hạn các bài viết trên trang chủ'), _t(''));
    $form->addInput($infpage);

    //侧边栏
    $sidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox('sidebarBlock',
        array('ShowRecentPosts' => _t('Bài viết mới nhất'),
            'ShowCategory' => _t('Liên kết giới thiệu, vùng quảng cáo'),
            'ShowRecentComments' => _t('Bình luận mới nhất'),
            'ShowTags' => _t('Tag Cloud')),
            array('ShowRecentPosts', 'ShowCategory', 'ShowRecentComments', 'ShowTags'), _t('Màn hình thanh bên')
        );
    $form->addInput($sidebarBlock->multiMode());

    $sidebarAD = new Typecho_Widget_Helper_Form_Element_Textarea('sidebarAD', NULL, NULL, _t('Bit đề xuất của thanh bên có màu đỏ'), _t('Vui lòng điền theo định dạng cố định, nếu không sẽ gây nhầm lẫn, bạn có thể thêm nhiều hơn một dòng đầu tiên là địa chỉ liên kết của quảng cáo, dòng thứ hai là tiêu đề quảng cáo và dòng thứ ba là nội dung quảng cáo<br>Ví dụ: <br>http://themebetter.com/theme/dux<br>Chủ đề DUX chủ đề thế hệ mới<br>Chủ đề DUX Wordpress là chủ đề hiện đang được các front-end lớn sử dụng. Nó được thiết kế bởi front-end lớn với nhiều năm kinh nghiệm về theme WordPress; phong cách phẳng hơn và cấu trúc màu trắng sạch sẽ làm cho trang web xuất hiện đầy đủ và nổi bật...'));
    $form->addInput($sidebarAD);


    $sitebar_fu = new Typecho_Widget_Helper_Form_Element_Text('sitebar_fu', NULL, NULL, _t('Thanh bên nổi'), _t('Vui lòng nhập số sê-ri của các mô-đun thanh bên sẽ được thả nổi và được phân tách bằng dấu phẩy. Ví dụ: 1,3 có nghĩa là thanh bên thứ nhất và thứ 3 sẽ nổi'));
    $form->addInput($sitebar_fu);

    $pagesidebar = new Typecho_Widget_Helper_Form_Element_Radio('pagesidebar',
        array('able' => _t('Cho phép'),
            'disable' => _t('Vô hiệu'),
        ),
        'able', _t('Thanh điều hướng ở bên trái của một trang riêng biệt'), _t('Hiển thị thanh điều hướng ở bên trái của trang độc lập'));
    $form->addInput($pagesidebar);

    //社交

    //图片
    $srcAddress = new Typecho_Widget_Helper_Form_Element_Text('src_add', NULL, NULL, _t('Hình ảnh CDN trước địa chỉ thay thế'), _t('Đó là, các liên kết lưu trữ tệp đính kèm của bạn, thường là http://www.yourblog.com/usr/uploads/'));
    $form->addInput($srcAddress->addRule('xssCheck', _t('Vui lòng không sử dụng các ký tự đặc biệt trong liên kết')));
    $cdnAddress = new Typecho_Widget_Helper_Form_Element_Text('cdn_add', NULL, NULL, _t('Địa chỉ CDN hình ảnh sau khi thay thế'), _t('Đó là, tên miền lưu trữ đám mây Qiniu của bạn, thường là http://yourblog.qiniudn.com/'));
    $form->addInput($cdnAddress->addRule('xssCheck', _t('Vui lòng không sử dụng các ký tự đặc biệt trong liên kết')));
    $default_thumb = new Typecho_Widget_Helper_Form_Element_Text('default_thumb', NULL, '', _t('Hình thu nhỏ mặc định'),_t('Hình thu nhỏ mặc định của bài viết khi không có ảnh, để trống không có gì, thường là http://www.yourblog.com/image.png'));
    $form->addInput($default_thumb->addRule('xssCheck', _t('Vui lòng không sử dụng các ký tự đặc biệt trong liên kết')));

    //作者简介
    $authordesc = new Typecho_Widget_Helper_Form_Element_Text('authordesc', null, NULL, _t('Giới thiệu về tác giả'), _t('Mô tả tác giả của trang bài viết'));
    $form->addInput($authordesc);

    //代码高亮设置
    $useHighline = new Typecho_Widget_Helper_Form_Element_Radio('useHighline',
        array('able' => _t('Cho phép'),
            'disable' => _t('Vô hiệu'),
        ),
        'disable', _t('Cài đặt đánh dấu mã'), _t('Bị tắt theo mặc định, bật đánh dấu mã cho ``` và hỗ trợ đánh dấu cho 20 ngôn ngữ lập trình'));
    $form->addInput($useHighline);

    //footer部分
    $footad = new Typecho_Widget_Helper_Form_Element_Textarea('footad', NULL, NULL, _t('Thanh quảng cáo dưới cùng'), _t('Quảng cáo ở cuối trang, nơi có thể đặt quảng cáo, kiểu bootstrap'));
    $form->addInput($footad);
    $flinks = new Typecho_Widget_Helper_Form_Element_Textarea('flinks', NULL, NULL, _t('Liên kết tình bạn ở phía dưới'), _t('Thanh điều hướng dưới cùng, định dạng li chung, sử dụng các liên kết hữu nghị'));
    $form->addInput($flinks);
    $fcode = new Typecho_Widget_Helper_Form_Element_Textarea('fcode', NULL, NULL, _t('Quảng cáo nhỏ ở dưới cùng'), _t('Khối này được hiển thị ở cuối trang web và phía trên bản quyền Có thể xác định một số liên kết hoặc hình ảnh.'));
    $form->addInput($fcode);
    $miitbeian = new Typecho_Widget_Helper_Form_Element_Text('miitbeian', NULL, NULL, _t('Số giấy phép'), _t('Điền số hồ sơ của bạn, để trống để không hiển thị'));
    $form->addInput($miitbeian);
    $GoogleAnalytics = new Typecho_Widget_Helper_Form_Element_Textarea('GoogleAnalytics', NULL, NULL, _t('Mã thống kê'), _t('Điền vào các mã thống kê theo dõi khác nhau của bạn, tương đương với mã chân trang'));
    $form->addInput($GoogleAnalytics);

}



/**
 * 解析内容以实现附件加速
 * @access public
 * @param string $content 文章正文
 * @param Widget_Abstract_Contents $obj
 */
function parseContent($obj) {
    $options = Typecho_Widget::widget('Widget_Options');
    if (!empty($options->src_add) && !empty($options->cdn_add)) {
        $obj->content = str_ireplace($options->src_add, $options->cdn_add, $obj->content);
    }
    echo trim($obj->content);
}


/*文章阅读次数统计*/
function get_post_view($archive) {
    $cid = $archive->cid;
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();
    if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
        echo 0;
        return;
    }
    $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
    if ($archive->is('single')) {
        $views = Typecho_Cookie::get('extend_contents_views');
        if (empty($views)) {
            $views = array();
        } else {
            $views = explode(',', $views);
        }
        if (!in_array($cid, $views)) {
            $db->query($db->update('table.contents')->rows(array('views' => (int)$row['views'] + 1))->where('cid = ?', $cid));
            array_push($views, $cid);
            $views = implode(',', $views);
            Typecho_Cookie::set('extend_contents_views', $views); //记录查看cookie

        }
    }
    echo $row['views'];
}


/*Typecho 24小时发布文章数量*/
function get_recent_posts_number($days = 1,$display = true){
    $db = Typecho_Db::get();
    $today = time() + 3600 * 8;
    $daysago = $today - ($days * 24 * 60 * 60);
    $total_posts = $db->fetchObject($db->select(array('COUNT(cid)' => 'num'))
        ->from('table.contents')
        ->orWhere('created < ? AND created > ?', $today,$daysago)
        ->where('type = ? AND status = ? AND password IS NULL', 'post', 'publish'))->num;
    if($display) {
        echo $total_posts;
    } else {
        return $total_posts;
    }
}

//缩略图调用
function showThumb($obj,$size=null,$link=false){
    preg_match_all( "/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/", $obj->content, $matches );
    $thumb = '';
    $options = Typecho_Widget::widget('Widget_Options');
    $attach = $obj->attachments(1)->attachment;
    if (isset($attach->isImage) && $attach->isImage == 1){
        $thumb = $attach->url;
        if(!empty($options->src_add) && !empty($options->cdn_add)){
            $thumb = str_ireplace($options->src_add,$options->cdn_add,$thumb);
        }
    }elseif(isset($matches[1][0])){
        $thumb = $matches[1][0];
        if(!empty($options->src_add) && !empty($options->cdn_add)){
            $thumb = str_ireplace($options->src_add,$options->cdn_add,$thumb);
        }
    }
    if(empty($thumb) && empty($options->default_thumb)){
        $thumb= $options->themeUrl .'/img/thumb/' . rand(1, 15) . '.jpg';
        //去掉下面4行双斜杠 启用BING美图随机缩略图
        //$str = file_get_contents('http://cn.bing.com/HPImageArchive.aspx?format=js&idx='.rand(1, 30).'&n=1');
        //$array = json_decode($str);
        //$imgurl = $array->{"images"}[0]->{"urlbase"};
        //$thumb = '//i'.rand(0, 2).'.wp.com/cn.bing.com'.$imgurl.'_1920x1080.jpg?resize=220,150';

        return $thumb;
    }else{
        $thumb = empty($thumb) ? $options->default_thumb : $thumb;
    }
    if($link){
        return $thumb;
    }
}

//编辑推荐
function hotpost() {
    $options = Typecho_Widget::widget('Widget_Options');
    if ((!empty($options->tj_cid)) && floor($options->tj_cid)==$options->tj_cid) {
        $tjids =  $options->tj_cid;
    }else{
        $tjids = 0;
    }
    //return $tjids;
    $defaults = array(
        'cid' => $tjids,
        'before' => '',
        'after' => '',
        'xformat' => '<article class="excerpt-minic excerpt-minic-index"><h2><span class="red">[Top]</span><a href="{permalink}" title="{title}">{title}</a></h2><p class="note">{content}...</p></article>'
    );
    $db = Typecho_Db::get();

    $sql = $db->select()->from('table.contents')
    ->where('status = ?','publish')
    ->where('type = ?', 'post')
    ->where('cid = ?', $defaults['cid']);

    $result = $db->fetchAll($sql);
    echo $defaults['before'];
    foreach($result as $val){
        $val = Typecho_Widget::widget('Widget_Abstract_Contents')->filter($val);
        echo str_replace(array('{permalink}', '{title}','{content}'),array($val['permalink'], $val['title'],substr($val['text'],0,250)), $defaults['xformat']);
    }
    echo $defaults['after'];
}

//幻灯片输出
function slout() {
    $options = Typecho_Widget::widget('Widget_Options');
    if (!empty($options->slidercode)) {
        $text = $options->slidercode;
    }else{
        $text='<a target="_blank" href="https://github.com/hiCasper/Typecho-theme-DUX"><img src="'. $options->themeUrl .'/img/banner.png"></a>
               <a target="_blank" href="https://github.com/hiCasper/Typecho-theme-DUX"><img src="'. $options->themeUrl .'/img/banner.png"></a>';
    }
    $t_arr = explode('
', $text);
    $sss = '<div id="focusslide" class="carousel slide" data-ride="carousel"><ol class="carousel-indicators">';
    foreach($t_arr as $key=>$val) {$sss .= '<li data-target="#focusslide" data-slide-to="'.$key.'"';
    if($key==0){$sss .= ' class="active"></li>';}else{$sss .= '></li>';}}
    $sss .= '</ol><div class="carousel-inner" role="listbox">';
    foreach($t_arr as $key=>$val) {$sss .= '<div class="item';
    if($key==0){$sss .= ' active">';}else{$sss .= '">';} $sss .= $val.'</div>';}
    $sss .= '</div><a class="left carousel-control" href="#focusslide" role="button" data-slide="prev"><i class="fa fa-angle-left"></i></a><a class="right carousel-control" href="#focusslide" role="button" data-slide="next"><i class="fa fa-angle-right"></i></a></div>';

    echo $sss;
}

//导航fa图标
function fa_ico($type, $num) {
    $options = Typecho_Widget::widget('Widget_Options');
    if ($type == 1) {
        if (!empty($options->fatext)) {
            $text = $options->fatext;
            $fa_arr = explode("\n", $text);
            return $fa_arr[$num];
        }
        else {
            $text='';
            return $text;
        }
    }
    else {
        if (!empty($options->pagefatext)) {
            $text = $options->pagefatext;
            $fa_arr = explode("\n", $text);
            return $fa_arr[$num];
        }
        else {
            $text='';
            return $text;
        }
    }
}

//侧边栏推荐位
function sitebar_ad($obj) {
    $options = $obj;
    if (!empty($options)) {
        $text = $options;
    }else{
        $text="https://github.com/hiCasper/Typecho-theme-DUX\nDUX theme\nDUX for Typecho";
    }
    $b_arr = explode("\n", $text);
    return $b_arr;
}

?>
