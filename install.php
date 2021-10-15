<?php

if (!file_exists(dirname(__FILE__) . '/config.inc.php')) {
    // site root path
    define('__TYPECHO_ROOT_DIR__', dirname(__FILE__));

    // plugin directory (relative path)
    define('__TYPECHO_PLUGIN_DIR__', '/usr/plugins');

    // theme directory (relative path)
    define('__TYPECHO_THEME_DIR__', '/usr/themes');

    // admin directory (relative path)
    define('__TYPECHO_ADMIN_DIR__', '/admin/');

    // register autoload
    require_once __TYPECHO_ROOT_DIR__ . '/var/Typecho/Common.php';

    // init
    \Typecho\Common::init();
} else {
    require_once dirname(__FILE__) . '/config.inc.php';
    $installDb = \Typecho\Db::get();
}

/**
 * get lang
 *
 * @return string
 */
function install_get_lang(): string
{
    $serverLang = \Typecho\Request::getInstance()->getServer('TYPECHO_LANG');

    if (!empty($serverLang)) {
        return $serverLang;
    } else {
        $lang = 'zh_CN';
        $request = \Typecho\Request::getInstance();

        if ($request->is('lang')) {
            $lang = $request->get('lang');
            \Typecho\Cookie::set('lang', $lang);
        }

        return \Typecho\Cookie::get('lang', $lang);
    }
}

/**
 * get site url
 *
 * @return string
 */
function install_get_site_url(): string
{
    $request = \Typecho\Request::getInstance();
    return install_is_cli() ? $request->getServer('TYPECHO_SITE_URL', 'http://localhost') : $request->getRequestRoot();
}

/**
 * detect cli mode
 *
 * @return bool
 */
function install_is_cli(): bool
{
    return \Typecho\Request::getInstance()->isCli();
}

/**
 * get default router
 *
 * @return string[][]
 */
function install_get_default_routers(): array
{
    return [
        'index'              =>
            [
                'url'    => '/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'archive'            =>
            [
                'url'    => '/blog/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'do'                 =>
            [
                'url'    => '/action/[action:alpha]',
                'widget' => '\Widget\Action',
                'action' => 'action',
            ],
        'post'               =>
            [
                'url'    => '/archives/[cid:digital]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'attachment'         =>
            [
                'url'    => '/attachment/[cid:digital]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'category'           =>
            [
                'url'    => '/category/[slug]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'tag'                =>
            [
                'url'    => '/tag/[slug]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'author'             =>
            [
                'url'    => '/author/[uid:digital]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'search'             =>
            [
                'url'    => '/search/[keywords]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'index_page'         =>
            [
                'url'    => '/page/[page:digital]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'archive_page'       =>
            [
                'url'    => '/blog/page/[page:digital]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'category_page'      =>
            [
                'url'    => '/category/[slug]/[page:digital]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'tag_page'           =>
            [
                'url'    => '/tag/[slug]/[page:digital]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'author_page'        =>
            [
                'url'    => '/author/[uid:digital]/[page:digital]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'search_page'        =>
            [
                'url'    => '/search/[keywords]/[page:digital]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'archive_year'       =>
            [
                'url'    => '/[year:digital:4]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'archive_month'      =>
            [
                'url'    => '/[year:digital:4]/[month:digital:2]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'archive_day'        =>
            [
                'url'    => '/[year:digital:4]/[month:digital:2]/[day:digital:2]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'archive_year_page'  =>
            [
                'url'    => '/[year:digital:4]/page/[page:digital]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'archive_month_page' =>
            [
                'url'    => '/[year:digital:4]/[month:digital:2]/page/[page:digital]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'archive_day_page'   =>
            [
                'url'    => '/[year:digital:4]/[month:digital:2]/[day:digital:2]/page/[page:digital]/',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'comment_page'       =>
            [
                'url'    => '[permalink:string]/comment-page-[commentPage:digital]',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
        'feed'               =>
            [
                'url'    => '/feed[feed:string:0]',
                'widget' => '\Widget\Archive',
                'action' => 'feed',
            ],
        'feedback'           =>
            [
                'url'    => '[permalink:string]/[type:alpha]',
                'widget' => '\Widget\Feedback',
                'action' => 'action',
            ],
        'page'               =>
            [
                'url'    => '/[slug].html',
                'widget' => '\Widget\Archive',
                'action' => 'render',
            ],
    ];
}

/**
 * list all default options
 *
 * @return array
 */
function install_get_default_options(): array
{
    static $options;

    if (empty($options)) {
        $options = [
            'theme' => 'default',
            'theme:default' => 'a:2:{s:7:"logoUrl";N;s:12:"sidebarBlock";a:5:{i:0;s:15:"ShowRecentPosts";i:1;s:18:"ShowRecentComments";i:2;s:12:"ShowCategory";i:3;s:11:"ShowArchive";i:4;s:9:"ShowOther";}}',
            'timezone' => '28800',
            'lang' => install_get_lang(),
            'charset' => 'UTF-8',
            'contentType' => 'text/html',
            'gzip' => 0,
            'generator' => 'Typecho ' . \Typecho\Common::VERSION,
            'title' => 'Hello World',
            'description' => 'Your description here.',
            'keywords' => 'typecho,php,blog',
            'rewrite' => 0,
            'frontPage' => 'recent',
            'frontArchive' => 0,
            'commentsRequireMail' => 1,
            'commentsWhitelist' => 0,
            'commentsRequireURL' => 0,
            'commentsRequireModeration' => 0,
            'plugins' => 'a:0:{}',
            'commentDateFormat' => 'F jS, Y \a\t h:i a',
            'siteUrl' => install_get_site_url(),
            'defaultCategory' => 1,
            'allowRegister' => 0,
            'defaultAllowComment' => 1,
            'defaultAllowPing' => 1,
            'defaultAllowFeed' => 1,
            'pageSize' => 5,
            'postsListSize' => 10,
            'commentsListSize' => 10,
            'commentsHTMLTagAllowed' => null,
            'postDateFormat' => 'Y-m-d',
            'feedFullText' => 1,
            'editorSize' => 350,
            'autoSave' => 0,
            'markdown' => 1,
            'xmlrpcMarkdown' => 0,
            'commentsMaxNestingLevels' => 5,
            'commentsPostTimeout' => 24 * 3600 * 30,
            'commentsUrlNofollow' => 1,
            'commentsShowUrl' => 1,
            'commentsMarkdown' => 0,
            'commentsPageBreak' => 0,
            'commentsThreaded' => 1,
            'commentsPageSize' => 20,
            'commentsPageDisplay' => 'last',
            'commentsOrder' => 'ASC',
            'commentsCheckReferer' => 1,
            'commentsAutoClose' => 0,
            'commentsPostIntervalEnable' => 1,
            'commentsPostInterval' => 60,
            'commentsShowCommentOnly' => 0,
            'commentsAvatar' => 1,
            'commentsAvatarRating' => 'G',
            'commentsAntiSpam' => 1,
            'routingTable' => serialize(install_get_default_routers()),
            'actionTable' => 'a:0:{}',
            'panelTable' => 'a:0:{}',
            'attachmentTypes' => '@image@',
            'secret' => \Typecho\Common::randString(32, true),
            'installed' => 0,
            'allowXmlRpc' => 2
        ];
    }

    return $options;
}

/**
 * get database driver type
 *
 * @param string $driver
 * @return string
 */
function install_get_db_type(string $driver): string
{
    $parts = explode('_', $driver);
    return $driver == 'Mysqli' ? 'Mysql' : array_pop($parts);
}

/**
 * list all available database drivers
 *
 * @return array
 */
function install_get_db_drivers(): array
{
    $drivers = [];

    if (\Typecho\Db\Adapter\Pdo\Mysql::isAvailable()) {
        $drivers['Pdo_Mysql'] = _t('Pdo driver Mysql');
    }

    if (\Typecho\Db\Adapter\Pdo\SQLite::isAvailable()) {
        $drivers['Pdo_SQLite'] = _t('Pdo driver SQLite');
    }

    if (\Typecho\Db\Adapter\Pdo\Pgsql::isAvailable()) {
        $drivers['Pdo_Pgsql'] = _t('Pdo driver PostgreSql');
    }

    if (\Typecho\Db\Adapter\Mysqli::isAvailable()) {
        $drivers['Mysqli'] = _t('Mysql function');
    }

    if (\Typecho\Db\Adapter\SQLite::isAvailable()) {
        $drivers['SQLite'] = _t('SQLite function');
    }

    if (\Typecho\Db\Adapter\Pgsql::isAvailable()) {
        $drivers['Pgsql'] = _t('Pgsql function');
    }

    return $drivers;
}

/**
 * get current db driver
 *
 * @return string
 */
function install_get_current_db_driver(): string
{
    global $installDb;

    if (empty($installDb)) {
        $driver = \Typecho\Request::getInstance()->get('driver');
        $drivers = install_get_db_drivers();

        if (empty($driver) || !isset($drivers[$driver])) {
            return key($drivers);
        }

        return $driver;
    } else {
        return $installDb->getAdapterName();
    }
}

/**
 * generate config file
 *
 * @param string $adapter
 * @param string $dbPrefix
 * @param array $dbConfig
 * @param bool $return
 * @return string
 */
function install_config_file(string $adapter, string $dbPrefix, array $dbConfig, bool $return = false): string
{
    global $configWritten;

    $code = "<" . "?php
// site root path
define('__TYPECHO_ROOT_DIR__', dirname(__FILE__));

// plugin directory (relative path)
define('__TYPECHO_PLUGIN_DIR__', '/usr/plugins');

// theme directory (relative path)
define('__TYPECHO_THEME_DIR__', '/usr/themes');

// admin directory (relative path)
define('__TYPECHO_ADMIN_DIR__', '/admin/');

// register autoload
require_once __TYPECHO_ROOT_DIR__ . '/var/Typecho/Common.php';

// init
\Typecho\Common::init();

// config db
\$db = new \Typecho\Db('{$adapter}', '{$dbPrefix}');
\$db->addServer(" . (var_export($dbConfig, true)) . ", \Typecho\Db::READ | \Typecho\Db::WRITE);
\Typecho\Db::set(\$db);
";

    $configWritten = false;

    if (!$return) {
        $configWritten = @file_put_contents(__TYPECHO_ROOT_DIR__ . '/config.inc.php', $code) !== false;
    }

    return $code;
}

/**
 * remove config file if written
 */
function install_remove_config_file()
{
    global $configWritten;

    if ($configWritten) {
        unlink(__TYPECHO_ROOT_DIR__ . '/config.inc.php');
    }
}

/**
 * check install
 *
 * @param string $type
 * @return bool
 */
function install_check(string $type): bool
{
    switch ($type) {
        case 'config':
            return file_exists(__TYPECHO_ROOT_DIR__ . '/config.inc.php');
        case 'db_structure':
        case 'db_data':
            global $installDb;

            if (empty($installDb)) {
                return false;
            }

            try {
                // check if table exists
                $installed = $installDb->fetchRow($installDb->select()->from('table.options')
                    ->where('user = 0 AND name = ?', 'installed'));

                if ($type == 'db_data' && empty($installed['value'])) {
                    return false;
                }
            } catch (\Typecho\Db\Adapter\ConnectionException $e) {
                return true;
            } catch (\Typecho\Db\Adapter\SQLException $e) {
                return false;
            }

            return true;
        default:
            return false;
    }
}

/**
 * raise install error
 *
 * @param mixed $error
 * @param mixed $config
 */
function install_raise_error($error, $config = null)
{
    if (install_is_cli()) {
        if (is_array($error)) {
            foreach ($error as $key => $value) {
                echo (is_int($key) ? '' : $key . ': ') . $value . "\n";
            }
        } else {
            echo $error . "\n";
        }

        exit(1);
    } else {
        install_throw_json([
            'success' => 0,
            'message' => is_string($error) ? nl2br($error) : $error,
            'config' => $config
        ]);
    }
}

/**
 * @param $step
 * @param array|null $config
 */
function install_success($step, ?array $config = null)
{
    global $installDb;

    if (install_is_cli()) {
        if ($step == 3) {
            \Typecho\Db::set($installDb);
        }

        if ($step > 0) {
            $method = 'install_step_' . $step . '_perform';
            $method();
        }

        if (!empty($config)) {
            [$userName, $userPassword] = $config;
            echo _t('Cài đặt thành công') . "\n";
            echo _t('Tài khoản của bạn là') . " {$userName}\n";
            echo _t('Mật khẩu của bạn là') . " {$userPassword}\n";
        }

        exit(0);
    } else {
        install_throw_json([
            'success' => 1,
            'message' => $step,
            'config'  => $config
        ]);
    }
}

/**
 * @param $data
 */
function install_throw_json($data)
{
    \Typecho\Response::getInstance()->setContentType('application/json')
        ->addResponder(function () use ($data) {
            echo json_encode($data);
        })
        ->respond();
}

/**
 * @param string $url
 */
function install_redirect(string $url)
{
    \Typecho\Response::getInstance()->setStatus(302)
        ->setHeader('Location', $url)
        ->respond();
}

/**
 * add common js support
 */
function install_js_support()
{
    ?>
    <div id="success" class="row typecho-page-main hidden">
        <div class="col-mb-12 col-tb-8 col-tb-offset-2">
            <div class="typecho-page-title">
                <h2><?php _e('Cài đặt thành công'); ?></h2>
            </div>
            <div id="typecho-welcome">
                <p class="keep-word">
                    <?php _e('Bạn đã chọn sử dụng dữ liệu gốc, tên người dùng và mật khẩu của bạn giống với dữ liệu gốc'); ?>
                </p>
                <p class="fresh-word">
                    <?php _e('Tài khoản của bạn là'); ?>: <strong class="warning" id="success-user"></strong><br>
                    <?php _e('Mật khẩu của bạn là'); ?>: <strong class="warning" id="success-password"></strong>
                </p>
                <ul>
                    <li><a id="login-url" href=""><?php _e('Nhấp vào đây để truy cập bảng điều khiển của bạn'); ?></a></li>
                    <li><a id="site-url" href=""><?php _e('Bấm vào đây để xem blog của bạn'); ?></a></li>
                </ul>
                <p><?php _e('Hy vọng bạn có thể tận hưởng niềm vui của Typecho!'); ?></p>
            </div>
        </div>
    </div>
    <script>
        let form = $('form'), errorBox = $('<div></div>');

        errorBox.addClass('message error')
            .prependTo(form);

        function showError(error) {
            if (typeof error == 'string') {
                $(window).scrollTop(0);

                errorBox
                    .html(error)
                    .addClass('fade');
            } else {
                for (let k in error) {
                    let input = $('#' + k), msg = error[k], p = $('<p></p>');

                    p.addClass('message error')
                        .html(msg)
                        .insertAfter(input);

                    input.on('input', function () {
                        p.remove();
                    });
                }
            }

            return errorBox;
        }

        form.submit(function (e) {
            e.preventDefault();

            errorBox.removeClass('fade');
            $('button', form).attr('disabled', 'disabled');
            $('.typecho-option .error', form).remove();

            $.ajax({
                url: form.attr('action'),
                processData: false,
                contentType: false,
                type: 'POST',
                data: new FormData(this),
                success: function (data) {
                    $('button', form).removeAttr('disabled');

                    if (data.success) {
                        if (data.message) {
                            location.href = '?step=' + data.message;
                        } else {
                            let success = $('#success').removeClass('hidden');

                            form.addClass('hidden');

                            if (data.config) {
                                success.addClass('fresh');

                                $('.typecho-page-main:first').addClass('hidden');
                                $('#success-user').html(data.config[0]);
                                $('#success-password').html(data.config[1]);

                                $('#login-url').attr('href', data.config[2]);
                                $('#site-url').attr('href', data.config[3]);
                            } else {
                                success.addClass('keep');
                            }
                        }
                    } else {
                        let el = showError(data.message);

                        if (typeof configError == 'function' && data.config) {
                            configError(form, data.config, el);
                        }
                    }
                },
                error: function (xhr, error) {
                    showError(error)
                }
            });
        });
    </script>
    <?php
}

/**
 * @param string[] $extensions
 * @return string|null
 */
function install_check_extension(array $extensions): ?string
{
    foreach ($extensions as $extension) {
        if (extension_loaded($extension)) {
            return null;
        }
    }

    return _n('Thiếu phần mở rộng PHP', 'Vui lòng cài đặt ít nhất một trong các phần mở rộng PHP sau trên máy chủ', count($extensions))
        . ': ' . implode(', ', $extensions);
}

function install_step_1()
{
    $langs = \Widget\Options\General::getLangs();
    $lang = install_get_lang();
    ?>
    <div class="row typecho-page-main">
        <div class="col-mb-12 col-tb-8 col-tb-offset-2">
            <div class="typecho-page-title">
                <h2><?php _e('Chào mừng đến với Typecho'); ?></h2>
            </div>
            <div id="typecho-welcome">
                <form autocomplete="off" method="post" action="install.php">
                    <h3><?php _e('Ghi chú cài đặt'); ?></h3>
                    <p class="warning">
                        <strong><?php _e('Trình cài đặt này sẽ tự động phát hiện xem môi trường máy chủ có đáp ứng các yêu cầu cấu hình tối thiểu hay không. Nếu không đáp ứng các yêu cầu cấu hình tối thiểu, thông báo nhắc sẽ xuất hiện ở trên cùng, vui lòng làm theo thông tin nhắc để kiểm tra cấu hình máy chủ của bạn.'); ?></strong>
                    </p>
                    <h3><?php _e('Giấy phép và thỏa thuận'); ?></h3>
                    <ul>
                        <li><?php _e('Typecho được phát hành theo thỏa thuận <a href="http://www.gnu.org/copyleft/gpl.html">GPL</a>. Chúng tôi cho phép người dùng sử dụng, sao chép, sửa đổi và phân phối chương trình này trong phạm vi của thỏa thuận GPL.'); ?>
                            <?php _e('Trong phạm vi của giấy phép GPL, bạn có thể tự do sử dụng nó cho các mục đích thương mại và phi thương mại.'); ?></li>
                        <li><?php _e('Phần mềm Typecho được hỗ trợ bởi cộng đồng của nó và nhóm phát triển cốt lõi chịu trách nhiệm phát triển hàng ngày của chương trình bảo trì và xây dựng các tính năng mới.'); ?>
                            <?php _e('Nếu bạn gặp sự cố khi sử dụng, lỗi trong chương trình và các tính năng mới dự kiến, bạn có thể giao tiếp trong cộng đồng hoặc trực tiếp đóng góp mã cho chúng tôi.'); ?>
                            <?php _e('Đối với những người đóng góp xuất sắc, tên của anh ấy sẽ xuất hiện trong danh sách những người đóng góp.'); ?></li>
                    </ul>

                    <p class="submit">
                        <button class="btn primary" type="submit"><?php _e('Tôi đã sẵn sàng, hãy bắt đầu bước tiếp theo &raquo;'); ?></button>
                        <input type="hidden" name="step" value="1">

                        <?php if (count($langs) > 1) : ?>
                            <select style="float: right" onchange="location.href='?lang=' + this.value">
                                <?php foreach ($langs as $key => $val) : ?>
                                    <option value="<?php echo $key; ?>"<?php if ($lang == $key) :
                                        ?> selected<?php
                                                   endif; ?>><?php echo $val; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </p>
                </form>
            </div>
        </div>
    </div>
    <?php
    install_js_support();
}

/**
 * check dependencies before install
 */
function install_step_1_perform()
{
    $errors = [];
    $checks = [
        'mbstring',
        'json',
        'Reflection',
        ['mysqli', 'sqlite3', 'pgsql', 'pdo_mysql', 'pdo_sqlite', 'pdo_pgsql']
    ];

    foreach ($checks as $check) {
        $error = install_check_extension(is_array($check) ? $check : [$check]);

        if (!empty($error)) {
            $errors[] = $error;
        }
    }

    $uploadDir = '/usr/uploads';
    $realUploadDir = \Typecho\Common::url($uploadDir, __TYPECHO_ROOT_DIR__);
    $writeable = true;
    if (is_dir($realUploadDir)) {
        if (!is_writeable($realUploadDir) || !is_readable($realUploadDir)) {
            if (!@chmod($realUploadDir, 0755)) {
                $writeable = false;
            }
        }
    } else {
        if (!@mkdir($realUploadDir, 0755)) {
            $writeable = false;
        }
    }

    if (!$writeable) {
        $errors[] = _t('Không thể ghi thư mục tải lên, vui lòng đặt thủ công quyền cho thư mục %s trong thư mục cài đặt có thể ghi được và tiếp tục nâng cấp', $uploadDir);
    }

    if (empty($errors)) {
        install_success(2);
    } else {
        install_raise_error(implode("\n", $errors));
    }
}

/**
 * display step 2
 */
function install_step_2()
{
    global $installDb;

    $drivers = install_get_db_drivers();
    $adapter = install_get_current_db_driver();
    $type = install_get_db_type($adapter);

    if (!empty($installDb)) {
        $config = $installDb->getConfig(\Typecho\Db::WRITE)->toArray();
        $config['prefix'] = $installDb->getPrefix();
        $config['adapter'] = $adapter;
    }
    ?>
    <div class="row typecho-page-main">
        <div class="col-mb-12 col-tb-8 col-tb-offset-2">
            <div class="typecho-page-title">
                <h2><?php _e('Cấu hình ban đầu'); ?></h2>
            </div>
            <form autocomplete="off" action="install.php" method="post">
                <ul class="typecho-option">
                    <li>
                        <label for="dbAdapter" class="typecho-label"><?php _e('Bộ điều hợp cơ sở dữ liệu'); ?></label>
                        <select name="dbAdapter" id="dbAdapter" onchange="location.href='?step=2&driver=' + this.value">
                            <?php foreach ($drivers as $driver => $name) : ?>
                                <option value="<?php echo $driver; ?>"<?php if ($driver == $adapter) :
                                    ?> selected="selected"<?php
                                               endif; ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e('Vui lòng chọn bộ điều hợp thích hợp theo loại cơ sở dữ liệu của bạn'); ?></p>
                        <input type="hidden" id="dbNext" name="dbNext" value="none">
                    </li>
                </ul>
                <ul class="typecho-option">
                    <li>
                        <label class="typecho-label" for="dbPrefix"><?php _e('Tiền tố cơ sở dữ liệu'); ?></label>
                        <input type="text" class="text" name="dbPrefix" id="dbPrefix" value="typecho_" />
                        <p class="description"><?php _e('Tiền tố mặc định là "typecho_"'); ?></p>
                    </li>
                </ul>
                <?php require_once './install/' . $type . '.php'; ?>


                <ul class="typecho-option typecho-option-submit">
                    <li>
                        <button id="confirm" type="submit" class="btn primary"><?php _e('Xác nhận, bắt đầu cài đặ &raquo;'); ?></button>
                        <input type="hidden" name="step" value="2">
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <script>
        function configError(form, config, errorBox) {
            let next = $('#dbNext'),
                line = $('<p></p>');

            if (config.code) {
                let text = $('<textarea></textarea>'),
                    btn = $('<button></button>');

                btn.html('<?php _e('Đã tạo, tiếp tục cài đặt &raquo;'); ?>')
                    .attr('type', 'button')
                    .addClass('btn btn-s primary');

                btn.click(function () {
                    next.val('config');
                    form.trigger('submit');
                });

                text.val(config.code)
                    .addClass('mono')
                    .attr('readonly', 'readonly');

                errorBox.append(text)
                    .append(btn);
                return;
            }

            errorBox.append(line);

            for (let key in config) {
                let word = config[key],
                    btn = $('<button></button>');

                btn.html(word)
                    .attr('type', 'button')
                    .addClass('btn btn-s primary')
                    .click(function () {
                        next.val(key);
                        form.trigger('submit');
                    });

                line.append(btn);
            }
        }

        $('#confirm').click(function () {
            $('#dbNext').val('none');
        });

        <?php if (!empty($config)) : ?>
        function fillInput(config) {
            for (let k in config) {
                let value = config[k],
                    key = 'db' + k.charAt(0).toUpperCase() + k.slice(1),
                    input = $('#' + key)
                        .attr('readonly', 'readonly')
                        .val(value);

                $('option:not(:selected)', input).attr('disabled', 'disabled');
            }
        }

        fillInput(<?php echo json_encode($config); ?>);
        <?php endif; ?>
    </script>
    <?php
    install_js_support();
}

/**
 * perform install step 2
 */
function install_step_2_perform()
{
    global $installDb;

    $request = \Typecho\Request::getInstance();
    $drivers = install_get_db_drivers();

    $configMap = [
        'Mysql' => [
            'dbHost' => 'localhost',
            'dbPort' => 3306,
            'dbUser' => null,
            'dbPassword' => null,
            'dbCharset' => 'utf8mb4',
            'dbDatabase' => null,
            'dbEngine' => 'InnoDB'
        ],
        'Pgsql' => [
            'dbHost' => 'localhost',
            'dbPort' => 5432,
            'dbUser' => null,
            'dbPassword' => null,
            'dbCharset' => 'utf8',
            'dbDatabase' => null,
        ],
        'SQLite' => [
            'dbFile' => __TYPECHO_ROOT_DIR__ . '/usr/' . uniqid() . '.db'
        ]
    ];

    if (install_is_cli()) {
        $config = [
            'dbHost' => $request->getServer('TYPECHO_DB_HOST'),
            'dbUser' => $request->getServer('TYPECHO_DB_USER'),
            'dbPassword' => $request->getServer('TYPECHO_DB_PASSWORD'),
            'dbCharset' => $request->getServer('TYPECHO_DB_CHARSET'),
            'dbPort' => $request->getServer('TYPECHO_DB_PORT'),
            'dbDatabase' => $request->getServer('TYPECHO_DB_DATABASE'),
            'dbFile' => $request->getServer('TYPECHO_DB_FILE'),
            'dbDsn' => $request->getServer('TYPECHO_DB_DSN'),
            'dbEngine' => $request->getServer('TYPECHO_DB_ENGINE'),
            'dbPrefix' => $request->getServer('TYPECHO_DB_PREFIX', 'typecho_'),
            'dbAdapter' => $request->getServer('TYPECHO_DB_ADAPTER', install_get_current_db_driver()),
            'dbNext' => $request->getServer('TYPECHO_DB_NEXT', 'none')
        ];
    } else {
        $config = $request->from([
            'dbHost',
            'dbUser',
            'dbPassword',
            'dbCharset',
            'dbPort',
            'dbDatabase',
            'dbFile',
            'dbDsn',
            'dbEngine',
            'dbPrefix',
            'dbAdapter',
            'dbNext'
        ]);
    }

    $error = (new \Typecho\Validate())
        ->addRule('dbPrefix', 'required', _t('Xác nhận cấu hình của bạn'))
        ->addRule('dbPrefix', 'minLength', _t('Xác nhận cấu hình của bạn'), 1)
        ->addRule('dbPrefix', 'maxLength', _t('Xác nhận cấu hình của bạn'), 16)
        ->addRule('dbPrefix', 'alphaDash', _t('Xác nhận cấu hình của bạn'))
        ->addRule('dbAdapter', 'required', _t('Xác nhận cấu hình của bạn'))
        ->addRule('dbAdapter', 'enum', _t('Xác nhận cấu hình của bạn'), array_keys($drivers))
        ->addRule('dbNext', 'required', _t('Xác nhận cấu hình của bạn'))
        ->addRule('dbNext', 'enum', _t('Xác nhận cấu hình của bạn'), ['none', 'delete', 'keep', 'config'])
        ->run($config);

    if (!empty($error)) {
        install_raise_error($error);
    }

    $type = install_get_db_type($config['dbAdapter']);
    $dbConfig = [];

    foreach ($configMap[$type] as $key => $value) {
        $config[$key] = !isset($config[$key]) ? (install_is_cli() ? $value : null) : $config[$key];
    }

    switch ($type) {
        case 'Mysql':
            $error = (new \Typecho\Validate())
                ->addRule('dbHost', 'required', _t('Xác nhận cấu hình của bạn'))
                ->addRule('dbPort', 'required', _t('Xác nhận cấu hình của bạn'))
                ->addRule('dbPort', 'isInteger', _t('Xác nhận cấu hình của bạn'))
                ->addRule('dbUser', 'required', _t('Xác nhận cấu hình của bạn'))
                ->addRule('dbCharset', 'required', _t('Xác nhận cấu hình của bạn'))
                ->addRule('dbCharset', 'enum', _t('Xác nhận cấu hình của bạn'), ['utf8', 'utf8mb4'])
                ->addRule('dbDatabase', 'required', _t('Xác nhận cấu hình của bạn'))
                ->addRule('dbEngine', 'required', _t('Xác nhận cấu hình của bạn'))
                ->addRule('dbEngine', 'enum', _t('Xác nhận cấu hình của bạn'), ['InnoDB', 'MyISAM'])
                ->run($config);
            break;
        case 'Pgsql':
            $error = (new \Typecho\Validate())
                ->addRule('dbHost', 'required', _t('Xác nhận cấu hình của bạn'))
                ->addRule('dbPort', 'required', _t('Xác nhận cấu hình của bạn'))
                ->addRule('dbPort', 'isInteger', _t('Xác nhận cấu hình của bạn'))
                ->addRule('dbUser', 'required', _t('Xác nhận cấu hình của bạn'))
                ->addRule('dbCharset', 'required', _t('Xác nhận cấu hình của bạn'))
                ->addRule('dbCharset', 'enum', _t('Xác nhận cấu hình của bạn'), ['utf8'])
                ->addRule('dbDatabase', 'required', _t('Xác nhận cấu hình của bạn'))
                ->run($config);
            break;
        case 'SQLite':
            $error = (new \Typecho\Validate())
                ->addRule('dbFile', 'required', _t('Xác nhận cấu hình của bạn'))
                ->run($config);
            break;
        default:
            install_raise_error(_t('Xác nhận cấu hình của bạn'));
            break;
    }

    if (!empty($error)) {
        install_raise_error($error);
    }

    foreach ($configMap[$type] as $key => $value) {
        $dbConfig[strtolower(substr($key, 2))] = $config[$key];
    }

    // intval port number
    if (isset($dbConfig['port'])) {
        $dbConfig['port'] = intval($dbConfig['port']);
    }

    // check config file
    if ($config['dbNext'] == 'config' && !install_check('config')) {
        $code = install_config_file($config['dbAdapter'], $config['dbPrefix'], $dbConfig, true);
        install_raise_error(_t('Tệp cấu hình bạn đã tạo theo cách thủ công không được phát hiện, vui lòng kiểm tra và tạo lại'), ['code' => $code]);
    } elseif (empty($installDb)) {
        // detect db config
        try {
            $installDb = new \Typecho\Db($config['dbAdapter'], $config['dbPrefix']);
            $installDb->addServer($dbConfig, \Typecho\Db::READ | \Typecho\Db::WRITE);
            $installDb->query('SELECT 1=1');
        } catch (\Typecho\Db\Adapter_Exception $e) {
            install_raise_error(_t('Xin lỗi, không thể kết nối với cơ sở dữ liệu, vui lòng kiểm tra cấu hình cơ sở dữ liệu trước khi tiến hành cài đặt'));
        } catch (\Typecho\Db\Exception $e) {
            install_raise_error(_t('Trình cài đặt gặp lỗi sau: " %s ". Chương trình đã bị chấm dứt, vui lòng kiểm tra thông tin cấu hình của bạn.', $e->getMessage()));
        }

        $code = install_config_file($config['dbAdapter'], $config['dbPrefix'], $dbConfig);

        if (!install_check('config')) {
            install_raise_error(
                _t('Trình cài đặt không thể tự động tạo tệp <strong>config.inc.php</strong>'). "\n" .
                _t('Bạn có thể tạo thủ công tệp <strong>config.inc.php</strong> trong thư mục gốc của trang web và sao chép mã sau vào đó'),
                [
                'code' => $code
                ]
            );
        }
    }

    // delete exists db
    if ($config['dbNext'] == 'delete') {
        $tables = [
            $config['dbPrefix'] . 'comments',
            $config['dbPrefix'] . 'contents',
            $config['dbPrefix'] . 'fields',
            $config['dbPrefix'] . 'metas',
            $config['dbPrefix'] . 'options',
            $config['dbPrefix'] . 'relationships',
            $config['dbPrefix'] . 'users'
        ];

        try {
            foreach ($tables as $table) {
                if ($type == 'Mysql') {
                    $installDb->query("DROP TABLE IF EXISTS `{$table}`");
                } elseif ($type == 'Pgsql') {
                    $installDb->query("DROP TABLE {$table}");
                } elseif ($type == 'SQLite') {
                    $installDb->query("DROP TABLE {$table}");
                }
            }
        } catch (\Typecho\Db\Exception $e) {
            install_raise_error(_t('Trình cài đặt gặp lỗi sau: "%s". Chương trình đã bị chấm dứt, vui lòng kiểm tra thông tin cấu hình của bạn.', $e->getMessage()));
        }
    }

    // init db structure
    try {
        $scripts = file_get_contents(__TYPECHO_ROOT_DIR__ . '/install/' . $type . '.sql');
        $scripts = str_replace('typecho_', $config['dbPrefix'], $scripts);

        if (isset($dbConfig['charset'])) {
            $scripts = str_replace('%charset%', $dbConfig['charset'], $scripts);
        }

        if (isset($dbConfig['engine'])) {
            $scripts = str_replace('%engine%', $dbConfig['engine'], $scripts);
        }

        $scripts = explode(';', $scripts);
        foreach ($scripts as $script) {
            $script = trim($script);
            if ($script) {
                $installDb->query($script, \Typecho\Db::WRITE);
            }
        }
    } catch (\Typecho\Db\Exception $e) {
        $code = $e->getCode();

        if (
            ('Mysql' == $type && (1050 == $code || '42S01' == $code)) ||
            ('SQLite' == $type && ('HY000' == $code || 1 == $code)) ||
            ('Pgsql' == $type && '42P07' == $code)
        ) {
            if ($config['dbNext'] == 'keep') {
                if (install_check('db_data')) {
                    install_success(0);
                } else {
                    install_success(3);
                }
            } elseif ($config['dbNext'] == 'none') {
                install_remove_config_file();

                install_raise_error(_t('Trình cài đặt kiểm tra xem bảng dữ liệu gốc đã tồn tại chưa.'), [
                    'delete' => _t('Xóa dữ liệu gốc'),
                    'keep' => _t('Sử dụng dữ liệu gốc')
                ]);
            }
        } else {
            install_remove_config_file();

            install_raise_error(_t('Trình cài đặt gặp lỗi sau: "%s". Chương trình đã bị chấm dứt, vui lòng kiểm tra thông tin cấu hình của bạn.', $e->getMessage()));
        }
    }

    install_success(3);
}

/**
 * display step 3
 */
function install_step_3()
{
    $options = \Widget\Options::alloc();
    ?>
    <div class="row typecho-page-main">
        <div class="col-mb-12 col-tb-8 col-tb-offset-2">
            <div class="typecho-page-title">
                <h2><?php _e('Tạo tài khoản quản trị của bạn'); ?></h2>
            </div>
            <form autocomplete="off" action="install.php" method="post">
                <ul class="typecho-option">
                    <li>
                        <label class="typecho-label" for="userUrl"><?php _e('Địa chỉ trang web'); ?></label>
                        <input autocomplete="new-password" type="text" name="userUrl" id="userUrl" class="text" value="<?php $options->siteUrl(); ?>" />
                        <p class="description"><?php _e('Đây là đường dẫn trang web được chương trình tự động so khớp, vui lòng sửa đổi nếu nó không chính xác'); ?></p>
                    </li>
                </ul>
                <ul class="typecho-option">
                    <li>
                        <label class="typecho-label" for="userName"><?php _e('Tên tài khoản'); ?></label>
                        <input autocomplete="new-password" type="text" name="userName" id="userName" class="text" />
                        <p class="description"><?php _e('Vui lòng điền tên người dùng của bạn'); ?></p>
                    </li>
                </ul>
                <ul class="typecho-option">
                    <li>
                        <label class="typecho-label" for="userPassword"><?php _e('Mật khẩu đăng nhập'); ?></label>
                        <input type="password" name="userPassword" id="userPassword" class="text" />
                        <p class="description"><?php _e('Vui lòng điền mật khẩu đăng nhập của bạn, nếu bạn để trống hệ thống sẽ tạo ngẫu nhiên cho bạn'); ?></p>
                    </li>
                </ul>
                <ul class="typecho-option">
                    <li>
                        <label class="typecho-label" for="userMail"><?php _e('Địa chỉ thư điện tử'); ?></label>
                        <input autocomplete="new-password" type="text" name="userMail" id="userMail" class="text" />
                        <p class="description"><?php _e('Vui lòng điền vào một địa chỉ email chung'); ?></p>
                    </li>
                </ul>
                <ul class="typecho-option typecho-option-submit">
                    <li>
                        <button type="submit" class="btn primary"><?php _e('Tiếp tục cài đặt &raquo;'); ?></button>
                        <input type="hidden" name="step" value="3">
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php
    install_js_support();
}

/**
 * perform step 3
 */
function install_step_3_perform()
{
    global $installDb;

    $request = \Typecho\Request::getInstance();
    $defaultPassword = \Typecho\Common::randString(8);
    $options = \Widget\Options::alloc();

    if (install_is_cli()) {
        $config = [
            'userUrl' => $request->getServer('TYPECHO_SITE_URL'),
            'userName' => $request->getServer('TYPECHO_USER_NAME', 'typecho'),
            'userPassword' => $request->getServer('TYPECHO_USER_PASSWORD'),
            'userMail' => $request->getServer('TYPECHO_USER_MAIL', 'admin@localhost.local')
        ];
    } else {
        $config = $request->from([
            'userUrl',
            'userName',
            'userPassword',
            'userMail',
        ]);
    }

    $error = (new \Typecho\Validate())
        ->addRule('userUrl', 'required', _t('Vui lòng điền vào địa chỉ trang web'))
        ->addRule('userUrl', 'url', _t('Vui lòng điền vào một địa chỉ URL hợp pháp'))
        ->addRule('userName', 'required', _t('Tên người dùng phải được điền vào'))
        ->addRule('userName', 'xssCheck', _t('Vui lòng không sử dụng các ký tự đặc biệt trong tên người dùng'))
        ->addRule('userName', 'maxLength', _t('Độ dài của tên người dùng vượt quá giới hạn, vui lòng không vượt quá 32 ký tự'), 32)
        ->addRule('userMail', 'required', _t('Email phải được điền vào'))
        ->addRule('userMail', 'email', _t('Lỗi định dạng email'))
        ->addRule('userMail', 'maxLength', _t('Độ dài của hộp thư vượt quá giới hạn, vui lòng không vượt quá 200 ký tự'), 200)
        ->run($config);

    if (!empty($error)) {
        install_raise_error($error);
    }

    if (empty($config['userPassword'])) {
        $config['userPassword'] = $defaultPassword;
    }

    try {
        // write user
        $hasher = new \Utils\PasswordHash(8, true);
        $installDb->query(
            $installDb->insert('table.users')->rows([
                'name' => $config['userName'],
                'password' => $hasher->hashPassword($config['userPassword']),
                'mail' => $config['userMail'],
                'url' => $config['userUrl'],
                'screenName' => $config['userName'],
                'group' => 'administrator',
                'created' => \Typecho\Date::time()
            ])
        );

        // write category
        $installDb->query(
            $installDb->insert('table.metas')
                ->rows([
                    'name' => _t('Mặc định'),
                    'slug' => 'default',
                    'type' => 'category',
                    'description' => _t('Chỉ là một danh mục mặc định'),
                    'count' => 1
                ])
        );

        $installDb->query($installDb->insert('table.relationships')->rows(['cid' => 1, 'mid' => 1]));

        // write first page and post
        $installDb->query(
            $installDb->insert('table.contents')->rows([
                'title' => _t('Chào mừng đến với Typecho'),
                'slug' => 'start', 'created' => \Typecho\Date::time(),
                'modified' => \Typecho\Date::time(),
                'text' => '<!--markdown-->' . _t('Nếu bạn thấy bài viết này, có nghĩa là blog của bạn đã được cài đặt thành công.'),
                'authorId' => 1,
                'type' => 'post',
                'status' => 'publish',
                'commentsNum' => 1,
                'allowComment' => 1,
                'allowPing' => 1,
                'allowFeed' => 1,
                'parent' => 0
            ])
        );

        $installDb->query(
            $installDb->insert('table.contents')->rows([
                'title' => _t('About'),
                'slug' => 'start-page',
                'created' => \Typecho\Date::time(),
                'modified' => \Typecho\Date::time(),
                'text' => '<!--markdown-->' . _t('Trang này được tạo bởi Typecho, đây chỉ là một trang thử nghiệm.'),
                'authorId' => 1,
                'order' => 0,
                'type' => 'page',
                'status' => 'publish',
                'commentsNum' => 0,
                'allowComment' => 1,
                'allowPing' => 1,
                'allowFeed' => 1,
                'parent' => 0
            ])
        );

        // write comment
        $installDb->query(
            $installDb->insert('table.comments')->rows([
                'cid' => 1, 'created' => \Typecho\Date::time(),
                'author' => 'Typecho',
                'ownerId' => 1,
                'url' => 'http://typecho.org',
                'ip' => '127.0.0.1',
                'agent' => $options->generator,
                'text' => 'Chào mừng đến với gia đình Typecho',
                'type' => 'comment',
                'status' => 'approved',
                'parent' => 0
            ])
        );

        // write options
        foreach (install_get_default_options() as $key => $value) {
            // mark installing finished
            if ($key == 'installed') {
                $value = 1;
            }

            $installDb->query(
                $installDb->insert('table.options')->rows(['name' => $key, 'user' => 0, 'value' => $value])
            );
        }
    } catch (\Typecho\Db\Exception $e) {
        install_raise_error($e->getMessage());
    }

    $parts = parse_url($options->loginAction);
    $parts['query'] = http_build_query([
            'name'  => $config['userName'],
            'password' => $config['userPassword'],
            'referer' => $options->adminUrl
        ]);
    $loginUrl = \Typecho\Common::buildUrl($parts);

    install_success(0, [
        $config['userName'],
        $config['userPassword'],
        \Widget\Security::alloc()->getTokenUrl($loginUrl, $request->getReferer()),
        $config['userUrl']
    ]);
}

/**
 * dispatch install action
 *
 */
function install_dispatch()
{
    // disable root url on cli mode
    if (install_is_cli()) {
        define('__TYPECHO_ROOT_URL__', 'http://localhost');
    }

    // init default options
    $options = \Widget\Options::alloc(install_get_default_options());
    \Widget\Init::alloc();

    // display version
    if (install_is_cli()) {
        echo $options->generator . "\n";
        echo 'PHP ' . PHP_VERSION . "\n";
    }

    // install finished yet
    if (
        install_check('config')
        && install_check('db_structure')
        && install_check('db_data')
    ) {
        // redirect to siteUrl if not cli
        if (!install_is_cli()) {
            install_redirect($options->siteUrl);
        }

        exit(1);
    }

    if (install_is_cli()) {
        install_step_1_perform();
    } else {
        $request = \Typecho\Request::getInstance();
        $step = $request->get('step');

        $action = 1;

        switch (true) {
            case $step == 2:
                if (!install_check('db_structure')) {
                    $action = 2;
                } else {
                    install_redirect('install.php?step=3');
                }
                break;
            case $step == 3:
                if (install_check('db_structure')) {
                    $action = 3;
                } else {
                    install_redirect('install.php?step=2');
                }
                break;
            default:
                break;
        }

        $method = 'install_step_' . $action;

        if ($request->isPost()) {
            $method .= '_perform';
            $method();
            exit;
        }
        ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="<?php _e('UTF-8'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php _e('Trình cài đặt Typecho'); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php $options->adminStaticUrl('css', 'normalize.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php $options->adminStaticUrl('css', 'grid.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php $options->adminStaticUrl('css', 'style.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php $options->adminStaticUrl('css', 'install.css') ?>" />
    <script src="<?php $options->adminStaticUrl('js', 'jquery.js'); ?>"></script>
</head>
<body>
    <div class="body container">
        <h1><a href="http://typecho.org" target="_blank" class="i-logo">Typecho</a></h1>
        <?php $method(); ?>
    </div>
</body>
</html>
        <?php
    }
}

install_dispatch();
