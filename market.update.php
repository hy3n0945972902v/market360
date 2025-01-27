<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 * @Createdate Sun, 20 Nov 2016 07:31:04 GMT
 */
define('NV_SYSTEM', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __file__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';


// Duyệt tất cả các ngôn ngữ
$language_query = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup = 1');
while (list ($lang) = $language_query->fetch(3)) {
    $mquery = $db->query("SELECT title, module_data FROM " . $db_config['prefix'] . "_" . $lang . "_modules WHERE module_file = 'market'");
    while (list ($mod, $mod_data) = $mquery->fetch(3)) {

        $_sql = array();

        $data = array(
            'maps_appid' => '',
            'priceformat' => 0,
            'map_position' => 'top',
            'price_days' => 0,
            'price_month' => 0,
        );
        foreach ($data as $config_name => $config_value) {
            $_sql[] = "INSERT INTO " . $dataname . "." . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', " . $db->quote($mod) . ", " . $db->quote($config_name) . ", " . $db->quote($config_value) . ")";
            $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'activecomm', '1')";
        }

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'refresh_allow', '0');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'refresh_config', '');";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD ordertime INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER userid;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_block_cat ADD useradd TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER keywords;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_block_cat ADD color VARCHAR(10) NOT NULL AFTER image;";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'specialgroup_config', '');";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_block ADD exptime INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER id;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows DROP queue_time, DROP queue_userid;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD groupid VARCHAR(255) NOT NULL AFTER catid;";

        $result = $db->query("SELECT id FROM " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows");
        while ($row = $result->fetch()) {
            $_result = $db->query("SELECT bid FROM " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_block WHERE id=" . $row['id']);
            $list_bid = array();
            while (list ($bid) = $_result->fetch(3)) {
                $list_bid[] = $bid;
            }
            if (!empty($list_bid)) {
                $_sql[] = "UPDATE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows SET groupid=" . $db->quote(implode(',', $list_bid)) . ' WHERE id=' . $row['id'];
            }
        }


        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_econtent(action varchar(100) NOT NULL, econtent text NOT NULL, PRIMARY KEY (action ) ENGINE=MyISAM";

        $_sql[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_econtent (action, econtent) VALUES('queue_status', 'Xin chào <strong>&#91;NAME&#93;. </strong>Chúng tôi<strong>&nbsp;</strong>xin gửi đến bạn thông báo về trạng thái tin rao của bạn tại&nbsp;<strong>&#91;SITE_NAME&#93;!</strong><br  /><br  />Trạng thái hiện tại: &#91;STATUS&#93;<br  />Kiểm duyệt bởi: &#91;QUEUE_NAME&#93;<br  />Thời gian duyệt: &#91;QUEUE_TIME&#93;<br  />Ghi chú: &#91;NOTE&#93;<br  /><br  />Mọi ý kiến xin gửi về &#91;SITE_EMAIL&#93; để được giải đáp, xin cảm ơn!')";

        $_sql[] = "UPDATE " . $db_config['prefix'] . "_setup_extensions SET version='1.0.01 " . NV_CURRENTTIME . "' WHERE type='module' and basename=" . $db->quote($mod);

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'style_default', 'viewlist_simple');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'province_default', '0');";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD description TEXT NOT NULL AFTER typeid;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows CHANGE contact_phone contact_phone VARCHAR(255) NOT NULL;";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'tags_alias', '0'), ('" . $lang . "', '" . $mod . "', 'auto_tags', '0'), ('" . $lang . "', '" . $mod . "', 'tags_remind', '1');";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_tags(
          tid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
          numnews mediumint(8) NOT NULL DEFAULT '0',
          alias varchar(250) NOT NULL DEFAULT '',
          image varchar(255) DEFAULT '',
          description text,
          keywords varchar(255) DEFAULT '',
          PRIMARY KEY (tid),
          UNIQUE KEY alias (alias)
        ) ENGINE=MyISAM;";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_tags_id(
          id int(11) NOT NULL,
          tid mediumint(9) NOT NULL,
          keyword varchar(65) NOT NULL,
          UNIQUE KEY id_tid (id,tid),
          KEY tid (tid)
        ) ENGINE=MyISAM;";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'fb_appid', ''), ('" . $lang . "', '" . $mod . "', 'fb_secret', ''), ('" . $lang . "', '" . $mod . "', 'fb_pagetoken_pages', '');";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD count_fb_post SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' AFTER count_refresh;";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'similar_content', '80');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'similar_time', '5');";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_fb_queue( rowsid int(11) unsigned NOT NULL, UNIQUE KEY rowsid (rowsid) ) ENGINE=MyISAM";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_mail_queue( id smallint(4) unsigned NOT NULL AUTO_INCREMENT, tomail varchar(100) NOT NULL, subject varchar(255) NOT NULL, message text NOT NULL, PRIMARY KEY (id) ) ENGINE=MyISAM";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'fb_enable', '0');";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_logs ADD reasonid TINYINT(2) UNSIGNED NOT NULL DEFAULT '0' AFTER reason;";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_reason( id smallint(4) unsigned NOT NULL AUTO_INCREMENT, title varchar(255) NOT NULL, note tinytext NOT NULL COMMENT 'Ghi chú', weight smallint(4) unsigned NOT NULL DEFAULT '0', status tinyint(1) NOT NULL COMMENT 'Trạng thái', PRIMARY KEY (id) ) ENGINE=MyISAM";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_refresh( userid int(11) unsigned NOT NULL, count int(11) unsigned NOT NULL DEFAULT '0', free smallint(4) unsigned NOT NULL DEFAULT '0', free_time int(11) unsigned NOT NULL DEFAULT '0', UNIQUE KEY userid (userid) ) ENGINE=MyISAM";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'refresh_default', '0'), ('" . $lang . "', '" . $mod . "', 'refresh_free', '0');";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows DROP count_refresh;";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'refresh_timelimit', '120');";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD refresh_time INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER ordertime;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD prior INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Ưu tiên' AFTER userid;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_block_cat ADD prior SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' AFTER weight;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD groups_config TEXT NOT NULL AFTER is_queue;";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'fb_accesstoken', '');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'fb_groupid', '');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'auto_link', '1');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'auto_link_casesens', '0');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'auto_link_target', '');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'auto_link_limit', '3');";

        $_sql[] = "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_name = 'freelancegroup' WHERE lang = '" . $lang . "' AND module = '" . $mod . "' AND config_name = 'vipgroup';";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_freelance(
          userid int(11) unsigned NOT NULL,
          salary double unsigned NOT NULL DEFAULT '0' COMMENT 'Lương',
          total double unsigned NOT NULL DEFAULT '0' COMMENT 'Tổng thu nhập',
          pay double unsigned NOT NULL DEFAULT '0',
          UNIQUE KEY userid (userid)
        ) ENGINE=MyISAM";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'tags_alias_lower', '1');";

        $_sql[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_econtent (action, econtent) VALUES('refresh', '')";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_freelance_payment( id smallint(4) unsigned NOT NULL AUTO_INCREMENT, userid int(11) unsigned NOT NULL, money double unsigned NOT NULL DEFAULT '0' COMMENT 'Số tiền thanh toán', addtime int(11) unsigned NOT NULL, PRIMARY KEY (id) ) ENGINE=MyISAM";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit(
          rowsid int(11) unsigned NOT NULL,
          title varchar(255) NOT NULL,
          catid smallint(4) NOT NULL,
          area_p smallint(4) NOT NULL COMMENT 'Vùng',
          area_d smallint(4) unsigned NOT NULL,
          area_w smallint(4) unsigned NOT NULL,
          typeid tinyint(1) NOT NULL,
          description text NOT NULL,
          content text NOT NULL,
          pricetype tinyint(1) unsigned NOT NULL DEFAULT '0',
          price double unsigned NOT NULL DEFAULT '0',
          price1 double unsigned NOT NULL DEFAULT '0',
          unitid smallint(4) unsigned NOT NULL,
          homeimgfile varchar(255) NOT NULL DEFAULT '',
          homeimgalt varchar(255) NOT NULL DEFAULT '',
          homeimgthumb tinyint(1) unsigned NOT NULL DEFAULT '0',
          note text NOT NULL,
          exptime int(11) unsigned NOT NULL DEFAULT '0',
          auction tinyint(1) unsigned NOT NULL DEFAULT '0',
          auction_begin int(11) unsigned NOT NULL DEFAULT '0',
          auction_end int(11) unsigned NOT NULL DEFAULT '0',
          auction_price_begin double unsigned NOT NULL DEFAULT '0',
          auction_price_step double unsigned NOT NULL DEFAULT '0',
          contact_fullname varchar(255) NOT NULL,
          contact_email varchar(100) NOT NULL,
          contact_phone varchar(255) NOT NULL,
          contact_address varchar(255) NOT NULL,
          images TEXT NOT NULL,
          keywords TEXT NOT NULL,
          PRIMARY KEY (rowsid)
        ) ENGINE=MyISAM";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD is_queue_edit TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER is_queue;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD pack_money tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER groups_config;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_cat ADD pricetype TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER image;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows CHANGE price price DOUBLE UNSIGNED NOT NULL DEFAULT '0';";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD price1 DOUBLE UNSIGNED NOT NULL DEFAULT '0' AFTER price;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD groupcomment TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER countcomment;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit CHANGE price price DOUBLE UNSIGNED NOT NULL DEFAULT '0';";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit ADD price1 DOUBLE UNSIGNED NOT NULL DEFAULT '0' AFTER price;";

        $_sql[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_econtent (action, econtent) VALUES('terms', '<h2>1 .Tiêu đề</h2> - Tiêu đề cần thể hiện ngắn gọn nội dung cần đăng.<br /> Ví dụ: thay vì đặt <strong>Cần tuyển nhân viên</strong>, hãy đặt <strong>Cần tuyển nhân viên bán hàng quần áo tại shops Famy</strong><br /> - Không viết hoa toàn bộ tiêu đề<br /> - Nên viết hoa đầu dòng cũng như các địa danh, tên người, theo quy tắc tiếng việt <h2>2. Nội dung</h2> - Nội dung chi tiết cần mô tả rõ ràng nhất về nội dung muốn truyền tải<br /> - Chỉ chấp nhận nội dung là tiếng việt<br /> - Không đăng lại các nội dung đã được đăng trước đó. Sử dụng chức năng <a href=\"https://raodn.com/huong-dan/Huong-dan-lam-moi-tin.html\"><strong>Làm mới tin</strong></a> nếu bán muốn đăng lại tin cùng nội dung<br /> - Không được chèn các liên kết đến site khác ngoài <strong>raodn.com</strong><br /> - Không chứa các ký tự đặc biệt, cái biểu tượng (icon) <h2>3. Thông tin liên hệ</h2> Nội dung của bạn phải có ít nhất một trường thông tin liên hệ, là số điện thoại hoặc email để người xem có thể chủ động liên hệ với bạn khi cần thiết')";
        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'editor_guest', '0');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'block_viewlist', '6');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'remove_link', '1');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'activecomm', '1');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'groupcomment', '-1');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'view_comm', '1');";

        $_sql[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'allowed_comm', '1');";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_news( id int(11) unsigned NOT NULL AUTO_INCREMENT, catid smallint(4) unsigned NOT NULL, content text NOT NULL, addtime int(11) unsigned NOT NULL, PRIMARY KEY (id) ) ENGINE=MyISAM";

        $_sql[] = "UPDATE " . $db_config['prefix'] . "_setup_extensions SET version='1.0.02 " . NV_CURRENTTIME . "' WHERE type='module' and basename=" . $db->quote($mod);

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_type CHANGE title title VARCHAR(250) NOT NULL;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_type ADD alias VARCHAR(250) NOT NULL AFTER title;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit CHANGE images images TEXT NOT NULL DEFAULT '';";


        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_detail(
          id int(11) unsigned NOT NULL,
          content text NOT NULL,
          note text NOT NULL,
          groupcomment varchar(255) NOT NULL,
          contact_fullname varchar(255) NOT NULL,
          contact_email varchar(100) NOT NULL,
          contact_phone varchar(255) NOT NULL,
          contact_address varchar(255) NOT NULL,
          PRIMARY KEY (id)
        ) ENGINE=MyISAM";

        $result = $db->query("SHOW COLUMNS FROM " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows LIKE 'content'")->fetch();
        if ($result) {
            $result = $db->query("select id, content, note, groupcomment, contact_fullname, contact_email, contact_phone, contact_address from " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows");
            while ($row = $result->fetch()) {
                $_sql[] = "insert into " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_detail values (" . $row['id'] . ", " . $db->quote($row['content']) . ", " . $db->quote($row['note']) . ", " . $db->quote($row['groupcomment']) . ", " . $db->quote($row['contact_fullname']) . ", " . $db->quote($row['contact_email']) . ", " . $db->quote($row['contact_phone']) . ", " . $db->quote($row['contact_address']) . ")";
            }
        }

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows DROP content, DROP note, DROP groupcomment, DROP contact_fullname, DROP contact_email, DROP contact_phone, DROP contact_address;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows CHANGE groups_config groups_config TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit CHANGE keywords keywords TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_cat ADD form VARCHAR(250) NOT NULL DEFAULT '' AFTER sort;";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_template (
          id mediumint(8) NOT NULL AUTO_INCREMENT,
          status tinyint(1) NOT NULL DEFAULT '1',
          title VARCHAR(250) NOT NULL DEFAULT '',
          alias VARCHAR(250) NOT NULL DEFAULT '',
          weight mediumint(8) unsigned NOT NULL DEFAULT '1',
          UNIQUE alias (alias),
          PRIMARY KEY (id)
        ) ENGINE=MyISAM ";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_field (
          fid mediumint(8) NOT NULL AUTO_INCREMENT,
          field varchar(25) NOT NULL,
          listtemplate varchar(25) NOT NULL,
          tab varchar(250) NOT NULL DEFAULT '',
          weight int(10) unsigned NOT NULL DEFAULT '1',
          field_type enum('number','date','textbox','textarea','editor','select','radio','checkbox','multiselect') NOT NULL DEFAULT 'textbox',
          field_choices text NOT NULL,
          sql_choices text NOT NULL,
          match_type enum('none','alphanumeric','email','url','regex','callback') NOT NULL DEFAULT 'none',
          match_regex varchar(250) NOT NULL DEFAULT '',
          func_callback varchar(75) NOT NULL DEFAULT '',
          min_length int(11) NOT NULL DEFAULT '0',
          max_length bigint(20) unsigned NOT NULL DEFAULT '0',
          class varchar(25) NOT NULL DEFAULT '',
          language text NOT NULL,
          default_value varchar(250) NOT NULL DEFAULT '',
          PRIMARY KEY (fid),
          UNIQUE KEY field (field)
        ) ENGINE=MyISAM";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_info (
        	rowid int(11) unsigned NOT NULL,
        	PRIMARY KEY (rowid)
        ) ENGINE=MyISAM";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_detail ADD maps VARCHAR(255) NOT NULL AFTER content;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD area_w VARCHAR(255) NOT NULL AFTER area_d;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit ADD area_w VARCHAR(255) NOT NULL AFTER area_d;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_crawler_items ADD area_w VARCHAR(255) NOT NULL AFTER area_d;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD address VARCHAR(255) NOT NULL AFTER area_w;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_company ADD wardid VARCHAR(255) NOT NULL AFTER districtid;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit ADD maps VARCHAR(255) NOT NULL AFTER content;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_detail ADD display_maps TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER maps;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit ADD display_maps TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER maps;";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_crawler_groups(
          id smallint(4) NOT NULL AUTO_INCREMENT,
          title varchar(255) NOT NULL,
          url varchar(255) NOT NULL,
          titlekey varchar(255) NOT NULL,
          logo varchar(255) NOT NULL,
          type tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
          container_list_outside varchar(255) NOT NULL,
          container_list_title varchar(255) NOT NULL,
          container_list_hometext varchar(255) NOT NULL,
          container_list_homeimage varchar(255) NOT NULL,
          container_list_url varchar(255) NOT NULL,
          container_title varchar(255) NOT NULL COMMENT 'Nhận diện tiêu đề',
          container_homeimage varchar(255) NOT NULL COMMENT 'Nhận diện ảnh minh họa',
          container_hometext varchar(255) NOT NULL COMMENT 'Nhận diện GT ngắn gọn',
          container_bodytext varchar(255) NOT NULL COMMENT 'Nhận diện nội dung',
          container_price varchar(255) NOT NULL COMMENT 'Nhận diện giá',
          container_maplat varchar(255) NOT NULL,
          container_maplng varchar(255) NOT NULL,
          container_contact_fullname varchar(255) NOT NULL,
          container_contact_email varchar(255) NOT NULL,
          container_contact_phone varchar(255) NOT NULL,
          container_contact_address varchar(255) NOT NULL,
          container_remove text NOT NULL COMMENT 'Nhận diện thẻ loại bỏ',
          other_remove_string text NOT NULL COMMENT 'Cụm từ loại bỏ',
          note TEXT NOT NULL,
          weight smallint(4) NOT NULL,
          updatetime int(11) UNSIGNED NOT NULL DEFAULT '0',
          status tinyint(1) NOT NULL DEFAULT '1',
          PRIMARY KEY (id)
        ) ENGINE=MyISAM";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_crawler_items(
           id mediumint(8) NOT NULL AUTO_INCREMENT,
          title varchar(255) NOT NULL,
          url varchar(255) NOT NULL COMMENT 'URL cần lấy',
          groups_id smallint(4) NOT NULL COMMENT 'ID nhóm nguồn tin',
          typeid tinyint(1) unsigned NOT NULL DEFAULT '0',
          catid varchar(255) NOT NULL COMMENT 'Chủ đề lưu tin',
          area_p smallint(4) NOT NULL COMMENT 'Tỉnh',
          area_d smallint(4) unsigned NOT NULL COMMENT 'Huyện',
          area_w smallint(4) unsigned NOT NULL COMMENT 'Xã',
          queue tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Kiểm duyệt tin',
          save_image tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Tải ảnh về host',
          auto_getkeyword tinyint(1) unsigned NOT NULL DEFAULT '1',
          auto_keyword tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Tự tạo từ khóa bài viết',
          save_limit smallint(4) unsigned NOT NULL DEFAULT '20' COMMENT 'Số lượng tin lấy',
          auto_homeimage tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Tự động lấy ảnh minh họa',
          remove_link tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Xóa liên kết trong nội dung',
          autolink tinyint(1) unsigned NOT NULL DEFAULT '1',
          autotime smallint(4) unsigned NOT NULL DEFAULT '0',
          addtime int(11) unsigned NOT NULL COMMENT 'Thời gian thêm',
          updatetime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật',
          lasttime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian thực hiện gần nhất',
          status tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Trạng thái',
          PRIMARY KEY (id)
        ) ENGINE=MyISAM";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_crawler_rows(
          id int(11) unsigned NOT NULL AUTO_INCREMENT,
          title varchar(255) NOT NULL,
          url varchar(250) NOT NULL COMMENT 'URL bài viết',
          url_md5 varchar(32) NOT NULL COMMENT 'URL mã hóa md5',
          items_id mediumint(8) NOT NULL,
          addtime int(11) unsigned NOT NULL COMMENT 'Thời gian thêm vào',
          applytime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian duyệt',
          applyuserid mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID quản trị duyệt',
          status tinyint(1) unsigned NOT NULL DEFAULT '1',
          PRIMARY KEY (id),
          UNIQUE KEY url (url),
          KEY items_id (items_id)
        ) ENGINE=MyISAM";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_widget(
          id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
          title varchar(255) NOT NULL,
          note text NOT NULL,
          icon varchar(255) NOT NULL,
          weight smallint(4) unsigned NOT NULL DEFAULT '0',
          dfault tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Mặc định',
          status tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái',
          PRIMARY KEY (id)
        ) ENGINE=MyISAM";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_facilities(
          id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
          title varchar(255) NOT NULL,
          note text NOT NULL,
          icon varchar(255) NOT NULL,
          weight smallint(4) unsigned NOT NULL DEFAULT '0',
          dfault tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Mặc định',
          status tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái',
          PRIMARY KEY (id)
        ) ENGINE=MyISAM";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_field ADD show_locations varchar(25) NOT NULL AFTER listtemplate;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD wid varchar(255) NOT NULL COMMENT 'Tiện ích' AFTER alias;";
        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD faci varchar(255) NOT NULL COMMENT 'Tiện nghi' AFTER wid;";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_post_type(
          id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
          title varchar(255) NOT NULL,
          price double unsigned NOT NULL DEFAULT '0',
          note text NOT NULL,
          status tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái',
          weight smallint(4) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (id)
        ) ENGINE=MyISAM;";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_packages(
          id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
          title varchar(255) NOT NULL,
          price double unsigned NOT NULL DEFAULT '0',
          website text NOT NULL,
          note text NOT NULL,
          status tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái',
          weight smallint(4) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (id)
        ) ENGINE=MyISAM;";

        $_sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_package_websites(
          id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
          link text NOT NULL,
          note text NOT NULL,
          status tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái',
          weight smallint(4) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (id)
        ) ENGINE=MyISAM;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD post_type smallint(4) NOT NULL COMMENT 'Loại tin đăng' AFTER pack_money;";
        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD price_info double unsigned NOT NULL DEFAULT '0' AFTER post_type;";
        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD starttime int(11) unsigned NOT NULL DEFAULT '0' AFTER edittime;";
        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD autopost tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Trạng thái đăng tin tự động' AFTER exptime;";
        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows ADD package smallint(4) NOT NULL DEFAULT '0' COMMENT 'Gói' AFTER autopost;";

        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit ADD post_type smallint(4) NOT NULL COMMENT 'Loại tin đăng' AFTER images;";
        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit ADD price_info double unsigned NOT NULL DEFAULT '0' AFTER post_type;";
        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit ADD starttime int(11) unsigned NOT NULL DEFAULT '0' AFTER note;";
        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit ADD autopost tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Trạng thái đăng tin tự động' AFTER exptime;";
        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_queue_edit ADD package smallint(4) NOT NULL DEFAULT '0' COMMENT 'Gói' AFTER autopost;";

        if (!empty($_sql)) {
            foreach ($_sql as $sql) {
                try {
                    $db->query($sql);
                } catch (PDOException $e) {
                    //
                }
            }
            $nv_Cache->delMod($mod);
        }
    }
}

die('OK');
