tbfine(['router', 'jsrender'], function (){

	/* jsonp
 * ====================================================
*/
(function($) {
    $.ajaxTransport('jsonpi', function(opts, originalOptions, jqXHR) {
        var jsonpCallback = opts.jsonpCallback = 
                jQuery.isFunction(opts.jsonpCallback) ? opts.jsonpCallback() : opts.jsonpCallback,
            previous = window[jsonpCallback],
            replace = "$1" + jsonpCallback + "$2",
            url = opts.url;

        if (opts.type == 'GET')
            opts.params[opts.jsonp] = jsonpCallback;
        else
            url += (/\?/.test( url ) ? "&" : "?") + opts.jsonp + "=" + jsonpCallback;

        return {
            send: function(_, completeCallback) {
                var name = 'jQuery_iframe_' + jQuery.now(),
                    iframe, form;

                // Install callback
                window[jsonpCallback] = function(data) {
                    // TODO: How to handle errors? Only 200 for now
                    completeCallback(200, 'success', {
                        'jsonpi': data
                    });

                    iframe.remove();
                    form.remove();

                    window[jsonpCallback] = previous;
                };

                iframe = $('<iframe name="'+name+'">') //ie7 bug fix
                    //.attr('name', name)
                    .appendTo('head');

                form = $('<form>')
                    .attr('method', opts.type) // GET or POST
                    .attr('action', url)
                    .attr('target', name);
                
                $.each(opts.params, function(k, v) {
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', k)
                        .attr('value', v)
                        .appendTo(form);
                });
                form.appendTo('body').submit();
            },
            abort: function() {
                // TODO
            }
       };
    });
})(jQuery);

return {
	init: function (){
		var 
			_iframe = $('#contentframe'),
			_main = $('.user-main'),
			_homepage = 'comments',
			cache_postmenu = null,
			cache_userdata = null,
			cache_orderdata = null,
			cache_coupondata = null,

			rp_post = /^#post\//,
			rp_comment = /^#comment/,
			rp_like = /^#like/,

			ajax_url = jsui.uri+'/action/user.php',

			_msg = {
				// 1-2位：类型；3-4位：01-69指客户端操作提示，71-99指服务端操作提示
				1101: 'Không có dữ liệu trong cột này!',
				1079: 'Máy chủ không bình thường, vui lòng thử lại sau!',

				1201: 'Không có bài viết!',
				1301: 'Chưa có bình luận nào!'
			}

		function is_comment(){
			return rp_comment.test(location.hash) ? true : false
		}


		var routes = {
			'posts/all': function(){
				get_postdata('all', 1)
				$('.user-postmenu a:eq(0)').addClass('active')
			},
			'posts/all/:paged': function(paged){
				get_postdata('all', paged)
				$('.user-postmenu a:eq(0)').addClass('active')
			},

			'posts/publish': function(){
				get_postdata('publish', 1)
				$('.user-postmenu a:eq(1)').addClass('active')
			},
			'posts/publish/:paged': function(paged){
				get_postdata('publish', paged)
				$('.user-postmenu a:eq(1)').addClass('active')
			},

			'posts/future': function(){
				get_postdata('future', 1)
				$('.user-postmenu a:eq(2)').addClass('active')
			},
			'posts/future/:paged': function(paged){
				get_postdata('future', paged)
				$('.user-postmenu a:eq(2)').addClass('active')
			},

			'posts/pending': function(){
				get_postdata('pending', 1)
				$('.user-postmenu a:eq(3)').addClass('active')
			},
			'posts/pending/:paged': function(paged){
				get_postdata('pending', paged)
				$('.user-postmenu a:eq(3)').addClass('active')
			},

			'posts/draft': function(){
				get_postdata('draft', 1)
				$('.user-postmenu a:eq(4)').addClass('active')
			},
			'posts/draft/:paged': function(paged){
				get_postdata('draft', paged)
				$('.user-postmenu a:eq(4)').addClass('active')
			},

			'posts/trash': function(){
				get_postdata('trash', 1)
				$('.user-postmenu a:eq(5)').addClass('active')
			},
			'posts/trash/:paged': function(paged){
				get_postdata('trash', paged)
				$('.user-postmenu a:eq(5)').addClass('active')
			},

			'comments': function(){
				get_commentdata(1)
			},
			'comments/:paged': function(paged){
				get_commentdata(paged)
			},

			'info': function(){
				menuactive('info')

				loading( _main )

				if( !cache_userdata ){
					$.ajax({
						url: ajax_url,
						type: 'POST',
						dataType: 'json',
						data: {
							action: 'info'
						},
						success: function(data, textStatus, xhr) {
							if( data.user ){
								cache_userdata = data.user
								_main.html(
									$('#temp-info').render( data.user )
								)
							}else{
								loading(_main, _msg['1101'])
							}
						},
						error: function(xhr, textStatus, errorThrown) {
							loading(_main, _msg['1079'])
						}
					});

				}else{
					_main.html(
						$('#temp-info').render( cache_userdata )
					)
				}
			},

			'password': function(){
				menuactive('password')

				_main.html(
					$('#temp-password').render()
				)
				
			},

			'post-new': function(){
				menuactive('post-new')

				_main.html(
					$('#temp-postnew').render()
				)

				$('.user-main').hide()
				$('.user-main-postnew').show()
				
			}

		}

		var router = Router(routes);
		router.configure({
			on: function(){
				if( location.hash.indexOf('posts/')<=0 ){
					$('.user-postmenu').remove()
				}
			},
			before: function(){
				$('.user-main').show()
				$('.user-main-postnew').hide()
			},
			notfound: function(){
				location.hash = _homepage
			}
		})
		router.init();

		if( !location.hash ) location.hash = _homepage


		/* 
		 * functions
		 * ====================================================
		*/
	
		function get_postdata(status, paged, callback){
			menuactive('posts')
			$('.user-postmenu a').removeClass()

			loading( _main )

			var datas = {
				action: 'posts',
				status: status,
				paged: paged
			}

			if( !cache_postmenu ){
				datas.first = true
			}

			$.ajax({
				url: ajax_url,
				type: 'POST',
				dataType: 'json',
				data: datas,
				success: function(data, textStatus, xhr) {
					// console.log( data )

					if( !cache_postmenu && data.menus ){
						cache_postmenu = data.menus
					}

					if( (cache_postmenu || (!cache_postmenu && data.menus)) && !$('.user-postmenu').length ){
						_main.before( '<div class="user-postmenu"></div>' )
						$('.user-postmenu').html(
							$('#temp-postmenu').render( cache_postmenu || data.menus )
						)
					}

					if( data.items ){
						_main.html('<ul class="user-postlist"></ul>')
						$('.user-postlist').html(
							$('#temp-postitem').render( data.items )
						).after( paging(data.max, paged, '#posts/'+status+'/') )
						
						thumb_lazyload()
					}else{
						loading(_main, _msg['1201'])
					}

					callback && callback()
				},
				error: function(xhr, textStatus, errorThrown) {
					loading(_main, _msg['1079'])
				}
			});
		}

		function get_commentdata(paged){

			menuactive('comments')
			loading( _main )

			$.ajax({
				url: ajax_url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'comments',
					paged: paged
				},
				success: function(data, textStatus, xhr) {
					// console.log( data )

					if( data.items ){
						_main.html( '<ul class="user-commentlist"></ul>' )
						$('.user-commentlist').html(
							$('#temp-commentitem').render( data.items )
						).after( paging(data.max, paged, '#comments/') )
					}else{
						loading(_main, _msg['1301'])
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					loading(_main, _msg['1079'])
				}
			});
		}

		function menuactive(name){
			$('.usermenus li').removeClass('active')
			$('.usermenu-'+name).addClass('active')
		}

		function loading(el, msg){
			if( !msg ){
				msg = '<i class="fa fa-spinner fa-spin" style="position:relative;top:1px;margin-right:5px;"></i> Đang tải'
			}
			el.html('<div class="user-loading">'+msg+'</div>')
		}

		function thumb_lazyload(){
			tbquire(['lazyload'], function(){
				$('.user-main .thumb').lazyload({
			        data_attribute: 'src',
			        placeholder: jsui.uri + '/img/thumbnail.png',
			        threshold: 400
			    });
		    });
		}


		function paging(max, current, plink, step) {
			var show = 2
			if( !step ) step = 10
		    if ( max <= step ) return;
		    max = Math.ceil(max/step)
		    var html = '<div class="pagination user-pagination"><ul>'

		    if ( !current ) current = 1
		    current = Number(current)
		    if ( current > show + 1 ) html += '<li><a href="'+plink+'1">1</a></li>'
		    if ( current > show + 2 ) html += '<li><span>...</span></li>'
		    for( i = current - show; i <= current + show; i++ ) { 
		        if ( i > 0 && i <= max ){
		        	html += (i == current) ? '<li class="active"><span>'+i+'</span></li>' : '<li><a href="'+plink+i+'">'+i+'</a></li>'
		        }
		    }

		    if ( current < max - show - 1 ) html += '<li><span>...</span></li>'
		    if ( current < max - show ) html += '<li><a href="'+plink+max+'">'+max+'</a></li>'

		    html += '<li><span>Tổng '+max+' trang</span></li>'
		    html += '</ul></div>'

		    return html
		}


		var _tipstimer
		function tips(str){
		    if( !str ) return false
		    _tipstimer && clearTimeout(_tipstimer)
		    $('.user-tips').html(str).animate({
		        top: 0
		    }, 220)
		    _tipstimer = setTimeout(function(){
		        $('.user-tips').animate({
		            top: -30
		        }, 220)
		    }, 5000)
		}



		/* click event
		 * ====================================================
		*/
		$('.container-user').on('click', function(e){
		    e = e || window.event;
		    var target = e.target || e.srcElement
		    var _ta = $(target)

		    if( _ta.parent().attr('evt') ){
		        _ta = $(_ta.parent()[0])
		    }else if( _ta.parent().parent().attr('evt') ){
		        _ta = $(_ta.parent().parent()[0])
		    }

		    var type = _ta.attr('evt')

		    if( !type || _ta.hasClass('disabled') ) return 

		    switch( type ){
		    	
		    	case 'postnew.submit':

		    		var form = _ta.parent().parent().parent()
		            var inputs = form.serializeObject()

		            if( !window.tinyMCE ){
		            	tips('Dữ liệu bất thường');  
			            return
		            }

			        inputs.post_content = tinyMCE.activeEditor.getContent();

		            var title   =  $.trim(inputs.post_title)
			        var url     =  $.trim(inputs.post_url)
			        var content =  $.trim(inputs.post_content)


		            if ( !title || title.length > 50 ) {
			            tips('Tiêu đề không được để trống và ít hơn 50 ký tự');  
			            return
			        }

			        if ( !content || content.length > 10000 || content.length < 10 ) {
			            tips('Nội dung của bài viết không được để trống và từ 10-10000 từ');  
			            return
			        }

			        if ( !url && url.length > 200 ) {
			            tips('Liên kết nguồn không được lớn hơn 200 ký tự');  
			            return
			        }

		    		$.ajax({  
		                type: 'POST',  
		                url:  ajax_url,  
		                data: inputs,  
		                dataType: 'json',
		                success: function(data){  

		                	if( data.msg ){
	                            tips(data.msg)
	                        }

		                    if( data.error ){
		                        return
		                    }

		                    form.find('.form-control').val('')

		                    location.hash = 'posts/draft'
		                }  
		            });  

		    		break;

		        case 'password.submit':
		        	var form = _ta.parent().parent().parent()
		            var inputs = form.serializeObject()

		            if( !inputs.action ){
		                return
		            }

		        	if( !$.trim(inputs.passwordold) ){
	                    tips('Vui lòng nhập mật khẩu ban đầu')
	                    return
	                }

	                if( !inputs.password || inputs.password.length < 6 ){
	                    tips('Mật khẩu mới không được để trống và có ít nhất 6 chữ số')
	                    return
	                }

	                if( inputs.password !== inputs.password2 ){
	                    tips('Hai mục nhập mật khẩu không nhất quán')
	                    return
	                }

	                if( inputs.passwordold === inputs.password ){
	                    tips('Mật khẩu mới và mật khẩu ban đầu không được giống nhau')
	                    return
	                }

		        	$.ajax({  
		                type: 'POST',  
		                url:  ajax_url,  
		                data: inputs,  
		                dataType: 'json',
		                success: function(data){  

		                    if( data.error ){
		                        if( data.msg ){
		                            tips(data.msg)
		                        }
		                        return
		                    }

		                    tips('Đã sửa đổi thành công! Vui lòng sử dụng mật khẩu mới trong lần đăng nhập tiếp theo!')

		                    $('input:password').val('')
		                }  
		            });  

		            break;

		        case 'info.submit':
		            var form = _ta.parent().parent().parent()
		            var inputs = form.serializeObject()

		            if( !inputs.action ){
		                return
		            }

	                if( !/.{2,20}$/.test(inputs.nickname) ){
	                    tips('Biệt hiệu được giới hạn trong 2-20 ký tự')
	                    return
	                }

	                /*if( !inputs.email ){
	                    tips('邮箱不能为空')
	                    return
	                }

	                if( !is_mail(inputs.email) ){
	                    tips('邮箱格式错误')
	                    return
	                }
*/
	                if( inputs.url && (!is_url(inputs.url) || inputs.url.length>100) ){
	                    tips('URL không đúng định dạng')
	                    return
	                }

	                if( inputs.qq && !is_qq(inputs.qq) ){
	                    tips('Lỗi định dạng QQ')
	                    return
	                }

	                if( inputs.weixin && inputs.weixin.length>30 ){
	                    tips('Số lượng ký tự WeChat quá dài, hãy giới hạn ở 30 ký tự')
	                    return
	                }

	                if( inputs.weibo && (!is_url(inputs.weibo) || inputs.weibo.length>100) ){
	                    tips('Lỗi định dạng Weibo')
	                    return
	                }

		            $.ajax({  
		                type: 'POST',  
		                url:  ajax_url,  
		                data: inputs,  
		                dataType: 'json',
		                success: function(data){  

		                    if( data.error ){
		                        if( data.msg ){
		                            tips(data.msg)
		                        }
		                        return
		                    }

		                    tips('Đã sửa đổi thành công!')

		                    cache_userdata = null
		                }  
		            });  

		            break;


		    }
		})
	}
}

})