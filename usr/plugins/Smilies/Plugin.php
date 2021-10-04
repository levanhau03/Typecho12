<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Thêm biểu tượng cảm xúc (<a href="http://lt21.me">LT21</a>)
 * 
 * @package Smilies
 * @author Hanaka
 * @version 1.1.3
 * @dependence 14.5.26-*
 * @link http://www.yzmb.me/archives/net/smilies-for-typecho
 */
class Smilies_Plugin implements Typecho_Plugin_Interface
{
	/**
	 * 激活插件方法,如果激活失败,直接抛出异常
	 * 
	 * @access public
	 * @return void
	 * @throws Typecho_Plugin_Exception
	 */
	public static function activate()
	{
		Typecho_Plugin::factory('Widget_Abstract_Comments')->contentEx = array('Smilies_Plugin','showsmilies');
		Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('Smilies_Plugin','showsmilies');
		Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('Smilies_Plugin','showsmilies');

		Typecho_Plugin::factory('Widget_Archive')->footer = array('Smilies_Plugin','insertjs');
		Typecho_Plugin::factory('admin/write-post.php')->bottom = array('Smilies_Plugin','smbutton');
		Typecho_Plugin::factory('admin/write-page.php')->bottom = array('Smilies_Plugin','smbutton');

		//模版调用钩子
		Typecho_Plugin::factory('Widget_Comments_Archive')->callSmilies = array('Smilies_Plugin', 'output');

		Helper::addAction('smilies', 'Smilies_Action');
	}

	/**
	 * 禁用插件方法,如果禁用失败,直接抛出异常
	 * 
	 * @static
	 * @access public
	 * @return void
	 * @throws Typecho_Plugin_Exception
	 */
	public static function deactivate()
	{
		Helper::removeAction('smilies');
	}

	/**
	 * 获取插件配置面板
	 * 
	 * @access public
	 * @param Typecho_Widget_Helper_Form $form 配置面板
	 * @return void
	 */
	public static function config(Typecho_Widget_Helper_Form $form)
	{
		$no22 = new Typecho_Widget_Helper_Form_Element_Checkbox('no22',
		array(1=>_t('Không sử dụng nhóm tiêu chuẩn%s(chỉ xuất các mục biểu thức mở rộng bên dưới)%s',' <span style="color:#999;font-size:.92857em;">','</span>')),NULL,'');
		$no22->label->setAttribute('style','font-weight:normal;');
		$form->addInput($no22);

		$replacetxt = new Typecho_Widget_Helper_Form_Element_Checkbox('replacetxt',
		array(1=>_t('Thay thế biểu tượng cảm xúc ký tự%s(hoặc văn bản gây nhiễu)%s',' <span style="color:#999;font-size:.92857em;">','</span>')),NULL,'');
		$replacetxt->label->setAttribute('style','font-weight:normal;');
		$form->addInput($replacetxt);

		$smiliesset = new Typecho_Widget_Helper_Form_Element_Select('smiliesset',
		array('qq'=>_t('Đang quét...')),'qq',_t('Chọn biểu tượng cảm xúc'),_t('Xem trước thư mục biểu tượng cảm xúc trong thư mục plugin để sắp xếp hoặc tùy chỉnh tiện ích mở rộng'));
		$form->addInput($smiliesset);

		$customset = new Typecho_Widget_Helper_Form_Element_Textarea('customset',NULL,'',_t('Nhóm biểu tượng cảm xúc mở rộng'),_t('Điền vào biểu mẫu "mã biểu tượng cảm xúc%sđịa chỉ ảnh biểu tượng cảm xúc<br/>Ví dụ: %scó thể bao gồm phân tích mã chuẩn này','<strong style="color:#467B96;">|</strong>','<font style="color:#467B96;">:smile:|http://image.com/smile.gif</font> '));
		$customset->input->setAttribute('style','max-width:440px;height:180px;');
		$customset->addRule(array(new Smilies_Plugin,'notag'),_t('Không sử dụng biểu tượng <strong><|></strong> làm mã biểu tượng cảm xúc'));
		$form->addInput($customset);

		$omax = new Typecho_Widget_Helper_Form_Element_Text('omax',
		NULL,'21',_t('Giới hạn kích thước biểu tượng cảm xúc'),_t('Đặt chiều rộng tối đa của biểu tượng cảm xúc được hiển thị ở nền trước, đơn vị: px (không cần điền)'));
		$omax->input->setAttribute('class','text-s');
		$omax->input->setAttribute('style','width:40px;');
		$form->addInput($omax->addRule('isFloat'));

		$cmax = new Typecho_Widget_Helper_Form_Element_Text('cmax',NULL,'28','&#8656; '._t('nút gạt | bình luận').' &#8658;');
		$cmax->input->setAttribute('class','text-s');
		$cmax->label->setAttribute('style','position:absolute;color:#999;font-weight:normal;bottom:38px;left:42px;');
		$cmax->input->setAttribute('style','position:absolute;width:40px;bottom:40px;left:186px;');
		$cmax->setAttribute('style','position:relative');
		$form->addInput($cmax->addRule('isFloat'));

		$amax = new Typecho_Widget_Helper_Form_Element_Text('amax',NULL,'32',_t('bài viết').' &#8658;');
		$amax->input->setAttribute('class','text-s');
		$amax->label->setAttribute('style','position:absolute;color:#999;font-weight:normal;bottom:38px;left:230px;');
		$amax->input->setAttribute('style','position:absolute;width:40px;bottom:40px;left:290px;');
		$amax->setAttribute('style','position:relative');
		$form->addInput($amax->addRule('isFloat'));

		$textareaid = new Typecho_Widget_Helper_Form_Element_Text('textareaid',
		NULL,_t('Không cần điền'),_t('Chỉ định ID hộp nhận xét'),_t('Nếu nhận dạng sai, bạn có thể chỉ định thủ công <strong>id</strong> (textarea) tại đây'));
		$textareaid->input->setAttribute('style','width:100px;');
		$form->addInput($textareaid);

		$allowpop = new Typecho_Widget_Helper_Form_Element_Radio('allowpop',
		array(1=>_t('Bật'),0=>_t('Đóng')),0,_t('Hiệu ứng nút bật lên'));
		$form->addInput($allowpop);

		$width = new Typecho_Widget_Helper_Form_Element_Text('width',NULL,'240',_t('Chiều rộng:'));
		$width->input->setAttribute('class','text-s');
		$width->label->setAttribute('style','color:#999;font-weight:normal;');
		$width->input->setAttribute('style','position:absolute;width:45px;top:-4px;left:72px;');
		$width->setAttribute('style','position:relative');
		$form->addInput($width->addRule('isFloat'));

		$radius = new Typecho_Widget_Helper_Form_Element_Text('radius',NULL,'11','px<span style="margin-left:8px;">'._t('Góc tròn:').'</span>');
		$radius->input->setAttribute('class','text-s');
		$radius->label->setAttribute('style','position:absolute;color:#999;font-weight:normal;bottom:7px;left:120px;');
		$radius->input->setAttribute('style','position:absolute;width:40px;bottom:10px;left:200px;');
		$radius->setAttribute('style','position:relative');
		$form->addInput($radius->addRule('isFloat'));

		$bcolor = new Typecho_Widget_Helper_Form_Element_Text('bcolor',NULL,'#bbb','px<span style="margin-left:8px;">'._t('Màu viền:').'</span>');
		$bcolor->input->setAttribute('class','text-s');
		$bcolor->label->setAttribute('style','position:absolute;color:#999;font-weight:normal;bottom:7px;left:241px;');
		$bcolor->input->setAttribute('style','position:absolute;width:75px;bottom:10px;left:325px;');
		$bcolor->setAttribute('style','position:relative');
		$bcolor->addRule(array(new Smilies_Plugin,'colorformat'));
		$form->addInput($bcolor);

		$shadow = new Typecho_Widget_Helper_Form_Element_Select('shadow',
		array(1=>_t('Có'),0=>_t('Không')),1,_t('Bóng đường viền:'),'');
		$shadow->label->setAttribute('style','position:absolute;color:#999;font-weight:normal;bottom:7px;left:405px;');
		$shadow->input->setAttribute('style','position:absolute;bottom:11px;right:170px;');
		$shadow->setAttribute('style','position:relative;');
		$form->addInput($shadow);

		$jqmode = new Typecho_Widget_Helper_Form_Element_Radio('jqmode',
		array(1=>_t('jQuery'),0=>_t('Js bản địa')),0,_t('Chế độ tập lệnh chức năng'),_t('Chỉ có hiệu suất tương thích hơi khác một chút, jQuery tự động đánh giá để tải nguồn CDN'));
		$form->addInput($jqmode);

		$postmode = new Typecho_Widget_Helper_Form_Element_Radio('postmode',
		array(1=>_t('Bật'),0=>_t('Đóng')),0,_t('Biểu tượng cảm xúc được sử dụng trong văn bản'),_t('Khi chỉnh sửa một viết báo hoặc trang, bạn cũng có thể chọn chèn hình ảnh biểu tượng cảm xúc'));
		$form->addInput($postmode);

		//排序保存隐藏域
		$smsort = new Typecho_Widget_Helper_Form_Element_Hidden('smsort',
		NULL,'icon_mrgreen.gif|icon_neutral.gif|icon_twisted.gif|icon_arrow.gif|icon_eek.gif|icon_smile.gif|icon_confused.gif|icon_cool.gif|icon_evil.gif|icon_biggrin.gif|icon_idea.gif|icon_redface.gif|icon_razz.gif|icon_rolleyes.gif|icon_wink.gif|icon_cry.gif|icon_surprised.gif|icon_lol.gif|icon_mad.gif|icon_sad.gif|icon_exclaim.gif|icon_question.gif');
		$form->addInput($smsort);

		$option = Helper::options();
		$security = Helper::security();

//输出面板效果
?>
<link href="<?php $option->pluginUrl('Smilies/custom.css'); ?>" rel="stylesheet"/>
<script src="<?php $option->adminUrl('js/jquery.js'); ?>"></script>
<script src="<?php $option->pluginUrl('Smilies/custom.js'); ?>"></script>
<script>
$(function(){
	//获取文件夹数据
	$.post('<?php $security->index("/action/smilies"); ?>',
		function(datas){
			var data = $.parseJSON(datas),
				opt = $('#smiliesset-0-3'),
				scan = $('.scan'),
				rest = $('#rest'),
				input = $("input[name='smsort']"),
				sortEffect = function(){
					$('div').quberTip();
					//排序结果输入
					var reordered = function($elements){
						var sortid = [];
						$elements.each(function(){
							sortid.push(this.id);
						});
						input.val(sortid.join('|'));
					};
					//gridly挂载回调
					$('.gridly').gridly({
						base: 28,
						gutter: 1,
						columns: 22,
						callbacks: {reordered: reordered}
					});
				};
			opt.html(data['1']);
			scan.html(data['2']);
			rest.html(data['3']);
			sortEffect();
			//菜单切换事件
			opt.bind("change",function(){
				var folder = $(this).val(),
					dorder = data['0'][folder].join('|');
				input.val(dorder);
				//切换重取数据
				$.ajax({
					type:'post',
					url:'<?php $security->index("/action/smilies"); ?>',
					data:{'set':folder},
					beforeSend: function(){
						scan.text('<?php _e("Đang quét..."); ?>');
					},
					success:function(sdatas){
						var sdata = $.parseJSON(sdatas);
						scan.html(sdata['2']);
						rest.html(sdata['3']);
						sortEffect();
					}
				});
			});
		}
	);
	//弹窗选项显隐
	var al = $("#allowpop-1"),
		an = $("#allowpop-0"),
		op = $("#typecho-option-item-width-9, #typecho-option-item-radius-10, #typecho-option-item-bcolor-11, #typecho-option-item-shadow-12");
	if (!al.is(":checked")) op.hide();
	al.click(function(){
		op.show();
	});
	an.click(function(){
		op.hide();
	});
});
</script>
<div style="color:#999;font-size:.92857em;"><p><?php _e('Chèn mã %s vào vị trí thích hợp trong comments.php để hiển thị hộp chọn biểu tượng cảm xúc','<strong style="color:#467B96;">&lt;?php $comments-&gt;smilies(); ?&gt;</strong>'); ?></p></div>
<ul class="typecho-option" id="typecho-option-item-preview">
	<li><label class="typecho-label" for="preview"><?php _e('Sắp xếp nhóm tiêu chuẩn'); ?></label></li>
</ul>
<div class="table">
	<div class="sample">
		<div class="fix" id="0" title=":mrgreen:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_mrgreen.png'); ?>" alt=":mrgreen:"/></div>
		<div class="fix" id="1" title=":neutral:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_neutral.png'); ?>" alt=":neutral:"/></div>
		<div class="fix" id="2" title=":twisted:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_twisted.png'); ?>" alt=":twisted:"/></div>
		<div class="fix" id="3" title=":arrow:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_arrow.png'); ?>" alt=":arrow:"/></div>
		<div class="fix" id="4" title=":shock:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_eek.png'); ?>" alt=":shock:"/></div>
		<div class="fix" id="5" title=":smile:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_smile.png'); ?>" alt=":smile:"/></div>
		<div class="fix" id="6" title=":???:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_confused.png'); ?>" alt=":???:"/></div>
		<div class="fix" id="7" title=":cool:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_cool.png'); ?>" alt=":cool:"/></div>
		<div class="fix" id="8" title=":evil:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_evil.png'); ?>" alt=":evil:"/></div>
		<div class="fix" id="9" title=":grin:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_biggrin.png'); ?>" alt=":grin:"/></div>
		<div class="fix" id="10" title=":idea:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_idea.png'); ?>" alt=":idea:"/></div>
		<div class="fix" id="11" title=":oops:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_redface.png'); ?>" alt=":oops:"/></div>
		<div class="fix" id="12" title=":razz:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_razz.png'); ?>" alt=":razz:"/></div>
		<div class="fix" id="13" title=":roll:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_rolleyes.png'); ?>" alt=":roll:"/></div>
		<div class="fix" id="14" title=":wink:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_wink.png'); ?>" alt=":wink:"/></div>
		<div class="fix" id="15" title=":cry:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_cry.png'); ?>" alt=":cry:"/></div>
		<div class="fix" id="16" title=":eek:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_surprised.png'); ?>" alt=":eek:"/></div>
		<div class="fix" id="17" title=":lol:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_lol.png'); ?>" alt=":lol:"/></div>
		<div class="fix" id="18" title=":mad:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_mad.png'); ?>" alt=":mad:"/></div>
		<div class="fix" id="19" title=":sad:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_sad.png'); ?>" alt=":sad:"/></div>
		<div class="fix" id="20" title=":!:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_exclaim.png'); ?>" alt=":!:"/></div>
		<div class="fix" id="21" title=":?:"><img src="<?php $option->pluginUrl('Smilies/wordpress/icon_question.png'); ?>" alt=":?:"/></div>
	</div>
	<div class="desc"><?php _e('&#8661; Kéo hình biểu tượng cảm xúc bên dưới để phù hợp với kiểu mặc định ở trên &#8661;'); ?></div>
	<div class="scan"><?php _e('Đang quét...'); ?></div>
	<div class="textsm" title="<?php _e('So sánh biểu thức ký tự có thể thay thế'); ?>">
		<div class="fix"></div>
		<div class="fix">:|<br/>:-|</div>
		<div class="fix"></div>
		<div class="fix"></div>
		<div class="fix">8O<br/>8-O</div>
		<div class="fix">:)<br/>:-)</div>
		<div class="fix">:?<br/>:-?</div>
		<div class="fix">8)<br/>8-)</div>
		<div class="fix"></div>
		<div class="fix">:D<br/>:-D</div>
		<div class="fix"></div>
		<div class="fix"></div>
		<div class="fix">:P<br/>:-P</div>
		<div class="fix"></div>
		<div class="fix">;)<br/>;-)</div>
		<div class="fix"></div>
		<div class="fix">:o<br/>:-o</div>
		<div class="fix"></div>
		<div class="fix">:x<br/>:-x</div>
		<div class="fix">:(<br/>:-(</div>
		<div class="fix"></div>
		<div class="fix"></div>
	</div>
</div>
<div id="more"><div id="rest"></div></div>
<?php
	}

	/**
	 * 个人用户的配置面板
	 * 
	 * @access public
	 * @param Typecho_Widget_Helper_Form $form
	 * @return void
	 */
	public static function personalConfig(Typecho_Widget_Helper_Form $form){}

	/**
	 * 整理表情数据
	 * 
	 * @access private
	 * @param boolean $archive 是否为正文
	 * @return array
	 */
	private static function parsesmilies($archive=false)
	{
		$options = Helper::options();
		$settings = $options->plugin('Smilies');
		$omax = $settings->omax;
		$omax = $omax ? 'max-width:'.$omax.'px;' : '';
		$acmax = $archive ? $settings->amax : $settings->cmax;
		$acmax = $acmax ? 'max-width:'.$acmax.'px;' : '';

		//构建标准数组
		$smurl = Typecho_Common::url('Smilies/'.urlencode($settings->smiliesset).'/',$options->pluginUrl);
		$smsort = explode('|',$settings->smsort);
		$smimg = array();
		foreach ($smsort as $imgname) {
			$smimg[] = $smurl.$imgname;
		}
		$pattern = array(':mrgreen:',':neutral:',':twisted:',':arrow:',':shock:',':smile:',':???:',':cool:',':evil:',':grin:',':idea:',':oops:',':razz:',':roll:',':wink:',':cry:',':eek:',':lol:',':mad:',':sad:',':!:',':?:');
		$smtrans = array_combine($pattern,$smimg);

		//并入字符数组
		$textsm = array(
			'8-)'=>$smtrans[':cool:'],
			'8-O'=>$smtrans[':shock:'],
			':-('=>$smtrans[':sad:'],
			':-)'=>$smtrans[':smile:'],
			':-?'=>$smtrans[':???:'],
			':-D'=>$smtrans[':grin:'],
			':-P'=>$smtrans[':razz:'],
			':-o'=>$smtrans[':eek:'],
			':-x'=>$smtrans[':mad:'],
			':-|'=>$smtrans[':neutral:'],
			';-)'=>$smtrans[':wink:'],
			'8)'=>$smtrans[':cool:'],
			'8O'=>$smtrans[':shock:'],
			':('=>$smtrans[':sad:'],
			':)'=>$smtrans[':smile:'],
			':?'=>$smtrans[':???:'],
			':D'=>$smtrans[':grin:'],
			':P'=>$smtrans[':razz:'],
			':o'=>$smtrans[':eek:'],
			':x'=>$smtrans[':mad:'],
			':|'=>$smtrans[':neutral:'],
			';)'=>$smtrans[':wink:'],
		);
		$smtrans = $settings->replacetxt ? $smtrans+$textsm : $smtrans;

		//并入扩展数组
		$customset = trim(Typecho_Common::stripTags($settings->customset));
		$customsm = array();
		if (strpos($customset,'|')) {
			$smsets = array_filter(preg_split("/(\r|\n|\r\n)/",$customset));
			$smarray = array();
			foreach ($smsets as $smset) {
				$smarray[] = explode('|',$smset);
			}
			foreach ($smarray as $row) {
				$customsm[trim($row['0'])] = trim($row['1']);
			}
			$smtrans = array_merge($smtrans,$customsm);
		}

		$smiliesicon1 = array();
		$smiliesicon2 = array();
		$smiliestag = array();
		$smiliesimg = array();

		$smiled = array();
		foreach ($smtrans as $tag=>$grin) {
			$alt = basename($grin);

			//输出表情选项
			if (!in_array($grin,$smiled) && !in_array($tag,array_keys($textsm))) {
				$smiled[] = $grin; //过滤重复值
				$icons = '<span'.($settings->jqmode ? '' : 
					($archive ? ' onclick="Smilies.grin("'.$tag.'");"' : ' onclick="Smilies.grin(\''.$tag.'\');"') //fix js bug
				).' style="cursor:pointer;" data-tag=" '.$tag.' " title="'.$tag.'"><img style="margin:2px;'.$omax.'display:inline-block;" src="'.$grin.'" alt="'.$alt.'"/></span>';

				if (in_array($tag,$pattern)) {
					$smiliesicon1[] = $settings->no22 ? ($customsm ? '' : _t('Biểu tượng cảm xúc mở rộng trống!')) : $icons;
				} else {
					$smiliesicon2[] = $icons;
					$customsm[$tag] = $grin;
				}
			}

			$smiliestag[] = $tag;
			$smiliesimg[] = '<img class="smilies" src="'.$grin.'" alt="'.$alt.'" style="'.$acmax.'display:inline-block;"/>';
		}
		//弹窗模式按钮
		$smilies = empty($customsm) && $settings->no22 ? _t('Biểu tượng cảm xúc mở rộng trống!') : '<img src="'.(empty($customsm[':smile:']) && $settings->no22 ? current($customsm) : $smtrans[':smile:']).'" alt="'._t('Chọn biểu tượng cảm xúc').'" style="'.$omax.'"/>';

		return array($smilies,implode('',array_merge(array_unique($smiliesicon1),$smiliesicon2)),$smiliestag,$smiliesimg);
	}

	/**
	 * 输出编辑器按钮
	 * 
	 * @access public
	 * @return void
	 */
	public static function smbutton()
	{
		if (Helper::options()->plugin('Smilies')->postmode) {
			$smilies = self::parsesmilies(true);
?>
<script>
$(function(){
	var wmd = $('#wmd-image-button'),
		textarea = $('#text');
	if (wmd.length>0) {
		wmd.after(
	'<li class="wmd-button" id="wmd-sm-button" style="padding-top:5px;" title="<?php _e("Chèn biểu tượng cảm xúc"); ?>"><img src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2216%22%20height%3D%2216%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20fill%3D%22%23999%22%20d%3D%22M12%202c5.514%200%2010%204.486%2010%2010s-4.486%2010-10%2010-10-4.486-10-10%204.486-10%2010-10zm0-2c-6.627%200-12%205.373-12%2012s5.373%2012%2012%2012%2012-5.373%2012-12-5.373-12-12-12zm5.507%2013.941c-1.512%201.195-3.174%201.931-5.506%201.931-2.334%200-3.996-.736-5.508-1.931l-.493.493c1.127%201.72%203.2%203.566%206.001%203.566%202.8%200%204.872-1.846%205.999-3.566l-.493-.493zm-9.007-5.941c-.828%200-1.5.671-1.5%201.5s.672%201.5%201.5%201.5%201.5-.671%201.5-1.5-.672-1.5-1.5-1.5zm7%200c-.828%200-1.5.671-1.5%201.5s.672%201.5%201.5%201.5%201.5-.671%201.5-1.5-.672-1.5-1.5-1.5z%22%2F%3E%3C%2Fsvg%3E"/></li>');
	} else {
		$('.url-slug').after('<button type="button" id="wmd-sm-button" class="btn btn-xs" style="margin-right:5px;"><?php _e("Chèn biểu tượng cảm xúc"); ?></button>');
	}
	$('#wmd-sm-button').click(function(){
		$('body').append('<div id="smpanel">' +
		'<div class="wmd-prompt-background" style="position:absolute;z-index:1000;opacity:0.5;top:0px;left:0px;width:100%;height:954px;"></div>' +
		'<div class="wmd-prompt-dialog"><div><p><b><?php _e("Chèn biểu tượng cảm xúc"); ?></b></p>' +
		'<p><?php _e("Vui lòng bấm vào hình biểu tượng cảm xúc mà bạn muốn chèn vào văn bản bên dưới"); ?></p></div>' +
		'<form><?php echo $smilies["1"];?></form></div></div>');
		$("form span").click(function(){
			var sminput = $(this).attr("data-tag"),
				sel = textarea.getSelection(),
				offset = (sel ? sel.start : 0)+sminput.length;
			textarea.replaceSelection(sminput);
			textarea.setSelection(offset,offset);
			$('#smpanel').remove();
		});
	});
	$(document).mouseup(function(e){
		var dialog = $('.wmd-prompt-dialog');
		if (!dialog.is(e.target) && dialog.has(e.target).length === 0){
			$('#smpanel').remove();
		}
	});
});
</script>
<?php
		}
	}

	/**
	 * 解析表情图片
	 * 
	 * @access public
	 * @param string $content 评论内容
	 * @return string
	 */
	public static function showsmilies($content,$widget,$lastResult)
	{
		$content = empty($lastResult) ? $content : $lastResult;

		$options = Helper::options();
		//允许图片标签
		$options->commentsHTMLTagAllowed .= '<img src="" alt="" style=""/>';
		$archive = $widget instanceof Widget_Archive;

		if ($widget instanceof Widget_Abstract_Comments || $archive && $options->plugin('Smilies')->postmode) {
			$arrays = self::parsesmilies($archive);
			$content = str_replace($arrays['2'],$arrays['3'],$content);
		}

		return $content;
	}

	/**
	 * 输出表情选框
	 * 
	 * @access public
	 * @return void
	 */
	public static function output($widget='')
	{
		$options = Helper::options();
		$settings = $options->plugin('Smilies');
		$width = $settings->width;
		$width = $width ? $width : '240';
		$radius = $settings->radius;
		$radius = false!==$radius ? $radius : '11';
		$radius = 'border-radius:'.$radius.'px';
		$radius = '-moz-'.$radius.';-webkit-'.$radius.';-khtml-'.$radius.';'.$radius.';';
		$bcolor = $settings->bcolor;
		$bcolor = $bcolor ? $bcolor : '#bbb';
		$shadow = 'box-shadow:1px 3px 15px '.$bcolor;
		$shadow = $settings->shadow ? '-moz-'.$shadow.';-webkit-'.$shadow.';-khtml-'.$shadow.';'.$shadow.';' : '';

		//弹窗css样式
		$smiliesdisplay = $settings->allowpop
			 ? ' style="display:none;position:absolute;z-index:9999;width:'.$width.'px;margin-top:-70px;padding:5px;background-color:#fff;border:1px solid '.$bcolor.';'.$radius.$shadow.'"'
			 : ' style="display:block;"';

		//罗列表情图标
		$smilies = self::parsesmilies();
		$output = '<div id="smiliesbox"'.$smiliesdisplay.'>';
		$output .= $smilies['1'];
		$output .= '</div>';

		//弹窗风格按钮
		if ($settings->allowpop) {
			$output .= '<span style="cursor:pointer;" id="smiliesbutton" title="'._t('Chọn biểu tượng cảm xúc').'">'.($settings->jqmode ? $smilies['0'] : '<a href="javascript:Smilies.showBox();">'.$smilies['0'].'</a>').'</span>';
		}

		echo $output;
	}

	/**
	 * 输出js脚本
	 * 
	 * @access public
	 * @return void
	 */
	public static function insertjs($widget)
	{
		$options = Helper::options();
		$settings = $options->plugin('Smilies');
		$textareaid = $settings->textareaid;
		$textareaid = $textareaid ? $textareaid : _t('Không cần điền');

		$idset = $widget->is('single') ? $textareaid : 'text';
		$txtid = $settings->jqmode ? '#'.$idset : $idset;
		$txtdom = 'domId("'.$txtid.'")';
		if ($widget->is('single') && $idset==_t('Không cần điền')) {
			$txtid = 'textarea';
			$txtdom = 'domTag("'.$txtid.'")';
		}

		//jquery模式
		if ($settings->jqmode) {
			$auto = '';
			$js = '
<script type="text/javascript">
$(function(){
	var box = $("#smiliesbox");
	$("#smiliesbutton").click(function(){
		box.show();
	});
	$("span",box).click(function(){
		$("'.$txtid.'").insert($(this).attr("data-tag"));';
			if ($settings->allowpop){
				$js .= '
		box.hide();';
				$auto = '
	$(document).mouseup(function(e){
		if (!box.is(e.target) && box.has(e.target).length === 0) {
			box.hide();
		}
	});';
			}
			$js .= '
	});'.$auto.'
	$.fn.extend({
		"insert": function(myValue){
			var $t = $(this)[0];
			if (document.selection) {
				this.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				this.focus()
			} else if ($t.selectionStart || $t.selectionStart=="0") {
				var startPos = $t.selectionStart;
				var endPos = $t.selectionEnd;
				var scrollTop = $t.scrollTop;
				$t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
				this.focus();
				$t.selectionStart = startPos + myValue.length;
				$t.selectionEnd = startPos + myValue.length;
				$t.scrollTop = scrollTop
			} else {
				this.value += myValue;
				this.focus()
			}
		}
	}) 
});
</script>
';
		//js模式
		} else {
			$js = '<script type="text/javascript">
Smilies = {
	domId : function(id){
		return document.getElementById(id);
	},
	domTag : function(id){
		return document.getElementsByTagName(id)[0];
	},
	showBox : function(){
		this.domId("smiliesbox").style.display = "block";
		document.onclick = function(){
			Smilies.closeBox();
		}
	},
	closeBox : function(){
		this.domId("smiliesbox").style.display = "none";
	},
	grin : function(tag){
		tag = \' \' + tag + \' \'; myField = this.'.$txtdom.';
		document.selection ? (myField.focus(),sel = document.selection.createRange(),sel.text = tag,myField.focus()) : this.insertTag(tag);
	},
	insertTag : function(tag){
		myField = Smilies.'.$txtdom.';
		myField.selectionStart || myField.selectionStart=="0" ? (
			startPos = myField.selectionStart,
			endPos = myField.selectionEnd,
			cursorPos = startPos,
			myField.value = myField.value.substring(0,startPos)
				+ tag
				+ myField.value.substring(endPos,myField.value.length),
			cursorPos += tag.length,
			myField.focus(),
			myField.selectionStart = cursorPos,
			myField.selectionEnd = cursorPos
		) : (
			myField.value += tag,
			myField.focus()
		);';
			if ($settings->allowpop) {
				$js .= '
		this.closeBox();';
			}
			$js .= '
	}
}
</script>
';
		}

		if ($widget->is('single')) {
			echo ($settings->jqmode ? '<script type="text/javascript">
window.jQuery || document.write(\'<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"><\/script>\')</script>' : '').$js;
		}
		if ($widget instanceof Widget_Contents_Post_Edit && $settings->postmode) {
			echo $js;
		}

	}

	/**
	 * 检查禁用符号
	 * 
	 * @access public
	 * @param string $input
	 * @return boolean
	 */
	public static function notag($input)
	{
		return !preg_match('/[\>\<]|\|\|/',$input);
	}

	/**
	 * 判断颜色格式
	 * 
	 * @access public
	 * @param string $width
	 * @return boolean
	 */
	public static function colorformat($input)
	{
		return preg_match('/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/',$input);
	}

}