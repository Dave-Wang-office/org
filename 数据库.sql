-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2020-08-16 00:25:57
-- 服务器版本： 5.6.48-log
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sucaihuo`
--

-- --------------------------------------------------------

--
-- 表的结构 `ad`
--

CREATE TABLE IF NOT EXISTS `ad` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '广告名称',
  `as_id` tinyint(5) NOT NULL COMMENT '所属位置',
  `pic` varchar(200) NOT NULL DEFAULT '' COMMENT '广告图片URL',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '广告链接',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `sort` int(11) NOT NULL COMMENT '排序',
  `open` tinyint(2) NOT NULL COMMENT '1=审核  0=未审核',
  `content` varchar(225) DEFAULT '' COMMENT '广告内容'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='广告表';

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` tinyint(4) NOT NULL COMMENT '管理员ID',
  `username` varchar(20) NOT NULL COMMENT '管理员用户名',
  `pwd` varchar(70) NOT NULL COMMENT '管理员密码',
  `group_id` mediumint(8) DEFAULT NULL COMMENT '分组ID',
  `email` varchar(30) DEFAULT NULL COMMENT '邮箱',
  `realname` varchar(10) DEFAULT NULL COMMENT '真实姓名',
  `tel` varchar(30) DEFAULT NULL COMMENT '电话号码',
  `ip` varchar(20) DEFAULT NULL COMMENT 'IP地址',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `mdemail` varchar(50) DEFAULT '0' COMMENT '传递修改密码参数加密',
  `is_open` tinyint(2) DEFAULT '0' COMMENT '审核状态',
  `avatar` varchar(120) DEFAULT '' COMMENT '头像',
  `mubiao` float(100,0) DEFAULT NULL COMMENT '当月目标业绩',
  `ticheng` float(10,0) DEFAULT NULL COMMENT '提成点%',
  `curgetnum` int(11) DEFAULT '0' COMMENT '当前用户使用抢的次数',
  `curmaxnum` int(11) DEFAULT NULL COMMENT '用户当前抢的最大值'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台管理员';

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `pwd`, `group_id`, `email`, `realname`, `tel`, `ip`, `add_time`, `mdemail`, `is_open`, `avatar`, `mubiao`, `ticheng`, `curgetnum`, `curmaxnum`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, '327211663@qq.com', NULL, '17501005354', '127.0.0.1', 0, '0', 1, '/uploads/20200815/9e1e7fca2b0bbb337013c260cbf4c9e0.jpg', 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `adsense`
--

CREATE TABLE IF NOT EXISTS `adsense` (
  `as_id` tinyint(5) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '广告位名称',
  `sort` int(11) NOT NULL COMMENT '广告位排序'
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='广告分类';

--
-- 转存表中的数据 `adsense`
--

INSERT INTO `adsense` (`as_id`, `name`, `sort`) VALUES
(1, '【首页】顶部轮播', 1),
(5, '【内页】横幅', 1);

-- --------------------------------------------------------

--
-- 表的结构 `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `title` varchar(80) NOT NULL DEFAULT '',
  `keywords` varchar(120) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `content` text NOT NULL COMMENT '内容',
  `template` varchar(40) NOT NULL DEFAULT '',
  `posid` tinyint(2) unsigned DEFAULT '0' COMMENT '推荐位',
  `status` varchar(255) NOT NULL DEFAULT '1',
  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `readgroup` varchar(100) NOT NULL DEFAULT '',
  `readpoint` smallint(5) NOT NULL DEFAULT '0',
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `copyfrom` varchar(255) NOT NULL DEFAULT 'CLTPHP',
  `fromlink` varchar(255) NOT NULL DEFAULT 'http://www.cltphp.com/',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `title_style` varchar(100) NOT NULL DEFAULT '',
  `tags` varchar(255) NOT NULL DEFAULT '' COMMENT '标签'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `article_tags`
--

CREATE TABLE IF NOT EXISTS `article_tags` (
  `id` int(11) NOT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `article_tags`
--

INSERT INTO `article_tags` (`id`, `tag_id`, `article_id`) VALUES
(1, 1, 45),
(2, 2, 45),
(3, 1, 36),
(4, 3, 36),
(5, 4, 36),
(6, 5, 43),
(7, 6, 40),
(8, 7, 17),
(9, 8, 17);

-- --------------------------------------------------------

--
-- 表的结构 `auth_group`
--

CREATE TABLE IF NOT EXISTS `auth_group` (
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '全新ID',
  `title` char(100) NOT NULL DEFAULT '' COMMENT '标题',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `rules` longtext COMMENT '规则',
  `addtime` int(11) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员分组';

--
-- 转存表中的数据 `auth_group`
--

INSERT INTO `auth_group` (`group_id`, `title`, `status`, `rules`, `addtime`) VALUES
(1, '超级管理员', 1, '282,285,293,280,291,305,283,299,288,289,281,292,301,302,303,284,300,311,307,308,313,15,16,119,120,121,145,', 1465114224),
(10, '普通员工', 0, '0,282,285,280,283,299,281,292,301,302,303,307,313,', 1586768658);

-- --------------------------------------------------------

--
-- 表的结构 `auth_rule`
--

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `id` mediumint(8) unsigned NOT NULL,
  `href` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `authopen` tinyint(2) NOT NULL DEFAULT '1',
  `icon` varchar(20) DEFAULT NULL COMMENT '样式',
  `condition` char(100) DEFAULT '',
  `pid` int(5) NOT NULL DEFAULT '0' COMMENT '父栏目ID',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `zt` int(1) DEFAULT NULL,
  `menustatus` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=314 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限节点';

--
-- 转存表中的数据 `auth_rule`
--

INSERT INTO `auth_rule` (`id`, `href`, `title`, `type`, `status`, `authopen`, `icon`, `condition`, `pid`, `sort`, `addtime`, `zt`, `menustatus`) VALUES
(1, 'System', '系统设置', 1, 1, 0, 'icon-cogs', '', 0, 10, 1446535750, 1, 1),
(2, 'System/system', '系统设置', 1, 1, 0, '', '', 1, 1, 1446535789, 1, 1),
(3, 'Database/database', '数据库管理', 1, 1, 0, 'icon-database', '', 0, 22, 1446535805, 1, 1),
(4, 'Database/restore', '还原数据库', 1, 1, 0, '', '', 3, 10, 1446535750, 1, 1),
(5, 'Database/database', '数据库备份', 1, 1, 0, '', '', 3, 1, 1446535834, 1, 1),
(15, 'Auth/adminList', '权限管理', 1, 1, 0, 'icon-lifebuoy', '', 0, 11, 1446535750, 1, 1),
(16, 'Auth/adminList', '管理员列表', 1, 1, 0, '', '', 15, 0, 1446535750, 1, 1),
(17, 'Auth/adminGroup', '用户组列表', 1, 1, 0, '', '', 15, 1, 1446535750, 1, 1),
(18, 'Auth/adminRule', '权限管理', 1, 1, 0, '', '', 15, 2, 1446535750, 1, 1),
(108, 'Auth/ruleAdd', '操作-添加', 1, 1, 0, '', '', 18, 0, 1461550835, 1, 1),
(109, 'Auth/ruleState', '操作-状态', 1, 1, 0, '', '', 18, 5, 1461550949, 1, 1),
(110, 'Auth/ruleTz', '操作-验证', 1, 1, 0, '', '', 18, 6, 1461551129, 1, 1),
(111, 'Auth/ruleorder', '操作-排序', 1, 1, 0, '', '', 18, 7, 1461551263, 1, 1),
(112, 'Auth/ruleDel', '操作-删除', 1, 1, 0, '', '', 18, 4, 1461551536, 1, 1),
(114, 'Auth/ruleEdit', '操作-修改', 1, 1, 0, '', '', 18, 2, 1461551913, 1, 1),
(116, 'Auth/groupEdit', '操作-修改', 1, 1, 0, '', '', 17, 3, 1461552326, 1, 1),
(117, 'Auth/groupDel', '操作-删除', 1, 1, 0, '', '', 17, 30, 1461552349, 1, 1),
(118, 'Auth/groupAccess', '操作-权限', 1, 1, 0, '', '', 17, 40, 1461552404, 1, 1),
(119, 'Auth/adminAdd', '操作-添加', 1, 1, 0, '', '', 16, 0, 1461553162, 1, 1),
(120, 'Auth/adminEdit', '操作-修改', 1, 1, 0, '', '', 16, 2, 1461554130, 1, 1),
(121, 'Auth/adminDel', '操作-删除', 1, 1, 0, '', '', 16, 4, 1461554152, 1, 1),
(126, 'Database/export', '操作-备份', 1, 1, 0, '', '', 5, 1, 1461550835, 1, 1),
(127, 'Database/optimize', '操作-优化', 1, 1, 0, '', '', 5, 1, 1461550835, 1, 1),
(128, 'Database/repair', '操作-修复', 1, 1, 0, '', '', 5, 1, 1461550835, 1, 1),
(129, 'Database/delSqlFiles', '操作-删除', 1, 1, 0, '', '', 4, 3, 1461550835, 1, 1),
(145, 'Auth/adminState', '操作-状态', 1, 1, 0, '', '', 16, 5, 1461550835, 1, 1),
(149, 'Auth/groupAdd', '操作-添加', 1, 1, 0, '', '', 17, 1, 1461550835, 1, 1),
(151, 'Auth/groupRunaccess', '操作-权存', 1, 1, 0, '', '', 17, 50, 1461550835, 1, 1),
(181, 'Auth/groupState', '操作-状态', 1, 1, 0, '', '', 17, 50, 1461834340, 1, 1),
(230, 'Database/import', '操作-还原', 1, 1, 0, '', '', 4, 1, 1497423595, 0, 1),
(232, 'Database/downFile', '操作-下载', 1, 1, 0, '', '', 4, 2, 1497423744, 0, 1),
(270, 'System/email', '邮箱配置', 1, 1, 0, '', '', 1, 2, 1502331829, 0, 1),
(280, 'Clues', '线索管理', 1, 1, 0, 'icon-earth', '', 0, 2, 1554176386, 0, 1),
(281, 'Client', '客户管理', 1, 1, 0, 'icon-user', '', 0, 3, 1554176684, 0, 1),
(282, 'Liberum', '公海管理', 1, 1, 0, 'icon-users', '', 0, 1, 1554177045, 0, 1),
(283, 'Clues/index', '线索列表', 1, 1, 0, '', '', 280, 2, 1554186410, 0, 1),
(284, 'Client/index', '客户列表', 1, 1, 0, '', '', 281, 2, 1554186465, 0, 1),
(285, 'Liberum/index', '客户公海', 1, 1, 1, '', '', 282, 50, 1554187417, 0, 1),
(286, 'Client/rankList', '客户级别', 1, 1, 0, '', '', 281, 50, 1554285420, 0, 1),
(287, 'Client/statusList', '客户状态', 1, 1, 0, '', '', 281, 50, 1554358280, 0, 1),
(288, 'Clues/statusList', '线索状态', 1, 1, 0, '', '', 280, 50, 1554359172, 0, 1),
(289, 'Clues/sourceList', '线索来源', 1, 1, 0, '', '', 280, 50, 1554704322, 0, 1),
(290, 'Clues/areaList', '地区列表', 1, 1, 0, '', '', 281, 50, 1554863693, 0, 1),
(291, 'Clues/perCluList', '我的线索', 1, 1, 1, '', '', 280, 1, 1555032042, 0, 0),
(292, 'Client/perCliList', '我的客户', 1, 1, 0, '', '', 281, 1, 1555032086, 0, 1),
(293, 'Liberum/libTypeList', '公海类型', 1, 1, 1, '', '', 282, 50, 1555309757, 0, 1),
(294, 'Client/alterPrUserPri', '转移客户(个人)', 1, 1, 0, '', '', 292, 50, 1562841109, 0, 1),
(295, 'Client/alterPrUser', '转移客户', 1, 1, 1, '', '', 284, 50, 1563244558, 0, 1),
(296, 'Clues/alterPrUser', '线索转移', 1, 1, 0, '', '', 283, 50, 1563244846, 0, 1),
(297, 'Clues/alterPrUserPri', '线索转移(个人)', 1, 1, 0, '', '', 291, 50, 1563244866, 0, 1),
(298, 'Clues/perClulist', '线索列表(个人)', 1, 1, 1, '', '', 291, 50, 1563245305, 0, 1),
(299, 'Clues/index', '线索列表', 1, 1, 1, '', '', 283, 50, 1563245331, 0, 1),
(300, 'Client/index', '客户列表', 1, 1, 1, '', '', 284, 50, 1563245362, 0, 1),
(301, 'Client/perCliList', '客户列表(我的)', 1, 1, 0, '', '', 292, 50, 1563245403, 0, 1),
(302, 'Client/add', '客户添加', 1, 1, 0, '', '', 292, 50, 1585386092, 0, 1),
(303, 'Client/edit', '客户编辑', 1, 1, 1, '', '', 292, 50, 1585387130, NULL, 1),
(304, 'Client/del', '客户删除', 1, 1, 1, '', '', 292, 50, 1585387181, NULL, 1),
(305, 'Clues/edit', '线索编辑', 1, 1, 1, '', '', 291, 50, 1585388460, NULL, 1),
(306, 'Clues/del', '线索删除', 1, 1, 1, '', '', 291, 50, 1585388498, NULL, 1),
(307, 'Order', '业绩订单', 1, 1, 0, 'icon-database', '', 0, 4, 1585592104, NULL, 1),
(308, 'Order/index', '订单列表', 1, 1, 0, '', '', 307, 50, 1585593076, NULL, 1),
(309, 'Client/hangyeList', '行业类别', 1, 1, 0, '', '', 281, 49, 1586348647, NULL, 1),
(311, 'client/successCliList', '成交客户', 1, 1, 0, '', '', 281, 3, 1586352294, NULL, 1),
(313, 'order/personindex', '我的订单', 1, 1, 1, '', '', 307, 50, 1586370773, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` smallint(5) unsigned NOT NULL,
  `catname` varchar(255) NOT NULL DEFAULT '',
  `catdir` varchar(30) NOT NULL DEFAULT '',
  `parentdir` varchar(50) NOT NULL DEFAULT '',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `moduleid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `module` char(24) NOT NULL DEFAULT '',
  `arrparentid` varchar(255) NOT NULL DEFAULT '',
  `arrchildid` varchar(100) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL DEFAULT '',
  `keywords` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ishtml` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `image` varchar(100) NOT NULL DEFAULT '',
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `url` varchar(100) NOT NULL DEFAULT '',
  `template_list` varchar(20) NOT NULL DEFAULT '',
  `template_show` varchar(20) NOT NULL DEFAULT '',
  `pagesize` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `readgroup` varchar(100) NOT NULL DEFAULT '',
  `listtype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lang` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否预览'
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `category`
--

INSERT INTO `category` (`id`, `catname`, `catdir`, `parentdir`, `pid`, `moduleid`, `module`, `arrparentid`, `arrchildid`, `type`, `title`, `keywords`, `description`, `sort`, `ishtml`, `ismenu`, `hits`, `image`, `child`, `url`, `template_list`, `template_show`, `pagesize`, `readgroup`, `listtype`, `lang`, `is_show`) VALUES
(1, '最新动态', 'news', '', 0, 2, 'article', '0', '1,5,6,14,3', 0, '最新动态', '最新动态', '最新动态', 4, 0, 1, 0, '', 1, '', 'article_list', 'article_show', 0, '1,2,3', 0, 0, 0),
(2, '关于我们', 'about', '', 0, 1, 'page', '0', '2', 0, '关于我们', 'CLTPHP内容管理系统，微信公众平台、APP移动应用设计、HTML5网站API定制开发。大型企业网站、个人博客论坛、手机网站定制开发。更高效、更快捷的进行定制开发。', 'CLTPHP内容管理系统，微信公众平台、APP移动应用设计、HTML5网站API定制开发。大型企业网站、个人博客论坛、手机网站定制开发。更高效、更快捷的进行定制开发。', 0, 0, 1, 0, '', 0, '', '', '', 0, '1', 0, 0, 0),
(3, 'CLTPHP服务', 'news', 'news/', 1, 2, 'article', '0,1', '3', 0, '产品服务-CLTPHP', '产品服务,CLTPHP,CLTPHP内容管理系统', '产品服务', 1, 0, 1, 0, '', 0, '', '', '', 0, '1,2,3', 0, 0, 1),
(4, '系统操作', 'system', '', 0, 3, 'picture', '0', '4', 0, 'CLTPHP系统操作', 'CLTPHP系统操作,CLTPHP,CLTPHP内容管理系统', 'CLTPHP系统操作,CLTPHP,CLTPHP内容管理系统', 2, 0, 1, 0, '', 0, '', '', '', 0, '1,2', 0, 0, 0),
(5, 'CLTPHP动态', 'news', 'news/', 1, 2, 'article', '0,1', '5', 0, 'CLTPHP动态', 'CLTPHP动态', 'CLTPHP动态', 0, 0, 1, 0, '', 0, '', 'article_list', '', 0, '1,2,3', 0, 0, 1),
(6, '相关知识 ', 'news', 'news/', 1, 2, 'article', '0,1', '6', 0, 'CLTPHP相关知识', 'CLTPHP相关知识', 'CLTPHP相关知识', 0, 0, 1, 0, '', 0, '', '', '', 0, '1,2,3', 0, 0, 1),
(7, '精英团队', 'team', '', 0, 6, 'team', '0', '7', 0, '精英团队', '精英团队', '精英团队', 5, 0, 1, 0, '', 0, '', '', '', 0, '1,2', 0, 0, 0),
(8, '联系我们', 'contact', '', 0, 1, 'page', '0', '8', 0, '联系我们', '联系我们', '联系我们', 7, 0, 1, 0, '', 0, '', 'page_show_contace', 'page_show_contace', 0, '1,2', 0, 0, 0),
(14, '文件下载', 'news', 'news/', 1, 5, 'download', '0,1', '14', 0, '测试下载', '测试下载', '测试下载', 0, 0, 1, 0, '', 0, '', '', '', 0, '1,2,3', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` smallint(6) unsigned NOT NULL COMMENT '表id',
  `name` varchar(50) DEFAULT NULL COMMENT '配置的key键名',
  `value` varchar(512) DEFAULT NULL COMMENT '配置的val值',
  `inc_type` varchar(64) DEFAULT NULL COMMENT '配置分组',
  `desc` varchar(50) DEFAULT NULL COMMENT '描述'
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `config`
--

INSERT INTO `config` (`id`, `name`, `value`, `inc_type`, `desc`) VALUES
(16, 'is_mark', '0', 'water', '0'),
(17, 'mark_txt', '', 'water', '0'),
(18, 'mark_img', '/public/upload/public/2017/01-20/10cd966bd5f3549833c09a5c9700a9b8.jpg', 'water', '0'),
(19, 'mark_width', '', 'water', '0'),
(20, 'mark_height', '', 'water', '0'),
(21, 'mark_degree', '54', 'water', '0'),
(22, 'mark_quality', '56', 'water', '0'),
(23, 'sel', '9', 'water', '0'),
(24, 'sms_url', 'https://yunpan.cn/OcRgiKWxZFmjSJ', 'sms', '0'),
(25, 'sms_user', '', 'sms', '0'),
(26, 'sms_pwd', '访问密码 080e', 'sms', '0'),
(27, 'regis_sms_enable', '1', 'sms', '0'),
(28, 'sms_time_out', '1200', 'sms', '0'),
(38, '__hash__', '8d9fea07e44955760d3407524e469255_6ac8706878aa807db7ffb09dd0b02453', 'sms', '0'),
(39, '__hash__', '8d9fea07e44955760d3407524e469255_6ac8706878aa807db7ffb09dd0b02453', 'sms', '0'),
(56, 'sms_appkey', '123456789', 'sms', '0'),
(57, 'sms_secretKey', '123456789', 'sms', '0'),
(58, 'sms_product', 'CLTPHP', 'sms', '0'),
(59, 'sms_templateCode', 'SMS_101234567890', 'sms', '0'),
(60, 'smtp_server', 'smtp.qq.com', 'smtp', '0'),
(61, 'smtp_port', '465', 'smtp', '0'),
(62, 'smtp_user', '327211663@qq.com', 'smtp', '0'),
(63, 'smtp_pwd', 'zmmqivfdfflahemiegc', 'smtp', '0'),
(64, 'regis_smtp_enable', '1', 'smtp', '0'),
(65, 'test_eamil', '327211663@qq.com', 'smtp', '0'),
(70, 'forget_pwd_sms_enable', '1', 'sms', '0'),
(71, 'bind_mobile_sms_enable', '1', 'sms', '0'),
(72, 'order_add_sms_enable', '1', 'sms', '0'),
(73, 'order_pay_sms_enable', '1', 'sms', '0'),
(74, 'order_shipping_sms_enable', '1', 'sms', '0'),
(88, 'email_id', '飞天猫CRM系统', 'smtp', '0'),
(89, 'test_eamil_info', ' 您好！这是一封来自飞天猫CRM系统测试邮件！', 'smtp', '0');

-- --------------------------------------------------------

--
-- 表的结构 `crm_client_hangye`
--

CREATE TABLE IF NOT EXISTS `crm_client_hangye` (
  `id` int(11) NOT NULL,
  `hy_name` varchar(255) DEFAULT NULL,
  `add_time` bigint(20) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `crm_client_order`
--

CREATE TABLE IF NOT EXISTS `crm_client_order` (
  `id` int(11) NOT NULL COMMENT '主键id',
  `pr_user` varchar(100) NOT NULL COMMENT '负责人名称',
  `cphone` varchar(100) NOT NULL COMMENT '客户手机号',
  `cname` varchar(100) NOT NULL COMMENT '客户名称',
  `money` float(100,0) NOT NULL COMMENT '订单金额',
  `status` varchar(20) NOT NULL DEFAULT '1' COMMENT '业绩状态 1 待审核 2 审核不通过 3 审核通过',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `ticheng` float(100,0) DEFAULT NULL COMMENT '提成金额'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `crm_client_rank`
--

CREATE TABLE IF NOT EXISTS `crm_client_rank` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `rank_name` varchar(100) NOT NULL COMMENT '客户级别名称',
  `add_time` bigint(20) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='CRM客户级别表';

--
-- 转存表中的数据 `crm_client_rank`
--

INSERT INTO `crm_client_rank` (`id`, `rank_name`, `add_time`) VALUES
(10, 'A类客户', 1586770146),
(11, 'B类客户', 1586770153),
(12, 'C类客户', 1586770159);

-- --------------------------------------------------------

--
-- 表的结构 `crm_client_status`
--

CREATE TABLE IF NOT EXISTS `crm_client_status` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `status_name` varchar(100) NOT NULL COMMENT '客户状态名称',
  `add_time` bigint(20) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='CRM客户状态表';

--
-- 转存表中的数据 `crm_client_status`
--

INSERT INTO `crm_client_status` (`id`, `status_name`, `add_time`) VALUES
(9, '已上访', 1586770186),
(10, '未上访', 1586770192);

-- --------------------------------------------------------

--
-- 表的结构 `crm_clues_area`
--

CREATE TABLE IF NOT EXISTS `crm_clues_area` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `area_name` varchar(100) NOT NULL COMMENT '地区来源',
  `add_time` bigint(20) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='CRM地区来源表';

-- --------------------------------------------------------

--
-- 表的结构 `crm_clues_source`
--

CREATE TABLE IF NOT EXISTS `crm_clues_source` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `source_name` varchar(100) NOT NULL COMMENT ' 线索来源名称',
  `add_time` bigint(20) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='CRM线索来源表';

--
-- 转存表中的数据 `crm_clues_source`
--

INSERT INTO `crm_clues_source` (`id`, `source_name`, `add_time`) VALUES
(11, '抖音', 1586828605),
(12, '头条', 1586828610),
(13, '58同城', 1587548427);

-- --------------------------------------------------------

--
-- 表的结构 `crm_clues_status`
--

CREATE TABLE IF NOT EXISTS `crm_clues_status` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `status_name` varchar(100) NOT NULL COMMENT '线索状态名称',
  `add_time` bigint(20) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='CRM线索状态表';

--
-- 转存表中的数据 `crm_clues_status`
--

INSERT INTO `crm_clues_status` (`id`, `status_name`, `add_time`) VALUES
(4, '有效', 1586828670),
(5, '无效', 1597506884);

-- --------------------------------------------------------

--
-- 表的结构 `crm_comment`
--

CREATE TABLE IF NOT EXISTS `crm_comment` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `leads_id` int(11) NOT NULL COMMENT '被评论的内容ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `reply_msg` text NOT NULL COMMENT '回复内容',
  `create_date` bigint(20) NOT NULL COMMENT '回复时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='评论表';

-- --------------------------------------------------------

--
-- 表的结构 `crm_leads`
--

CREATE TABLE IF NOT EXISTS `crm_leads` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `xs_name` varchar(60) DEFAULT NULL COMMENT '线索名称',
  `phone` varchar(20) NOT NULL COMMENT '联系方式',
  `xs_status` varchar(100) DEFAULT NULL COMMENT '线索_状态',
  `last_up_records` varchar(200) DEFAULT NULL COMMENT '最新跟进记录',
  `last_up_time` datetime DEFAULT NULL COMMENT '实际跟进时间',
  `next_up_time` datetime DEFAULT NULL COMMENT '下次跟进时间  ',
  `remark` varchar(600) DEFAULT NULL COMMENT '备注',
  `wechat` varchar(30) DEFAULT NULL COMMENT '微信号',
  `xs_source` varchar(200) DEFAULT NULL COMMENT '线索_来源',
  `xs_area` varchar(100) DEFAULT NULL COMMENT '地区来源',
  `at_user` varchar(100) DEFAULT NULL COMMENT '创建人',
  `at_time` datetime DEFAULT NULL COMMENT '创建时间',
  `ut_time` datetime DEFAULT NULL COMMENT '更新时间',
  `pr_user` varchar(30) DEFAULT NULL COMMENT '负责人',
  `pr_user_bef` varchar(30) DEFAULT NULL COMMENT '前负责人',
  `pr_dep` varchar(30) DEFAULT NULL COMMENT '所属部门      不使用 ',
  `pr_dep_bef` varchar(30) DEFAULT NULL COMMENT '前所属部门   不使用 ',
  `to_kh_time` datetime DEFAULT NULL COMMENT '转客户时间',
  `to_gh_time` datetime DEFAULT NULL COMMENT '转公海时间',
  `pr_gh_type` varchar(200) DEFAULT NULL COMMENT '所属公海',
  `kh_name` varchar(100) DEFAULT NULL COMMENT '客户名称',
  `kh_contact` varchar(100) DEFAULT NULL COMMENT '客户联系人',
  `kh_hangye` varchar(255) DEFAULT NULL COMMENT '行业类别',
  `kh_rank` varchar(100) DEFAULT NULL COMMENT '客户级别',
  `kh_status` varchar(100) DEFAULT NULL COMMENT '客户状态',
  `kh_need` varchar(600) DEFAULT NULL COMMENT '客户需求   不使用',
  `status` varchar(30) DEFAULT '0' COMMENT '0-线索，1-客户，2-公海，3-删除',
  `issuccess` int(3) NOT NULL DEFAULT '-1' COMMENT '是否成交 1成交 -1未成交',
  `kh_username` varchar(100) DEFAULT NULL COMMENT '客户用户名',
  `ispublic` int(2) DEFAULT '1' COMMENT '1 公共 2 个人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='CRM线索单表';

-- --------------------------------------------------------

--
-- 表的结构 `crm_liberum_type`
--

CREATE TABLE IF NOT EXISTS `crm_liberum_type` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `type_name` varchar(200) NOT NULL COMMENT '公海类型名称',
  `add_time` bigint(20) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='公海类型表';

-- --------------------------------------------------------

--
-- 表的结构 `crm_reply`
--

CREATE TABLE IF NOT EXISTS `crm_reply` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `comment_id` int(11) NOT NULL COMMENT '评论ID',
  `from_user_id` int(11) NOT NULL COMMENT '回复人',
  `to_user_id` int(11) NOT NULL COMMENT '回复对象',
  `reply_msg` text NOT NULL COMMENT '回复内容',
  `create_date` bigint(20) NOT NULL COMMENT '回复时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `debris`
--

CREATE TABLE IF NOT EXISTS `debris` (
  `id` int(6) NOT NULL,
  `type_id` int(6) DEFAULT NULL COMMENT '碎片分类ID',
  `title` varchar(120) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `addtime` int(13) DEFAULT NULL COMMENT '添加时间',
  `sort` int(11) DEFAULT '50' COMMENT '排序',
  `url` varchar(120) DEFAULT '' COMMENT '链接',
  `pic` varchar(120) DEFAULT '' COMMENT '图片'
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `debris`
--

INSERT INTO `debris` (`id`, `type_id`, `title`, `content`, `addtime`, `sort`, `url`, `pic`) VALUES
(15, 1, '我们的差异化', '<p><span style="text-align: center;">CLTPHP内容管理系统给您自由的模型构建权利，让您的想法通过您亲自操作实现。不要再为传统的数据库字段限制而发愁。一步删除，一步增加，您想要的，就是这一步。</span></p>', 1503293255, 2, '', ''),
(16, 1, '完整的建站理念', '<p><span style="text-align: center;">CLTPHP可以轻松构建模型，让数据库随心而动，让内容表单随意而变。模型和栏目的绑定，是为了让前台页面能更好的为您的想法服务，让您不再为建站留下遗憾。</span></p>', 1503293273, 1, '', ''),
(17, 1, '简单、高效、低门槛', '<p><span style="text-align: center;">CLTPHP内容管理系统，全程鼠标操作，不用手动建立数据库，不用画网站结构图，也不用打开编译器。网站后台直接</span><span style="text-align: center;">编辑</span><span style="text-align: center;">模版，让网站建设达到前所未有的极致简单。</span></p>', 1503293291, 3, '', ''),
(18, 1, '  是大法官', '', 1581319206, 50, '地方', ''),
(19, 1, '单方事故', '', 1581319325, 50, '电风扇', '');

-- --------------------------------------------------------

--
-- 表的结构 `debris_type`
--

CREATE TABLE IF NOT EXISTS `debris_type` (
  `id` int(11) NOT NULL,
  `title` varchar(120) DEFAULT NULL,
  `sort` int(1) DEFAULT '50'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `debris_type`
--

INSERT INTO `debris_type` (`id`, `title`, `sort`) VALUES
(1, '【首页】中部碎片', 1),
(2, '玫瑰花玫瑰花密码本命年避免', 56),
(3, '地方', 50);

-- --------------------------------------------------------

--
-- 表的结构 `donation`
--

CREATE TABLE IF NOT EXISTS `donation` (
  `id` int(11) NOT NULL COMMENT '自增ID',
  `name` varchar(120) NOT NULL DEFAULT '' COMMENT '用户名',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '捐赠金额',
  `addtime` varchar(15) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `download`
--

CREATE TABLE IF NOT EXISTS `download` (
  `id` int(11) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `title` varchar(120) NOT NULL DEFAULT '',
  `title_style` varchar(225) NOT NULL DEFAULT '',
  `thumb` varchar(225) NOT NULL DEFAULT '',
  `keywords` varchar(120) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `content` text NOT NULL,
  `template` varchar(40) NOT NULL DEFAULT '',
  `posid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `readgroup` varchar(100) NOT NULL DEFAULT '',
  `readpoint` smallint(5) NOT NULL DEFAULT '0',
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `files` varchar(80) NOT NULL DEFAULT '',
  `ext` varchar(255) NOT NULL DEFAULT 'zip',
  `size` varchar(255) NOT NULL DEFAULT '',
  `downs` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `feast`
--

CREATE TABLE IF NOT EXISTS `feast` (
  `id` int(4) NOT NULL COMMENT '自增ID',
  `title` varchar(120) DEFAULT '' COMMENT '标题',
  `open` int(1) DEFAULT '1' COMMENT '是否开启',
  `sort` int(4) DEFAULT '50' COMMENT '排序',
  `addtime` varchar(15) DEFAULT NULL COMMENT '添加时间',
  `feast_date` varchar(20) DEFAULT '' COMMENT '节日日期',
  `type` int(1) DEFAULT '1' COMMENT '1阳历 2农历'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='节日列表';

--
-- 转存表中的数据 `feast`
--

INSERT INTO `feast` (`id`, `title`, `open`, `sort`, `addtime`, `feast_date`, `type`) VALUES
(2, '圣诞节', 1, 50, '1513304012', '12-25', 1),
(3, '中秋节', 1, 2, '1513317857', '07-12', 1),
(4, '七夕', 1, 50, '1532420762', '07-24', 1);

-- --------------------------------------------------------

--
-- 表的结构 `feast_element`
--

CREATE TABLE IF NOT EXISTS `feast_element` (
  `id` int(5) NOT NULL COMMENT '自增ID',
  `pid` int(4) DEFAULT NULL COMMENT '父级ID',
  `title` varchar(120) DEFAULT NULL COMMENT '标题',
  `css` text COMMENT 'CSS',
  `js` text COMMENT 'JS',
  `sort` int(5) DEFAULT '50' COMMENT '排序',
  `open` int(1) DEFAULT '1' COMMENT '是否开启',
  `addtime` varchar(15) DEFAULT NULL COMMENT '添加时间'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='节日元素表';

--
-- 转存表中的数据 `feast_element`
--

INSERT INTO `feast_element` (`id`, `pid`, `title`, `css`, `js`, `sort`, `open`, `addtime`) VALUES
(1, 2, '内容雪人', '#content-wrapper{position: relative;}\n#top-left img{width: 150px;}\n#top-left{position: absolute;top: 30px;left: -145px;}', '$("#content-wrapper").append("<div id=top-left><img src=/static/feast/christmas/top-left.png></div>");', 1, 1, '1513309235'),
(2, 2, '主页右下角驯鹿', '#body-right-bottom{position: fixed;bottom: 0;right: 20px;z-index:51}\n#body-right-bottom img{width: 400px;}', '$("body").append("<div id=body-right-bottom><img src=/static/feast/christmas/body-right-bottom.png></div>");', 2, 1, '1513309340'),
(3, 2, '主页左下角圣诞树', '#body-left-bottom{position: fixed;bottom: 0;left:0;z-index:51}\n#body-left-bottom img{width: 200px;}', ' $("body").append("<div id=body-left-bottom><img src=/static/feast/christmas/body-left-bottom.png></div>");', 3, 1, '1513309488'),
(4, 2, '主页右上角铃铛', '#body-top-right{position: fixed;top: 0;right:0;z-index: 100;}\n#body-top-right img{width: 120px;}', ' $("body").append("<div id=body-top-right><img src=/static/feast/christmas/body-top-right.png></div>");', 4, 1, '1513309568'),
(5, 2, '主页左中部圣诞老人', '#body-left-center{position: fixed;top: 300px;left: 0;z-index: 100;}\n#body-left-center img{width: 220px;}', '$("body").append("<div id=body-left-center><img src=/static/feast/christmas/body-left-center.png></div>");', 5, 1, '1513309625'),
(6, 2, '下载栏树叶', '.rfeatured-box{position: relative;}\n#right-one-top-right img{width: 60px;}\n#right-one-top-right{position: absolute;top: 0;right: -10px;}', ' $(".featured-box").append("<div id=right-one-top-right><img src=/static/feast/christmas/right-one-top-right.png></div>");', 6, 1, '1513309980'),
(7, 2, '导航栏雪景', 'header{position: relative;}\n#nav-bg img{}\n#nav-bg{position: absolute;bottom: -15px;height:30px;left: 0;width: 100%;background: url("/static/feast/christmas/nav-bg.png")repeat-x; z-index:50}', '$("header").append("<div id=nav-bg><img src=/static/feast/christmas/nav-bg.png></div>");', 7, 1, '1513310236'),
(8, 2, '主页背景', 'body{background: url("/static/feast/christmas/zbg.png") no-repeat 100% 100%;background-size: 100%;}', '', 50, 1, '1513310497'),
(10, 3, '主页左下角房子', '#body-left-bottom{position: fixed;bottom: 0;left:0;}\n#body-left-bottom img{width: 200px;}', ' $("body").append("<div id=body-left-bottom><img src=/static/feast/zhongqiu/body-left-bottom.png></div>");', 50, 1, '1513320275'),
(11, 3, '左上角文字', '#body-top-left{position: fixed;top:0;left0;z-index: 100;}\n#body-top-left img{width: 350px;}', ' $("body").append("<div id=body-top-left><img src=/static/feast/zhongqiu/body-top-left.png?111></div>");', 50, 1, '1513320569'),
(12, 3, '右上角嫦娥', '#body-top-right{position: fixed;top: 0;right:0;z-index: 100;}\n#body-top-right img{width: 300px;}', ' $("body").append("<div id=body-top-right><img src=/static/feast/zhongqiu/body-top-right.png></div>");', 50, 1, '1513321010'),
(13, 4, '右上角喜鹊', '#body-top-right{position: fixed;top: 0;right:0;z-index: 100;}\n#body-top-right img{width: 300px;}', ' $("body").append("<div id=body-top-right><img src=/static/feast/qixi/bird.png></div>");', 1, 1, '1528689869');

-- --------------------------------------------------------

--
-- 表的结构 `field`
--

CREATE TABLE IF NOT EXISTS `field` (
  `id` smallint(5) unsigned NOT NULL,
  `moduleid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `field` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(30) NOT NULL DEFAULT '',
  `tips` varchar(150) NOT NULL DEFAULT '',
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `minlength` int(10) unsigned NOT NULL DEFAULT '0',
  `maxlength` int(10) unsigned NOT NULL DEFAULT '0',
  `pattern` varchar(255) NOT NULL DEFAULT '',
  `errormsg` varchar(255) NOT NULL DEFAULT '',
  `class` varchar(20) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `setup` text,
  `ispost` tinyint(1) NOT NULL DEFAULT '0',
  `unpostgroup` varchar(60) NOT NULL DEFAULT '',
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=174 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `field`
--

INSERT INTO `field` (`id`, `moduleid`, `field`, `name`, `tips`, `required`, `minlength`, `maxlength`, `pattern`, `errormsg`, `class`, `type`, `setup`, `ispost`, `unpostgroup`, `sort`, `status`, `issystem`) VALUES
(1, 1, 'title', '标题', '', 1, 1, 80, 'defaul', '标题必须为1-80个字符', 'title', 'title', 'array (\n  ''thumb'' => ''1'',\n  ''style'' => ''1'',\n)', 1, '', 1, 1, 1),
(2, 1, 'hits', '点击次数', '', 0, 0, 8, '', '', '', 'number', 'array (\n  ''size'' => ''10'',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => ''0'',\n)', 1, '', 8, 0, 0),
(3, 1, 'createtime', '发布时间', '', 1, 0, 0, 'date', '', '', 'datetime', '', 1, '', 97, 1, 1),
(4, 1, 'template', '模板', '', 0, 0, 0, '', '', '', 'template', '', 1, '', 99, 1, 1),
(5, 1, 'status', '状态', '', 0, 0, 0, 'defaul', '', 'status', 'radio', 'array (\n  ''options'' => ''发布|1\n定时发布|0'',\n  ''fieldtype'' => ''varchar'',\n  ''numbertype'' => ''1'',\n  ''default'' => ''1'',\n)', 0, '', 98, 1, 1),
(6, 1, 'content', '内容', '', 1, 0, 0, 'defaul', '', 'content', 'editor', 'array (\n  ''edittype'' => ''wangEditor'',\n)', 0, '', 3, 0, 0),
(7, 2, 'catid', '栏目', '', 1, 1, 6, '', '必须选择一个栏目', '', 'catid', '', 1, '', 1, 1, 1),
(8, 2, 'title', '标题', '', 1, 1, 80, 'defaul', '标题必须为1-80个字符', 'title', 'title', 'array (\n  ''thumb'' => ''1'',\n  ''style'' => ''1'',\n)', 1, '', 2, 1, 1),
(9, 2, 'keywords', '关键词', '', 0, 0, 80, '', '', '', 'text', 'array (\n  ''size'' => ''55'',\n  ''default'' => '''',\n  ''ispassword'' => ''0'',\n  ''fieldtype'' => ''varchar'',\n)', 1, '', 3, 1, 1),
(10, 2, 'description', 'SEO简介', '', 0, 0, 0, '', '', '', 'textarea', 'array (\n  ''fieldtype'' => ''mediumtext'',\n  ''rows'' => ''4'',\n  ''cols'' => ''55'',\n  ''default'' => '''',\n)', 1, '', 4, 1, 1),
(11, 2, 'content', '内容', '', 0, 0, 0, 'defaul', '', 'content', 'editor', 'array (\n  ''edittype'' => ''UEditor'',\n)', 1, '', 6, 1, 1),
(12, 2, 'createtime', '发布时间', '', 1, 0, 0, 'date', '', 'createtime', 'datetime', '', 1, '', 6, 1, 1),
(13, 2, 'recommend', '允许评论', '', 0, 0, 1, '', '', '', 'radio', 'array (\n  ''options'' => ''允许评论|1\r\n不允许评论|0'',\n  ''fieldtype'' => ''tinyint'',\n  ''numbertype'' => ''1'',\n  ''labelwidth'' => '''',\n  ''default'' => '''',\n)', 1, '', 10, 0, 0),
(14, 2, 'readpoint', '阅读收费', '', 0, 0, 5, '', '', '', 'number', 'array (\n  ''size'' => ''5'',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => ''0'',\n)', 1, '', 11, 0, 0),
(15, 2, 'hits', '点击次数', '', 0, 0, 8, '', '', '', 'number', 'array (\n  ''size'' => ''10'',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => ''0'',\n)', 1, '', 12, 1, 0),
(16, 2, 'readgroup', '访问权限', '', 0, 0, 0, '', '', '', 'groupid', 'array (\n  ''inputtype'' => ''checkbox'',\n  ''fieldtype'' => ''tinyint'',\n  ''labelwidth'' => ''85'',\n  ''default'' => '''',\n)', 1, '', 13, 1, 1),
(17, 2, 'posid', '推荐位', '', 0, 0, 0, 'defaul', '', 'posid', 'posid', '', 1, '', 14, 1, 1),
(18, 2, 'template', '模板', '', 0, 0, 0, '', '', '', 'template', '', 1, '', 15, 1, 1),
(19, 2, 'status', '状态', '', 0, 0, 0, 'defaul', '', 'status', 'radio', 'array (\n  ''options'' => ''发布|1\n定时发布|2'',\n  ''fieldtype'' => ''varchar'',\n  ''numbertype'' => ''1'',\n  ''default'' => ''1'',\n)', 1, '', 7, 1, 1),
(20, 3, 'catid', '栏目', '', 1, 1, 6, '', '必须选择一个栏目', '', 'catid', '', 1, '', 1, 1, 1),
(21, 3, 'title', '标题', '', 1, 1, 80, 'defaul', '标题必须为1-80个字符', '', 'title', 'array (\n  ''thumb'' => ''0'',\n  ''style'' => ''0'',\n)', 1, '', 2, 1, 1),
(22, 3, 'keywords', '关键词', '', 0, 0, 80, '', '', '', 'text', 'array (\n  ''size'' => ''55'',\n  ''default'' => '''',\n  ''ispassword'' => ''0'',\n  ''fieldtype'' => ''varchar'',\n)', 1, '', 3, 1, 1),
(23, 3, 'description', 'SEO简介', '', 0, 0, 0, '', '', '', 'textarea', 'array (\n  ''fieldtype'' => ''mediumtext'',\n  ''rows'' => ''4'',\n  ''cols'' => ''55'',\n  ''default'' => '''',\n)', 1, '', 4, 1, 1),
(24, 3, 'content', '内容', '', 0, 0, 0, 'defaul', '', 'content', 'editor', 'array (\n  ''edittype'' => ''layedit'',\n)', 1, '', 7, 1, 1),
(25, 3, 'createtime', '发布时间', '', 1, 0, 0, 'date', '', '', 'datetime', '', 1, '', 8, 1, 1),
(26, 3, 'recommend', '允许评论', '', 0, 0, 1, '', '', '', 'radio', 'array (\n  ''options'' => ''允许评论|1\r\n不允许评论|0'',\n  ''fieldtype'' => ''tinyint'',\n  ''numbertype'' => ''1'',\n  ''labelwidth'' => '''',\n  ''default'' => '''',\n)', 1, '', 10, 0, 0),
(27, 3, 'readpoint', '阅读收费', '', 0, 0, 5, '', '', '', 'number', 'array (\n  ''size'' => ''5'',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => ''0'',\n)', 1, '', 11, 0, 0),
(28, 3, 'hits', '点击次数', '', 0, 0, 8, '', '', '', 'number', 'array (\n  ''size'' => ''10'',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => ''0'',\n)', 1, '', 12, 0, 0),
(29, 3, 'readgroup', '访问权限', '', 0, 0, 0, '', '', '', 'groupid', 'array (\n  ''inputtype'' => ''checkbox'',\n  ''fieldtype'' => ''tinyint'',\n  ''labelwidth'' => ''85'',\n  ''default'' => '''',\n)', 1, '', 13, 0, 1),
(30, 3, 'posid', '推荐位', '', 0, 0, 0, '', '', '', 'posid', '', 1, '', 14, 1, 1),
(31, 3, 'template', '模板', '', 0, 0, 0, '', '', '', 'template', '', 1, '', 15, 1, 1),
(32, 3, 'status', '状态', '', 0, 0, 0, '', '', '', 'radio', 'array (\n  ''options'' => ''发布|1\r\n定时发布|0'',\n  ''fieldtype'' => ''tinyint'',\n  ''numbertype'' => ''1'',\n  ''labelwidth'' => ''75'',\n  ''default'' => ''1'',\n)', 1, '', 9, 1, 1),
(33, 3, 'pic', '图片', '', 1, 0, 0, 'defaul', '', 'pic', 'image', '', 0, '', 5, 1, 0),
(34, 3, 'group', '类型', '', 1, 0, 0, 'defaul', '', 'group', 'select', 'array (\n  ''options'' => ''模型管理|1\n分类管理|2\n内容管理|3'',\n  ''multiple'' => ''0'',\n  ''fieldtype'' => ''varchar'',\n  ''numbertype'' => ''1'',\n  ''size'' => '''',\n  ''default'' => '''',\n)', 0, '', 6, 1, 0),
(35, 4, 'catid', '栏目', '', 1, 1, 6, '', '必须选择一个栏目', '', 'catid', '', 1, '', 1, 1, 1),
(36, 4, 'title', '标题', '', 1, 1, 80, '', '标题必须为1-80个字符', '', 'title', 'array (\n  ''thumb'' => ''1'',\n  ''style'' => ''1'',\n  ''size'' => ''55'',\n)', 1, '', 2, 1, 1),
(37, 4, 'keywords', '关键词', '', 0, 0, 80, '', '', '', 'text', 'array (\n  ''size'' => ''55'',\n  ''default'' => '''',\n  ''ispassword'' => ''0'',\n  ''fieldtype'' => ''varchar'',\n)', 1, '', 3, 1, 1),
(38, 4, 'description', 'SEO简介', '', 0, 0, 0, '', '', '', 'textarea', 'array (\n  ''fieldtype'' => ''mediumtext'',\n  ''rows'' => ''4'',\n  ''cols'' => ''55'',\n  ''default'' => '''',\n)', 1, '', 4, 1, 1),
(39, 4, 'content', '内容', '', 0, 0, 0, 'defaul', '', 'content', 'editor', 'array (\n  ''edittype'' => ''layedit'',\n)', 1, '', 8, 1, 1),
(40, 4, 'createtime', '发布时间', '', 1, 0, 0, 'date', '', '', 'datetime', '', 1, '', 9, 1, 1),
(41, 4, 'status', '状态', '', 0, 0, 0, '', '', '', 'radio', 'array (\n  ''options'' => ''发布|1\r\n定时发布|0'',\n  ''fieldtype'' => ''tinyint'',\n  ''numbertype'' => ''1'',\n  ''labelwidth'' => ''75'',\n  ''default'' => ''1'',\n)', 1, '', 10, 1, 1),
(42, 4, 'recommend', '允许评论', '', 0, 0, 1, '', '', '', 'radio', 'array (\n  ''options'' => ''允许评论|1\r\n不允许评论|0'',\n  ''fieldtype'' => ''tinyint'',\n  ''numbertype'' => ''1'',\n  ''labelwidth'' => '''',\n  ''default'' => '''',\n)', 1, '', 11, 0, 0),
(43, 4, 'readpoint', '阅读收费', '', 0, 0, 5, '', '', '', 'number', 'array (\n  ''size'' => ''5'',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => ''0'',\n)', 1, '', 12, 0, 0),
(44, 4, 'hits', '点击次数', '', 0, 0, 8, '', '', '', 'number', 'array (\n  ''size'' => ''10'',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => ''0'',\n)', 1, '', 13, 0, 0),
(45, 4, 'readgroup', '访问权限', '', 0, 0, 0, '', '', '', 'groupid', 'array (\n  ''inputtype'' => ''checkbox'',\n  ''fieldtype'' => ''tinyint'',\n  ''labelwidth'' => ''85'',\n  ''default'' => '''',\n)', 1, '', 14, 0, 1),
(46, 4, 'posid', '推荐位', '', 0, 0, 0, '', '', '', 'posid', '', 1, '', 15, 1, 1),
(47, 4, 'template', '模板', '', 0, 0, 0, '', '', '', 'template', '', 1, '', 16, 1, 1),
(48, 4, 'price', '价格', '', 1, 0, 0, 'defaul', '', 'price', 'number', 'array (\n  ''size'' => '''',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''2'',\n  ''default'' => ''0.00'',\n)', 0, '', 5, 1, 0),
(49, 4, 'xinghao', '型号', '', 0, 0, 0, 'defaul', '', '', 'text', 'array (\n  ''default'' => '''',\n  ''ispassword'' => ''0'',\n  ''fieldtype'' => ''varchar'',\n)', 0, '', 6, 1, 0),
(50, 4, 'pics', '图组', '', 0, 0, 0, 'defaul', '', 'pics', 'images', '', 0, '', 7, 1, 0),
(51, 5, 'catid', '栏目', '', 1, 1, 6, '', '必须选择一个栏目', '', 'catid', '', 1, '', 1, 1, 1),
(52, 5, 'title', '标题', '', 1, 1, 80, '', '标题必须为1-80个字符', '', 'title', 'array (\n  ''thumb'' => ''1'',\n  ''style'' => ''1'',\n  ''size'' => ''55'',\n)', 1, '', 2, 1, 1),
(53, 5, 'keywords', '关键词', '', 0, 0, 80, '', '', '', 'text', 'array (\n  ''size'' => ''55'',\n  ''default'' => '''',\n  ''ispassword'' => ''0'',\n  ''fieldtype'' => ''varchar'',\n)', 1, '', 3, 1, 1),
(54, 5, 'description', 'SEO简介', '', 0, 0, 0, '', '', '', 'textarea', 'array (\n  ''fieldtype'' => ''mediumtext'',\n  ''rows'' => ''4'',\n  ''cols'' => ''55'',\n  ''default'' => '''',\n)', 1, '', 4, 1, 1),
(55, 5, 'content', '内容', '', 0, 0, 0, 'defaul', '', 'content', 'editor', 'array (\n  ''edittype'' => ''layedit'',\n)', 1, '', 9, 1, 1),
(56, 5, 'createtime', '发布时间', '', 1, 0, 0, 'date', '', 'createtime', 'datetime', '', 1, '', 10, 1, 1),
(57, 5, 'status', '状态', '', 0, 0, 0, '', '', '', 'radio', 'array (\n  ''options'' => ''发布|1\r\n定时发布|0'',\n  ''fieldtype'' => ''tinyint'',\n  ''numbertype'' => ''1'',\n  ''labelwidth'' => ''75'',\n  ''default'' => ''1'',\n)', 1, '', 11, 1, 1),
(58, 5, 'recommend', '允许评论', '', 0, 0, 1, '', '', '', 'radio', 'array (\n  ''options'' => ''允许评论|1\r\n不允许评论|0'',\n  ''fieldtype'' => ''tinyint'',\n  ''numbertype'' => ''1'',\n  ''labelwidth'' => '''',\n  ''default'' => '''',\n)', 1, '', 12, 0, 0),
(59, 5, 'readpoint', '阅读收费', '', 0, 0, 5, '', '', '', 'number', 'array (\n  ''size'' => ''5'',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => ''0'',\n)', 1, '', 13, 0, 0),
(60, 5, 'hits', '点击次数', '', 0, 0, 8, '', '', '', 'number', 'array (\n  ''size'' => ''10'',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => ''0'',\n)', 1, '', 14, 0, 0),
(61, 5, 'readgroup', '访问权限', '', 0, 0, 0, '', '', '', 'groupid', 'array (\n  ''inputtype'' => ''checkbox'',\n  ''fieldtype'' => ''tinyint'',\n  ''labelwidth'' => ''85'',\n  ''default'' => '''',\n)', 1, '', 15, 0, 1),
(62, 5, 'posid', '推荐位', '', 0, 0, 0, '', '', '', 'posid', '', 1, '', 16, 1, 1),
(63, 5, 'template', '模板', '', 0, 0, 0, '', '', '', 'template', '', 1, '', 17, 1, 1),
(64, 5, 'files', '上传文件', '', 0, 0, 0, 'defaul', '', 'files', 'file', 'array (\n  ''upload_allowext'' => ''zip,rar,doc,ppt'',\n)', 0, '', 5, 1, 0),
(65, 5, 'ext', '文档类型', '', 0, 0, 0, 'defaul', '', 'ext', 'text', 'array (\n  ''default'' => ''zip'',\n  ''ispassword'' => ''0'',\n  ''fieldtype'' => ''varchar'',\n)', 0, '', 6, 1, 0),
(66, 5, 'size', '文档大小', '', 0, 0, 0, 'defaul', '', 'size', 'text', 'array (\n  ''default'' => '''',\n  ''ispassword'' => ''0'',\n  ''fieldtype'' => ''varchar'',\n)', 0, '', 7, 1, 0),
(67, 5, 'downs', '下载次数', '', 0, 0, 0, 'defaul', '', '', 'number', 'array (\n  ''size'' => '''',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => '''',\n)', 0, '', 8, 1, 0),
(68, 6, 'title', '标题', '', 1, 1, 80, '', '标题必须为1-80个字符', '', 'title', 'array (\n  ''thumb'' => ''1'',\n  ''style'' => ''1'',\n  ''size'' => ''55'',\n)', 1, '', 2, 1, 1),
(69, 6, 'hits', '点击次数', '', 0, 0, 8, '', '', '', 'number', 'array (\n  ''size'' => ''10'',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => ''0'',\n)', 1, '', 6, 0, 0),
(70, 6, 'createtime', '发布时间', '', 1, 0, 0, 'date', '', '', 'datetime', '', 1, '', 4, 1, 1),
(71, 6, 'template', '模板', '', 0, 0, 0, '', '', '', 'template', '', 1, '', 7, 1, 1),
(72, 6, 'status', '状态', '', 0, 0, 0, '', '', '', 'radio', 'array (\n  ''options'' => ''发布|1\r\n定时发布|0'',\n  ''fieldtype'' => ''tinyint'',\n  ''numbertype'' => ''1'',\n  ''labelwidth'' => ''75'',\n  ''default'' => ''1'',\n)', 1, '', 5, 1, 1),
(73, 6, 'catid', '分类', '', 1, 0, 0, 'defaul', '', 'catid', 'catid', '', 0, '', 1, 1, 0),
(74, 6, 'info', '简介', '', 1, 0, 0, 'defaul', '', 'info', 'editor', 'array (\n  ''edittype'' => ''layedit'',\n)', 0, '', 3, 1, 0),
(75, 2, 'copyfrom', '来源', '', 0, 0, 0, 'defaul', '', 'copyfrom', 'text', 'array (\n  ''default'' => ''CLTPHP'',\n  ''ispassword'' => ''0'',\n  ''fieldtype'' => ''varchar'',\n)', 0, '', 8, 1, 0),
(76, 2, 'fromlink', '来源网址', '', 0, 0, 0, 'defaul', '', 'fromlink', 'text', 'array (\n  ''default'' => ''http://www.cltphp.com/'',\n  ''ispassword'' => ''0'',\n  ''fieldtype'' => ''varchar'',\n)', 0, '', 9, 1, 0),
(160, 2, 'tags', '标签', '', 1, 0, 0, 'defaul', '', 'tags', 'text', 'array (\n  ''default'' => '''',\n  ''ispassword'' => ''0'',\n  ''fieldtype'' => ''varchar'',\n)', 0, '', 5, 1, 0),
(161, 7, 'catid', '栏目', '', 1, 1, 6, '', '必须选择一个栏目', '', 'catid', '', 1, '', 1, 1, 1),
(162, 7, 'title', '标题', '', 1, 1, 80, '', '标题必须为1-80个字符', '', 'title', 'array (\n  ''thumb'' => ''1'',\n  ''style'' => ''1'',\n  ''size'' => ''55'',\n)', 1, '', 2, 1, 1),
(163, 7, 'keywords', '关键词', '', 0, 0, 80, '', '', '', 'text', 'array (\n  ''size'' => ''55'',\n  ''default'' => '''',\n  ''ispassword'' => ''0'',\n  ''fieldtype'' => ''varchar'',\n)', 1, '', 3, 1, 1),
(164, 7, 'description', 'SEO简介', '', 0, 0, 0, '', '', '', 'textarea', 'array (\n  ''fieldtype'' => ''mediumtext'',\n  ''rows'' => ''4'',\n  ''cols'' => ''55'',\n  ''default'' => '''',\n)', 1, '', 4, 1, 1),
(165, 7, 'content', '内容', '', 0, 0, 0, '', '', '', 'editor', 'array (\n  ''toolbar'' => ''full'',\n  ''default'' => '''',\n  ''height'' => '''',\n  ''showpage'' => ''1'',\n  ''enablekeylink'' => ''0'',\n  ''replacenum'' => '''',\n  ''enablesaveimage'' => ''0'',\n  ''flashupload'' => ''1'',\n  ''alowuploadexts'' => '''',\n)', 1, '', 5, 1, 1),
(166, 7, 'createtime', '发布时间', '', 1, 0, 0, 'date', '', 'createtime', 'datetime', '', 1, '', 6, 1, 1),
(167, 7, 'status', '状态', '', 0, 0, 0, '', '', '', 'radio', 'array (\n  ''options'' => ''发布|1\r\n定时发布|0'',\n  ''fieldtype'' => ''tinyint'',\n  ''numbertype'' => ''1'',\n  ''labelwidth'' => ''75'',\n  ''default'' => ''1'',\n)', 1, '', 7, 1, 1),
(168, 7, 'recommend', '允许评论', '', 0, 0, 1, '', '', '', 'radio', 'array (\n  ''options'' => ''允许评论|1\r\n不允许评论|0'',\n  ''fieldtype'' => ''tinyint'',\n  ''numbertype'' => ''1'',\n  ''labelwidth'' => '''',\n  ''default'' => '''',\n)', 1, '', 8, 0, 0),
(169, 7, 'readpoint', '阅读收费', '', 0, 0, 5, '', '', '', 'number', 'array (\n  ''size'' => ''5'',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => ''0'',\n)', 1, '', 9, 0, 0),
(170, 7, 'hits', '点击次数', '', 0, 0, 8, '', '', '', 'number', 'array (\n  ''size'' => ''10'',\n  ''numbertype'' => ''1'',\n  ''decimaldigits'' => ''0'',\n  ''default'' => ''0'',\n)', 1, '', 10, 0, 0),
(171, 7, 'readgroup', '访问权限', '', 0, 0, 0, '', '', '', 'groupid', 'array (\n  ''inputtype'' => ''checkbox'',\n  ''fieldtype'' => ''tinyint'',\n  ''labelwidth'' => ''85'',\n  ''default'' => '''',\n)', 1, '', 11, 0, 1),
(172, 7, 'posid', '推荐位', '', 0, 0, 0, '', '', '', 'posid', '', 1, '', 12, 1, 1),
(173, 7, 'template', '模板', '', 0, 0, 0, '', '', '', 'template', '', 1, '', 13, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `link`
--

CREATE TABLE IF NOT EXISTS `link` (
  `id` int(5) NOT NULL,
  `title` varchar(50) NOT NULL COMMENT '链接名称',
  `url` varchar(200) NOT NULL COMMENT '链接URL',
  `type_id` tinyint(4) DEFAULT NULL COMMENT '所属栏目ID',
  `qq` varchar(20) NOT NULL COMMENT '联系QQ',
  `sort` int(5) NOT NULL DEFAULT '50' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `open` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0禁用1启用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `message_id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT '' COMMENT '留言标题',
  `tel` varchar(15) NOT NULL DEFAULT '' COMMENT '留言电话',
  `addtime` varchar(15) NOT NULL COMMENT '留言时间',
  `open` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1=审核 0=不审核',
  `ip` varchar(50) DEFAULT '' COMMENT '留言者IP',
  `content` longtext NOT NULL COMMENT '留言内容',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(50) NOT NULL COMMENT '留言邮箱'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `id` tinyint(3) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listfields` varchar(255) NOT NULL DEFAULT '',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1'
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `module`
--

INSERT INTO `module` (`id`, `title`, `name`, `description`, `type`, `issystem`, `listfields`, `sort`, `status`) VALUES
(1, '单页模型', 'page', '单页面', 1, 0, '*', 0, 1),
(2, '文章模型', 'article', '新闻文章', 1, 0, '*', 0, 1),
(3, '图片模型', 'picture', '图片展示', 1, 0, '*', 0, 1),
(4, '产品模型', 'product', '产品展示', 1, 0, '*', 0, 1),
(5, '下载模型', 'download', '文件下载', 1, 0, '*', 0, 1),
(6, '团队模型', 'team', '员工展示', 1, 0, '*', 0, 1),
(7, '小程序测试', '11111', '', 1, 0, '11*', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `oauth`
--

CREATE TABLE IF NOT EXISTS `oauth` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `type` varchar(50) DEFAULT NULL COMMENT '账号类型',
  `openid` varchar(120) DEFAULT NULL COMMENT '第三方唯一标示'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) unsigned NOT NULL,
  `title` varchar(80) NOT NULL DEFAULT '',
  `title_style` varchar(225) NOT NULL DEFAULT '',
  `thumb` varchar(225) NOT NULL DEFAULT '',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT '1',
  `userid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `lang` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content` text COMMENT '内容',
  `template` varchar(50) DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `page`
--

INSERT INTO `page` (`id`, `title`, `title_style`, `thumb`, `hits`, `status`, `userid`, `username`, `sort`, `createtime`, `updatetime`, `lang`, `content`, `template`) VALUES
(2, '发过火', 'color:rgb(95, 184, 120);font-weight:normal;', '/uploads/20180611/b78388d0d1b123e06d900c4d1a1d88ba.jpg', 40, '1', 0, '', 0, 1504251653, 0, 0, '<p style="font-size:14px;font-family:;">\n	<a href="http://www.cltphp.com/" target="_blank"><span style="color:#92D050;">CLTPHP内容管理系统</span></a>，包含系统设置，权限管理，模型管理，数据库管理，栏目管理，会员管理，网站功能，模版管理，微信管理等相关模块。基于ThinkPHP5开发，后台采用Layui框架完全自适应，数据交互采用更高效简洁的angularjs实现，。\n</p>\n<p style="font-size:14px;font-family:''Microsoft yahei'', Arial, Tahoma, Verdana;vertical-align:baseline;color:#979797;background-color:#FFFFFF;">\n	