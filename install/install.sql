DROP TABLE IF EXISTS `MN_config`;
CREATE TABLE `MN_config` (
  `id` int(1) NOT NULL AUTO_INCREMENT,   -- 数据库表ID
  `user` varchar(250) NOT NULL,  -- 账号
  `pwd` varchar(250) NOT NULL,  -- 密码
  `gg` mediumtext NOT NULL,    -- 网站公告
  `name` text NOT NULL,     -- 控制面板名称
  `yzm` text NOT NULL,   -- 后台验证码
  `yzme` text NOT NULL,   -- 控制面板验证码
  `wzqk` text NOT NULL,  -- 网站是否开启
  `auther` text NOT NULL,  -- 控制面板logo修改时间
  `kzmbqk` text NOT NULL,   -- 控制面板是否开启
  `apiqk` varchar(20) NOT NULL,  -- API接口是否开启
  `api` varchar(50) NOT NULL,     -- API统一1级密钥
  `qqh` varchar(50) NOT NULL,       -- 站长QQ号
  `date` varchar(50) NOT NULL,     -- 网站建成日期
  `hxw` varchar(250) NOT NULL,     -- FTP操作面板
  `hxe` varchar(250) NOT NULL,     -- 易支付地址
  `hxr` varchar(250) NOT NULL,     -- 易支付ID
  `hxt` varchar(250) NOT NULL,     -- 易支付key
  `hxy` varchar(50) NOT NULL,     -- 后续
  `hxu` text NOT NULL,     -- 开通网站后的默认PHP版本
  `hxi` text NOT NULL,     -- Linux建站的目录
  `hxo` text NOT NULL,     -- Windows建站的目录
  `hxp` text NOT NULL,     -- 控制面板显示版权
  `hxa` text NOT NULL,     -- 后续....
  `hxs` text NOT NULL,     -- 后续....
  `hxd` text NOT NULL,     -- 后续....
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `MN_config`(`id`, `user`, `pwd`, `gg`, `name`, `yzm`,`yzme`,`wzqk`,`auther`,`kzmbqk`, `apiqk`,`api`,`qqh`,`date`,`hxw`, `hxe`, `hxr`, `hxt`, `hxy`, `hxu`,`hxi`,`hxo`,`hxp`,`hxa`, `hxs`,`hxd`) VALUES
('1', 'admin', '123456', '', '', 'true', 'false', '', '', 'true', '', '', '', '', 'mnftp', '', '', '', '', '56', '/www/wwwroot', 'D:/wwwroot', "<a href='./'>Copyright ©梦奈云 2023</a>", '', '', '');



DROP TABLE IF EXISTS `MN_log`;
CREATE TABLE `MN_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,		-- 数据库表ID，日志功能的停用不代表以后不会开启
  `czuser` varchar(250) NOT NULL,			-- 操作用户
  `date` varchar(250) NOT NULL,				-- 操作时间
  `lx` varchar(250) NOT NULL,				-- 操作类型
  `lr` varchar(50) NOT NULL,				-- 操作内容
  `ip` text NOT NULL,					    -- 客户端IP
  `qk` text NOT NULL,					   -- 操作情况
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `MN_bt`;
CREATE TABLE `MN_bt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,		-- 数据库表ID
  `btip` varchar(250) NOT NULL,					-- 宝塔IP
  `btdk` varchar(250) NOT NULL,					-- 宝塔的端口
  `btmy` varchar(250) NOT NULL,					-- 宝塔的密钥
  `date` varchar(50) NOT NULL,					-- 添加时间
  `ktmy` text NOT NULL,							-- 调用时的密钥
  `qmk` text NOT NULL,							-- 二级验证
  `btdh` varchar(250) NOT NULL,					-- 宝塔开通代号
  `btos` INT(10) NOT NULL DEFAULT '1',			-- 宝塔的操作系统(1为Linux,2为Windows)
  `als` varchar(200) NOT NULL,					-- 自定义域名解析地址
  `ftpdz` varchar(50) NOT NULL,					-- 自定义FTP地址
  `ptl` varchar(50) NOT NULL,					-- 是否开启安全访问(true和false)
  `qk` varchar(50) NOT NULL,					-- 目前宝塔情况
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `MN_zj`;
CREATE TABLE `MN_zj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,				-- 数据库表ID
  `ssbt` varchar(250) NOT NULL,						-- 所属宝塔
  `user` varchar(250) NOT NULL,						-- 控制面板账号
  `pass` varchar(50) NOT NULL,						-- 控制面板密码
  `sqluser` text NOT NULL,								-- 数据库账号
  `sqlpass` text NOT NULL,								-- 数据密码
  `sqldz` varchar(50) NOT NULL,						-- 网站名
  `data` varchar(50) NOT NULL,						-- 开通时间
  `datae` varchar(50) NOT NULL,						-- 到期时间
  `qk` varchar(50) NOT NULL,						-- 目前状态
  `btid` varchar(50) NOT NULL,						-- 宝塔内网站id
  `ftpid` varchar(50) NOT NULL,						-- FTP的id
  `ymbds` varchar(50) NOT NULL,						-- 域名最大绑定数
  `hxa` varchar(50) NOT NULL,						-- 网页空间(max最大，dq当前)
  `hxb` varchar(50) NOT NULL,						-- 数据库空间(max最大，dq当前)
  `hxc` varchar(50) NOT NULL,						-- 产品类型（1为CDN2为主机）
  `hxd` varchar(50) NOT NULL,						-- SQLID
  `llmax` text NOT NULL,						-- 最大流量(max最大，dq当前)
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `MN_bs`;			-- 一键部署的可用网站列表
CREATE TABLE `MN_bs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,				-- 数据库表ID
  `name` varchar(250) NOT NULL,						-- 程序名称
  `jc` varchar(250) NOT NULL,						-- 程序介绍
  `src` text NOT NULL,								-- 程序图标位置
  `date` text NOT NULL,								-- 添加时间
  `cxwz` text NOT NULL,								-- 程序位置
  `sxpz` varchar(500) NOT NULL,						-- 所需最低配置(存入数组 网页空间和SQL空间)
  `tj` text NOT NULL,						        -- 使用本程序的主机和总人数
  `jg` varchar(50) NOT NULL,						-- 程序价格
  `inp` text NOT NULL,								-- 用户部署时填写的表单(存储为json)
  `pz` text NOT NULL,								-- 搭建程序时的程序配置(存储为json)
  `alet` text NOT NULL,								-- 部署完成后的弹窗提示
  `qk` varchar(50) NOT NULL,						-- 状态(上架和下架)
  `hxa` varchar(50) NOT NULL,						-- 后续...
  `hxb` varchar(50) NOT NULL,						-- 后续...
  `hxc` varchar(50) NOT NULL,						-- 后续...
  `hxd` varchar(50) NOT NULL,						-- 后续...
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `MN_bs` (`id`, `name`, `jc`, `src`, `date`, `cxwz`, `sxpz`, `tj`, `jg`, `inp`, `pz`, `qk`, `hxa`, `hxb`, `hxc`, `hxd`) VALUES
(1, '彩虹外链网盘', '彩虹外链网盘也称彩虹个人网盘，可以用来保存各种文件<br/>部署完成后默认账号为admin默认密码为123456<br/>默认后台为：域名/admin', '["../filecx/b7b04562/tp/0.png","../filecx/b7b04562/tp/1.png","../filecx/b7b04562/tp/2.png","../filecx/b7b04562/tp/3.png"]', '2022-01-29 21:27:06', '../filecx/b7b04562/cxym.zip', '["10","5"]', '[]', '0.01', '[]', '{"1":{"cz":"xjwj","name":"install.lock","ml":"/install/"},"2":{"cz":"setwj","name":"config.php","ml":"/","nr":"../filecx/b7b04562/setwj/2.setwj"},"3":{"cz":"drsql","name":"install.sql","ml":"/install"},"4":{"cz":"drsql","name":"update.sql","ml":"/install"}}', 'true', '', '', '', '');

DROP TABLE IF EXISTS `MN_ym`;
CREATE TABLE `MN_ym` (
  `id` int(11) NOT NULL AUTO_INCREMENT,		-- 数据库表ID
  `url` varchar(128) NOT NULL,					-- 域名
  `btdh` varchar(250) NOT NULL,					-- 对应的宝塔
  `jg` varchar(250) NOT NULL,					-- 解析价格
  `date` varchar(50) NOT NULL,					-- 添加时间
  `js` varchar(50) NOT NULL,					-- 域名介绍
  `json` text NOT NULL,							-- 绑定了该域名的主机(json)
  `qk` varchar(50) NOT NULL,					-- 目前域名情况
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `MN_dd`;               -- 支付订单
CREATE TABLE `MN_dd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,		-- 数据库表ID
  `cs` varchar(1000) NOT NULL,				-- 传入参数(json)
  `date` varchar(250) NOT NULL,				-- 操作时间
  `zffs` varchar(250) NOT NULL,				-- 支付方式
  `je` varchar(250) NOT NULL,				-- 支付金额
  `ddh` varchar(250) NOT NULL,				-- 订单号
  `lx` varchar(250) NOT NULL,				-- 功能类型
  `qk` varchar(50) NOT NULL,				-- 支付情况
  `ip` text NOT NULL,					    -- 发起者的IP
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `MN_bt` ADD `ftpdz` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `als`;

UPDATE MN_bt SET ftpdz= 'false';

ALTER TABLE `MN_config` ADD `mailhost` VARCHAR(50) NULL DEFAULT NULL COMMENT '邮箱服务器地址';
ALTER TABLE `MN_config` ADD `mailuser` VARCHAR(50) NULL DEFAULT NULL COMMENT '邮箱账号';
ALTER TABLE `MN_config` ADD `mailpassword` VARCHAR(50) NULL DEFAULT NULL COMMENT '邮箱密码';
ALTER TABLE `MN_config` ADD `mailport` VARCHAR(20) NOT NULL DEFAULT '465' COMMENT '邮箱端口';
ALTER TABLE `MN_config` ADD `ymjkkg` VARCHAR(20) NOT NULL DEFAULT 'false' COMMENT '域名监控开关';
ALTER TABLE `MN_config` ADD `mtyxfskg` VARCHAR(20) NOT NULL DEFAULT 'false' COMMENT '每天邮箱发送开关';
ALTER TABLE `MN_config` ADD `ymjktsyz` VARCHAR(20) NOT NULL DEFAULT '7' COMMENT '域名监控天数阈值';
ALTER TABLE `MN_config` ADD `wjjkkg` VARCHAR(20) NOT NULL DEFAULT 'false' COMMENT '文件监控开关';
ALTER TABLE `MN_config` ADD `mtwjfskg` VARCHAR(50) NOT NULL DEFAULT 'false' COMMENT '每天文件发送邮箱开关';
ALTER TABLE `MN_config` ADD `wjjktsyz` VARCHAR(20) NOT NULL DEFAULT '7' COMMENT '文件监控天数阈值';
ALTER TABLE `MN_zj` ADD `backup` VARCHAR(50) NOT NULL DEFAULT '{\"max\":\"3\",\"dq\":0}' COMMENT '备份SQL个数';
ALTER TABLE `MN_zj` ADD `mailuser` VARCHAR(50) NULL DEFAULT NULL COMMENT '主机使用的用户邮箱';
ALTER TABLE `MN_config` ADD `optionzc` VARCHAR(20) NOT NULL DEFAULT 'stop' COMMENT '选择暂停主机还是删除主机';
ALTER TABLE `MN_config` ADD `zjyxbd` VARCHAR(20) NOT NULL DEFAULT 'true' COMMENT '主机邮箱绑定';