/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : jxc_oubao

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-11-12 09:08:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `acm_func2role`
-- ----------------------------
DROP TABLE IF EXISTS `acm_func2role`;
CREATE TABLE `acm_func2role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `menuId` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '对应菜单定义文件中的id',
  `roleId` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FuncId` (`menuId`) USING BTREE,
  KEY `RoleId` (`roleId`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=815 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of acm_func2role
-- ----------------------------

-- ----------------------------
-- Table structure for `acm_funcdb`
-- ----------------------------
DROP TABLE IF EXISTS `acm_funcdb`;
CREATE TABLE `acm_funcdb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(10) NOT NULL DEFAULT '0',
  `funcName` varchar(20) COLLATE utf8_bin NOT NULL,
  `leftId` int(10) NOT NULL DEFAULT '0',
  `rightId` int(10) NOT NULL DEFAULT '0',
  `usedByStandard` tinyint(1) NOT NULL DEFAULT '1' COMMENT '标准本是否可用',
  `usedByJingji` tinyint(1) NOT NULL DEFAULT '1' COMMENT '经济版是否可用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of acm_funcdb
-- ----------------------------

-- ----------------------------
-- Table structure for `acm_roledb`
-- ----------------------------
DROP TABLE IF EXISTS `acm_roledb`;
CREATE TABLE `acm_roledb` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `roleName` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `GroupName` (`roleName`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of acm_roledb
-- ----------------------------

-- ----------------------------
-- Table structure for `acm_sninfo`
-- ----------------------------
DROP TABLE IF EXISTS `acm_sninfo`;
CREATE TABLE `acm_sninfo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sn` varchar(20) NOT NULL,
  `sninfo` varchar(1000) NOT NULL,
  `userId` int(10) NOT NULL COMMENT '用户名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='动态密码卡信息';

-- ----------------------------
-- Records of acm_sninfo
-- ----------------------------

-- ----------------------------
-- Table structure for `acm_user2message`
-- ----------------------------
DROP TABLE IF EXISTS `acm_user2message`;
CREATE TABLE `acm_user2message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userId` int(10) NOT NULL COMMENT '用户Id',
  `messageId` int(10) NOT NULL COMMENT '通知Id',
  `kind` int(1) NOT NULL DEFAULT '0' COMMENT '0表示查看信息，1表示弹出窗但未查看信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户查看通知表';

-- ----------------------------
-- Records of acm_user2message
-- ----------------------------

-- ----------------------------
-- Table structure for `acm_user2role`
-- ----------------------------
DROP TABLE IF EXISTS `acm_user2role`;
CREATE TABLE `acm_user2role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userId` int(10) NOT NULL DEFAULT '0',
  `roleId` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UserId` (`userId`,`roleId`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of acm_user2role
-- ----------------------------

-- ----------------------------
-- Table structure for `acm_user2trader`
-- ----------------------------
DROP TABLE IF EXISTS `acm_user2trader`;
CREATE TABLE `acm_user2trader` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userId` int(10) NOT NULL DEFAULT '0',
  `traderId` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UserId` (`userId`,`traderId`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of acm_user2trader
-- ----------------------------

-- ----------------------------
-- Table structure for `acm_userdb`
-- ----------------------------
DROP TABLE IF EXISTS `acm_userdb`;
CREATE TABLE `acm_userdb` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userName` varchar(10) COLLATE utf8_bin NOT NULL,
  `realName` varchar(10) COLLATE utf8_bin NOT NULL,
  `shenfenzheng` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '身份证号',
  `passwd` varchar(10) COLLATE utf8_bin NOT NULL,
  `lastLoginTime` date NOT NULL COMMENT '最后一次登录日期',
  `loginCnt` int(10) NOT NULL COMMENT '当前日登录次数',
  `sn` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '动态密码卡sn',
  `snInfo` varchar(300) COLLATE utf8_bin NOT NULL COMMENT '动态密码的字符串',
  `phone` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '手机号码',
  PRIMARY KEY (`id`),
  KEY `UserId` (`userName`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of acm_userdb
-- ----------------------------
INSERT INTO `acm_userdb` VALUES ('1', 'admin', '管理员', '', 'admin', '2015-11-12', '1', '', '', '');

-- ----------------------------
-- Table structure for `api_log`
-- ----------------------------
DROP TABLE IF EXISTS `api_log`;
CREATE TABLE `api_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(1) NOT NULL COMMENT '0表示响应1表示调用',
  `success` tinyint(1) NOT NULL COMMENT '是否成功',
  `apiName` varchar(100) NOT NULL COMMENT 'api名称',
  `url` varchar(200) NOT NULL COMMENT '回应地址',
  `params` text NOT NULL COMMENT '传参',
  `msg` text NOT NULL COMMENT '错误信息',
  `calltime` datetime NOT NULL COMMENT '调用时间',
  `retrytime` datetime NOT NULL COMMENT '重试时间',
  `retrytimes` int(4) NOT NULL COMMENT '重试次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6075 DEFAULT CHARSET=utf8 COMMENT='api调用日志';

-- ----------------------------
-- Records of api_log
-- ----------------------------

-- ----------------------------
-- Table structure for `caigou_order`
-- ----------------------------
DROP TABLE IF EXISTS `caigou_order`;
CREATE TABLE `caigou_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `orderCode` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '采购单号',
  `employId` int(10) NOT NULL COMMENT '采购人',
  `supplierId` int(10) NOT NULL COMMENT '供应商Id',
  `orderDate` date NOT NULL COMMENT '采购日期',
  `memo` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `creater` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '创建人',
  `shenhe` varchar(10) COLLATE utf8_bin NOT NULL COMMENT 'yes通过，no未通过，空未审核完成',
  `shTime` int(5) NOT NULL COMMENT '审核次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of caigou_order
-- ----------------------------

-- ----------------------------
-- Table structure for `caigou_order2product`
-- ----------------------------
DROP TABLE IF EXISTS `caigou_order2product`;
CREATE TABLE `caigou_order2product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `caigouId` int(10) NOT NULL COMMENT '采购主表Id',
  `productId` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '取产品编号  为了不想改动量大  所以名字还是用productId',
  `jiaoqi` date NOT NULL COMMENT '交期',
  `cnt` decimal(10,2) NOT NULL COMMENT '要货数',
  `unit` varchar(3) COLLATE utf8_bin NOT NULL COMMENT '单位',
  `cntM` decimal(10,2) NOT NULL COMMENT '米数',
  `danjia` decimal(10,3) NOT NULL COMMENT '单价',
  `rukuOver` tinyint(1) NOT NULL COMMENT '是否入库完成：1：完成  0:未完成',
  `memo` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of caigou_order2product
-- ----------------------------

-- ----------------------------
-- Table structure for `caigou_shengou`
-- ----------------------------
DROP TABLE IF EXISTS `caigou_shengou`;
CREATE TABLE `caigou_shengou` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `shengouCode` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT '申购单号',
  `depId` int(10) NOT NULL COMMENT '部门',
  `employId` int(10) NOT NULL COMMENT '申请人',
  `shengouDate` date NOT NULL COMMENT '申请日期',
  `memo` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `creater` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '创建人',
  `shenhe` varchar(10) COLLATE utf8_bin NOT NULL COMMENT 'yes通过，no未通过，空未审核完成',
  `shTime` int(5) NOT NULL COMMENT '审核次数',
  PRIMARY KEY (`id`),
  KEY `depId` (`depId`),
  KEY `employId` (`employId`),
  KEY `shengouDate` (`shengouDate`),
  KEY `shengouCode` (`shengouCode`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of caigou_shengou
-- ----------------------------

-- ----------------------------
-- Table structure for `caigou_shengou2product`
-- ----------------------------
DROP TABLE IF EXISTS `caigou_shengou2product`;
CREATE TABLE `caigou_shengou2product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `shengouId` int(10) NOT NULL COMMENT '申请主表Id',
  `productId` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '取产品编号  为了不想改动量大  所以名字还是用productId',
  `supplierId` int(10) NOT NULL COMMENT '供应商Id',
  `jiaoqi` date NOT NULL COMMENT '交期',
  `cnt` decimal(10,2) NOT NULL COMMENT '要货数',
  `unit` varchar(3) COLLATE utf8_bin NOT NULL COMMENT '单位',
  `cntM` decimal(10,2) NOT NULL COMMENT '米数',
  `danjia` decimal(10,3) NOT NULL COMMENT '单价',
  `memo` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `caigou2proId` int(10) NOT NULL COMMENT '采购明细Id',
  PRIMARY KEY (`id`),
  KEY `shengouId` (`shengouId`),
  KEY `productId` (`productId`),
  KEY `supplierId` (`supplierId`),
  KEY `caigou2proId` (`caigou2proId`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of caigou_shengou2product
-- ----------------------------

-- ----------------------------
-- Table structure for `caiwu_ar_fapiao`
-- ----------------------------
DROP TABLE IF EXISTS `caiwu_ar_fapiao`;
CREATE TABLE `caiwu_ar_fapiao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fapiaoHead` varchar(20) COLLATE utf8_bin NOT NULL,
  `fapiaoCode` varchar(20) COLLATE utf8_bin NOT NULL,
  `clientId` int(11) NOT NULL COMMENT '客户Id',
  `taitou` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '客户发票抬头',
  `fukuanFangshi` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '付款方式',
  `money` decimal(20,2) NOT NULL DEFAULT '0.00',
  `huilv` decimal(10,4) NOT NULL DEFAULT '1.0000' COMMENT '开票汇率',
  `bizhong` varchar(20) COLLATE utf8_bin NOT NULL,
  `fapiaoDate` date NOT NULL DEFAULT '0000-00-00',
  `memo` text COLLATE utf8_bin NOT NULL,
  `creater` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '创建人',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tax_compName` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '开票抬头',
  PRIMARY KEY (`id`),
  KEY `yixiangId` (`clientId`),
  KEY `fapiaoCode` (`fapiaoCode`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='开票表';

-- ----------------------------
-- Records of caiwu_ar_fapiao
-- ----------------------------

-- ----------------------------
-- Table structure for `caiwu_ar_guozhang`
-- ----------------------------
DROP TABLE IF EXISTS `caiwu_ar_guozhang`;
CREATE TABLE `caiwu_ar_guozhang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) NOT NULL,
  `ord2proId` int(11) NOT NULL,
  `chukuId` int(10) NOT NULL COMMENT '出库id',
  `chuku2proId` int(10) NOT NULL,
  `kind` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '过账类型',
  `productId` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '产品Id',
  `cnt` decimal(10,2) NOT NULL,
  `unit` char(20) COLLATE utf8_bin NOT NULL,
  `danjia` decimal(10,3) NOT NULL,
  `bizhong` char(10) COLLATE utf8_bin NOT NULL COMMENT '币种',
  `huilv` decimal(10,4) NOT NULL DEFAULT '1.0000' COMMENT '汇率',
  `guozhangDate` date NOT NULL,
  `clientId` int(11) NOT NULL,
  `zhekouMoney` decimal(10,3) NOT NULL,
  `_money` decimal(15,3) NOT NULL COMMENT '发生金额，入库单价*数量',
  `money` decimal(15,3) NOT NULL,
  `memo` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `chukuDate` date NOT NULL COMMENT '出库日期',
  `creater` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '创建人',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '时间',
  `cntM` decimal(10,2) NOT NULL COMMENT '数量M',
  PRIMARY KEY (`id`),
  KEY `ord2proId` (`ord2proId`),
  KEY `orderId` (`orderId`),
  KEY `guozhangDate` (`guozhangDate`),
  KEY `clientId` (`clientId`),
  KEY `chuku2proId` (`chuku2proId`)
) ENGINE=MyISAM AUTO_INCREMENT=208 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='应收过账表';

-- ----------------------------
-- Records of caiwu_ar_guozhang
-- ----------------------------

-- ----------------------------
-- Table structure for `caiwu_ar_income`
-- ----------------------------
DROP TABLE IF EXISTS `caiwu_ar_income`;
CREATE TABLE `caiwu_ar_income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '收汇方式',
  `shouhuiCode` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '收汇单号',
  `shouhuiDate` date NOT NULL COMMENT '收汇日期',
  `orderId` int(11) NOT NULL COMMENT '订单id',
  `clientId` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '客户member_id',
  `bizhong` char(10) COLLATE utf8_bin NOT NULL COMMENT '币种',
  `bankId` int(10) NOT NULL COMMENT '银行账户',
  `huilv` decimal(10,4) NOT NULL DEFAULT '1.0000' COMMENT '汇率',
  `money` decimal(20,3) NOT NULL COMMENT '金额',
  `memo` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `creater` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '创建人',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `shouhuiDate` (`shouhuiDate`),
  KEY `yixiangId` (`clientId`)
) ENGINE=MyISAM AUTO_INCREMENT=129 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='收汇登记表';

-- ----------------------------
-- Records of caiwu_ar_income
-- ----------------------------

-- ----------------------------
-- Table structure for `caiwu_yf_fapiao`
-- ----------------------------
DROP TABLE IF EXISTS `caiwu_yf_fapiao`;
CREATE TABLE `caiwu_yf_fapiao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `head` varchar(100) NOT NULL COMMENT '台头',
  `fapiaoCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `supplierId` int(11) NOT NULL COMMENT '加工户',
  `money` decimal(20,3) NOT NULL DEFAULT '0.000',
  `fapiaoDate` date NOT NULL DEFAULT '0000-00-00',
  `memo` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `creater` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '创建人',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fukuanOver` tinyint(1) NOT NULL COMMENT '是否付款完成',
  PRIMARY KEY (`id`),
  KEY `fapiaoCode` (`fapiaoCode`),
  KEY `jghId` (`supplierId`),
  KEY `fapiaoDate` (`fapiaoDate`),
  KEY `head` (`head`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='应付发票';

-- ----------------------------
-- Records of caiwu_yf_fapiao
-- ----------------------------

-- ----------------------------
-- Table structure for `caiwu_yf_fukuan`
-- ----------------------------
DROP TABLE IF EXISTS `caiwu_yf_fukuan`;
CREATE TABLE `caiwu_yf_fukuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `head` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '台头',
  `fukuanCode` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '收汇单号',
  `fukuanDate` date NOT NULL COMMENT '付款日期',
  `supplierId` int(11) NOT NULL COMMENT '供应商id',
  `fkType` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '付款方式',
  `money` decimal(15,3) NOT NULL COMMENT '付款金额',
  `memo` varchar(300) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `creater` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '创建人',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fukuanDate` (`fukuanDate`),
  KEY `jghId` (`supplierId`),
  KEY `head` (`head`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='付款表';

-- ----------------------------
-- Records of caiwu_yf_fukuan
-- ----------------------------

-- ----------------------------
-- Table structure for `caiwu_yf_guozhang`
-- ----------------------------
DROP TABLE IF EXISTS `caiwu_yf_guozhang`;
CREATE TABLE `caiwu_yf_guozhang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rukuId` int(11) NOT NULL,
  `ruku2ProId` int(11) NOT NULL,
  `supplierId` int(10) NOT NULL COMMENT '加工户Id',
  `guozhangDate` date NOT NULL,
  `kind` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '应付款类型',
  `cnt` decimal(10,2) NOT NULL,
  `unit` char(20) COLLATE utf8_bin NOT NULL COMMENT '单位',
  `productId` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '花型编号',
  `danjia` decimal(10,3) NOT NULL,
  `money` decimal(15,3) NOT NULL,
  `zhekouMoney` decimal(10,3) NOT NULL COMMENT '折扣金额',
  `huilv` decimal(10,4) NOT NULL DEFAULT '1.0000' COMMENT '汇率',
  `bizhong` char(10) COLLATE utf8_bin NOT NULL COMMENT '币种',
  `qitaMemo` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '批号',
  `memo` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `creater` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '制单人',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '时间',
  `rukuDate` date NOT NULL COMMENT '应付发生日期',
  `_money` decimal(10,3) NOT NULL COMMENT '发生金额，入库单价*数量',
  `cntM` decimal(10,2) NOT NULL COMMENT '数量M',
  PRIMARY KEY (`id`),
  KEY `rukuId` (`rukuId`),
  KEY `guozhangDate` (`guozhangDate`),
  KEY `kind` (`kind`),
  KEY `ruku2proId` (`ruku2ProId`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='应付入账表';

-- ----------------------------
-- Records of caiwu_yf_guozhang
-- ----------------------------

-- ----------------------------
-- Table structure for `cangku_back_plan`
-- ----------------------------
DROP TABLE IF EXISTS `cangku_back_plan`;
CREATE TABLE `cangku_back_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(10) NOT NULL DEFAULT '' COMMENT '完成/拒绝',
  `orderId` int(11) NOT NULL COMMENT '订单id',
  `ord2proId` int(11) NOT NULL COMMENT '订单明细id',
  `orderCode` bigint(20) NOT NULL COMMENT '订单code',
  `bn` varchar(20) NOT NULL COMMENT '货号',
  `productId` varchar(20) NOT NULL COMMENT '产品编号',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `type` int(5) NOT NULL COMMENT '1退2换',
  `add_time` datetime NOT NULL COMMENT '申请时间',
  `num` decimal(13,2) NOT NULL COMMENT '数量',
  `price` decimal(13,3) NOT NULL COMMENT '单价',
  `content` varchar(100) NOT NULL COMMENT '备注',
  `planCode` varchar(20) NOT NULL COMMENT '申请编号',
  `name` varchar(100) NOT NULL COMMENT '商品描述',
  `return_id` varchar(30) NOT NULL COMMENT '退换货流水号',
  PRIMARY KEY (`id`),
  KEY `orderId` (`orderId`),
  KEY `ord2proId` (`ord2proId`),
  KEY `productId` (`productId`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COMMENT='退换货申请表';

-- ----------------------------
-- Records of cangku_back_plan
-- ----------------------------

-- ----------------------------
-- Table structure for `cangku_chuku`
-- ----------------------------
DROP TABLE IF EXISTS `cangku_chuku`;
CREATE TABLE `cangku_chuku` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `kind` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '入库类型',
  `clientId` int(10) NOT NULL COMMENT '会员Id',
  `jiagonghuId` int(10) NOT NULL COMMENT '加工户id',
  `chukuCode` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '出库单号',
  `cangkuName` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '仓库名称',
  `kuweiId` int(10) NOT NULL COMMENT '库位Id',
  `chukuDate` date NOT NULL COMMENT '入库日期',
  `isGuozhang` tinyint(1) NOT NULL COMMENT '是否需要过账:0是1否',
  `isChuRuku` varchar(1) COLLATE utf8_bin NOT NULL COMMENT '出入库标记，0为入库，1为出库',
  `shipping` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '配送方式',
  `corp_name` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '物流公司',
  `ship_name` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '收货人',
  `ship_addr` text COLLATE utf8_bin NOT NULL COMMENT '收货地址',
  `ship_zip` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '收货人邮编',
  `ship_tel` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '收货电话',
  `ship_email` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '收货人email',
  `logi_no` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '物流单号',
  `ship_mobile` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '收货人手机',
  `ship_area` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '收货地区',
  `memo` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `creater` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '创建人',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'dt',
  PRIMARY KEY (`id`),
  KEY `chukuCode` (`chukuCode`),
  KEY `chukuDate` (`chukuDate`),
  KEY `kuweiId` (`kuweiId`),
  KEY `kind` (`kind`)
) ENGINE=MyISAM AUTO_INCREMENT=334 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='出库主表';

-- ----------------------------
-- Records of cangku_chuku
-- ----------------------------

-- ----------------------------
-- Table structure for `cangku_chuku2product`
-- ----------------------------
DROP TABLE IF EXISTS `cangku_chuku2product`;
CREATE TABLE `cangku_chuku2product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) unsigned NOT NULL COMMENT '订单id；更通用，一次出多个订单的或',
  `peihuoId` int(11) NOT NULL COMMENT '配货单id',
  `ord2proId` int(11) unsigned NOT NULL COMMENT '订单明细id',
  `chukuId` int(10) unsigned NOT NULL COMMENT '出库id,pk',
  `productId` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '产品id',
  `backId` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '退换货ID',
  `pihao` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '批号',
  `unit` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '单位M/Y',
  `cnt` decimal(13,2) NOT NULL COMMENT '出库数量',
  `cntM` decimal(13,2) NOT NULL COMMENT '数量（M）',
  `cntJian` int(10) NOT NULL COMMENT '件数',
  `danjia` decimal(15,6) NOT NULL COMMENT '单价',
  `money` decimal(15,2) NOT NULL COMMENT '金额',
  `memoView` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '明细备注',
  `return4id` int(10) NOT NULL COMMENT '退回的时候关联本表id字段',
  `dengji` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '等级',
  `planId` int(10) NOT NULL COMMENT '出库计划Id',
  `Madan` text CHARACTER SET utf8 COMMENT '保存码单的json   为了调拨能保存码单信息',
  `dahuo2proId` int(10) NOT NULL COMMENT '大货的订单明细Id    为大货调入现货并保存配货单用的',
  `isBaofei` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否报废:0是1否',
  `dbId` int(10) NOT NULL COMMENT '取调拨的入库子表的id  加这个字段主要为了解决码单的保存',
  PRIMARY KEY (`id`),
  KEY `chukuId` (`chukuId`) USING BTREE,
  KEY `productId` (`productId`) USING BTREE,
  KEY `ord2proId` (`ord2proId`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=341 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='出库子表';

-- ----------------------------
-- Records of cangku_chuku2product
-- ----------------------------

-- ----------------------------
-- Table structure for `cangku_kucun`
-- ----------------------------
DROP TABLE IF EXISTS `cangku_kucun`;
CREATE TABLE `cangku_kucun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateFasheng` date DEFAULT NULL COMMENT '发生日期',
  `rukuId` int(11) NOT NULL COMMENT '入库表id',
  `chukuId` int(11) NOT NULL COMMENT '出库表id',
  `kind` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '出入库类型',
  `productId` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '产品编号',
  `cnt` decimal(15,2) NOT NULL COMMENT '发生数量,入库为+，出库为-',
  `cntM` decimal(15,2) NOT NULL COMMENT '数量M',
  `cntJian` int(10) NOT NULL COMMENT '件数',
  `danjia` decimal(15,6) NOT NULL COMMENT '单价',
  `cangkuName` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '仓库名称',
  `money` decimal(15,2) NOT NULL COMMENT '金额',
  `kuweiId` int(10) NOT NULL COMMENT '库位Id',
  `unit` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '单位',
  `pihao` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '批号',
  `isBaofei` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否报废:0是1否',
  PRIMARY KEY (`id`),
  KEY `rukuId` (`rukuId`),
  KEY `chukuId` (`chukuId`),
  KEY `productId` (`productId`),
  KEY `kind` (`kind`),
  KEY `kuweiId` (`kuweiId`)
) ENGINE=MyISAM AUTO_INCREMENT=1092 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='仓库库存表';

-- ----------------------------
-- Records of cangku_kucun
-- ----------------------------

-- ----------------------------
-- Table structure for `cangku_ruku`
-- ----------------------------
DROP TABLE IF EXISTS `cangku_ruku`;
CREATE TABLE `cangku_ruku` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `clientId` int(10) NOT NULL COMMENT '会员Id',
  `kind` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '入库类型',
  `caigouId` int(10) NOT NULL COMMENT '采购合同Id',
  `rukuCode` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '入库单号',
  `cangkuName` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '仓库名称',
  `kuweiId` int(10) NOT NULL COMMENT '库位Id',
  `jiagonghuId` int(10) NOT NULL COMMENT '加工户Id',
  `rukuDate` date NOT NULL COMMENT '入库日期',
  `isGuozhang` tinyint(1) NOT NULL COMMENT '是否需要过账:0是1否',
  `memo` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `creater` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '创建人',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'dt',
  PRIMARY KEY (`id`),
  KEY `rukuCode` (`rukuCode`),
  KEY `rukuDate` (`rukuDate`),
  KEY `kuweiId` (`kuweiId`),
  KEY `kind` (`kind`)
) ENGINE=MyISAM AUTO_INCREMENT=253 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='入库主表';

-- ----------------------------
-- Records of cangku_ruku
-- ----------------------------

-- ----------------------------
-- Table structure for `cangku_ruku2product`
-- ----------------------------
DROP TABLE IF EXISTS `cangku_ruku2product`;
CREATE TABLE `cangku_ruku2product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) NOT NULL COMMENT '订单ID',
  `ord2proId` int(11) NOT NULL COMMENT '订单明细ID',
  `rukuId` int(10) NOT NULL COMMENT '入库主表id',
  `productId` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '产品编号',
  `backId` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '退换货ID关联cangku_back_plan',
  `cnt` decimal(15,2) NOT NULL COMMENT '入库数',
  `cntM` decimal(15,2) NOT NULL COMMENT '数量（M）',
  `cntJian` int(10) NOT NULL COMMENT '件数',
  `unit` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '单位',
  `memoView` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '明细备注',
  `danjia` decimal(15,6) NOT NULL COMMENT '单价',
  `return4id` int(11) NOT NULL COMMENT '退库：cangku_ruku_son表关联id',
  `money` decimal(10,2) NOT NULL COMMENT '金额',
  `cai2proId` int(10) NOT NULL COMMENT '采购合同明细id',
  `pihao` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '批号',
  `dbId` int(10) NOT NULL COMMENT '调拨Id',
  `cairu2proId` int(10) NOT NULL COMMENT '采购入库Id   用于采购退货',
  `dahuo2proId` int(10) NOT NULL COMMENT '大货的订单明细Id    为大货调入现货并保存配货单用的',
  PRIMARY KEY (`id`),
  KEY `productId` (`productId`),
  KEY `rukuId` (`rukuId`)
) ENGINE=MyISAM AUTO_INCREMENT=245 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='仓库入库子表';

-- ----------------------------
-- Records of cangku_ruku2product
-- ----------------------------

-- ----------------------------
-- Table structure for `chuku_plan`
-- ----------------------------
DROP TABLE IF EXISTS `chuku_plan`;
CREATE TABLE `chuku_plan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kind` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '仓库：样品，现货，大货',
  `productId` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '产品编号',
  `orderId` int(10) NOT NULL COMMENT '客户member_id',
  `ord2proId` int(11) NOT NULL COMMENT '订单明细id',
  `peihuoId` int(11) NOT NULL COMMENT '配货单id',
  `cnt` decimal(13,2) NOT NULL COMMENT '数量',
  `unit` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '单位：M/Y',
  `cntM` decimal(13,2) NOT NULL COMMENT 'M数',
  `danjia` decimal(13,3) NOT NULL COMMENT '单价',
  `memo` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `jiaoyan` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '出库校验',
  `status` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '状态',
  `print` int(2) NOT NULL COMMENT '打印次数',
  `planDate` date NOT NULL COMMENT '确认日期',
  `creater` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '确认人',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '时间',
  `jiaoyanDetial` varchar(300) CHARACTER SET utf8 NOT NULL COMMENT '校验的码单条码信息',
  PRIMARY KEY (`id`),
  KEY `productId` (`productId`),
  KEY `ord2proId` (`ord2proId`),
  KEY `peihuoId` (`peihuoId`),
  KEY `planDate` (`planDate`)
) ENGINE=MyISAM AUTO_INCREMENT=122 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='出库单';

-- ----------------------------
-- Records of chuku_plan
-- ----------------------------

-- ----------------------------
-- Table structure for `crontab_log`
-- ----------------------------
DROP TABLE IF EXISTS `crontab_log`;
CREATE TABLE `crontab_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `result` varchar(20) NOT NULL COMMENT '执行结果',
  `desc` varchar(200) NOT NULL COMMENT '描述',
  `address` varchar(200) NOT NULL COMMENT '地址',
  `isActive` varchar(20) NOT NULL DEFAULT '1' COMMENT '是否开启',
  `time` int(15) NOT NULL COMMENT '多少分钟执行',
  `runtime` int(15) NOT NULL COMMENT '计划执行时间戳',
  `createtime` int(15) NOT NULL COMMENT '创建时间戳',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=161 DEFAULT CHARSET=utf8 COMMENT='自动计划任务日志';

-- ----------------------------
-- Records of crontab_log
-- ----------------------------

-- ----------------------------
-- Table structure for `jichu_bank`
-- ----------------------------
DROP TABLE IF EXISTS `jichu_bank`;
CREATE TABLE `jichu_bank` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `itemName` varchar(40) COLLATE utf8_bin NOT NULL,
  `address` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '地址',
  `manger` char(10) COLLATE utf8_bin NOT NULL COMMENT '负责人',
  `tel` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '电话',
  `contacter` char(10) COLLATE utf8_bin NOT NULL COMMENT '联系人',
  `phone` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '营业厅电话',
  `acountCode` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '开户账号',
  `xingzhi` char(10) COLLATE utf8_bin NOT NULL COMMENT '性质(基本户|一般户|税务专用)',
  `memo` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `itemName` (`itemName`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='银行帐号';

-- ----------------------------
-- Records of jichu_bank
-- ----------------------------

-- ----------------------------
-- Table structure for `jichu_client`
-- ----------------------------
DROP TABLE IF EXISTS `jichu_client`;
CREATE TABLE `jichu_client` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `traderId` int(10) NOT NULL COMMENT '本单位联系人',
  `compCode` varchar(40) COLLATE utf8_bin NOT NULL COMMENT '公司编码',
  `com_type` varchar(40) COLLATE utf8_bin NOT NULL COMMENT '客户类型',
  `sex` tinyint(1) NOT NULL COMMENT '性别：0男 1女',
  `compName` varchar(40) CHARACTER SET utf8 NOT NULL COMMENT '公司名称',
  `isGuanwang` tinyint(1) NOT NULL COMMENT '官网用户：0是 1否',
  `quyuCode` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '区域四级编码组合',
  `compDate` date NOT NULL COMMENT '注册时间',
  `diqu` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '地区',
  `mobile` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '手机',
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `dianhua` varchar(40) COLLATE utf8_bin NOT NULL COMMENT '固定电话',
  `address` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '地址',
  `edu` decimal(20,3) NOT NULL COMMENT '信用额度',
  `memo` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `member_id` int(10) unsigned NOT NULL COMMENT '从ec中获取的客户Id',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `compCode` (`compCode`) USING BTREE,
  KEY `traderId` (`traderId`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='客户档案';

-- ----------------------------
-- Records of jichu_client
-- ----------------------------

-- ----------------------------
-- Table structure for `jichu_department`
-- ----------------------------
DROP TABLE IF EXISTS `jichu_department`;
CREATE TABLE `jichu_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `depName` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '部门名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `depName` (`depName`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='部门档案';

-- ----------------------------
-- Records of jichu_department
-- ----------------------------

-- ----------------------------
-- Table structure for `jichu_employ`
-- ----------------------------
DROP TABLE IF EXISTS `jichu_employ`;
CREATE TABLE `jichu_employ` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `employCode` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '员工编码',
  `employName` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '员工名称',
  `codeAtEmploy` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '简称',
  `sex` varchar(2) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '性别',
  `depId` int(10) NOT NULL COMMENT '部门ID',
  `gongzhong` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '工种',
  `fenlei` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '修布工分类',
  `mobile` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '手机',
  `address` varchar(40) COLLATE utf8_bin NOT NULL COMMENT '地址',
  `dateEnter` date NOT NULL COMMENT '入厂时间',
  `isFire` tinyint(1) NOT NULL COMMENT '是否离职：1为是',
  `dateLeave` date NOT NULL COMMENT '离厂时间',
  `shenfenNo` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '身份证号',
  `hetongCode` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '劳动合同号',
  `isCaiyang` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否可以采样',
  `isDayang` tinyint(1) NOT NULL COMMENT '是否打样人',
  `type` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '类别',
  `paixu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `employName` (`employName`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='员工档案';

-- ----------------------------
-- Records of jichu_employ
-- ----------------------------

-- ----------------------------
-- Table structure for `jichu_kuwei`
-- ----------------------------
DROP TABLE IF EXISTS `jichu_kuwei`;
CREATE TABLE `jichu_kuwei` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `kuweiName` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '仓库+库位的名称',
  `memo` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `letters` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '拼音',
  `ckName` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '仓库名称',
  `kwName` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '库位名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='库位';

-- ----------------------------
-- Records of jichu_kuwei
-- ----------------------------

-- ----------------------------
-- Table structure for `jichu_product`
-- ----------------------------
DROP TABLE IF EXISTS `jichu_product`;
CREATE TABLE `jichu_product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `proCode` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '产品编码',
  `proName` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '品名',
  `jingmi` varchar(40) COLLATE utf8_bin NOT NULL COMMENT '经密',
  `weimi` varchar(40) COLLATE utf8_bin NOT NULL COMMENT '纬密',
  `shazhi` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '纱支',
  `color` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '颜色',
  `menfu` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '门幅',
  `zhengli` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '整理方式',
  `kezhong` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '克重',
  `chengfen` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '成分',
  `wuliaoKind` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '物料大类',
  `zuzhi` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '组织大类',
  `danjia` decimal(10,3) NOT NULL COMMENT '基准价格',
  `memo` varchar(100) COLLATE utf8_bin NOT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pinming` (`proName`) USING BTREE,
  KEY `guige` (`jingmi`) USING BTREE,
  KEY `proCode` (`proCode`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='物料档案';

-- ----------------------------
-- Records of jichu_product
-- ----------------------------

-- ----------------------------
-- Table structure for `jichu_supplier`
-- ----------------------------
DROP TABLE IF EXISTS `jichu_supplier`;
CREATE TABLE `jichu_supplier` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `traderId` int(10) NOT NULL COMMENT '本单位联系人',
  `compCode` varchar(40) COLLATE utf8_bin NOT NULL COMMENT '加工户编码',
  `zhujiCode` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '助记码',
  `compName` varchar(40) COLLATE utf8_bin NOT NULL COMMENT '加工户名称',
  `people` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '联系人',
  `tel` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '电话',
  `fax` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '传真',
  `mobile` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '手机',
  `accountId` varchar(40) COLLATE utf8_bin NOT NULL COMMENT '帐号',
  `taxId` varchar(40) COLLATE utf8_bin NOT NULL COMMENT '税号',
  `address` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '地址',
  `memo` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `comeFrom` char(10) COLLATE utf8_bin NOT NULL COMMENT '来源',
  `isStop` tinyint(1) NOT NULL COMMENT '0正常，1停止往来',
  `letters` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '供应商名称转拼音',
  `isJiagong` tinyint(1) NOT NULL COMMENT '是否加工户：0否1是',
  `kind` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '分类',
  PRIMARY KEY (`id`),
  UNIQUE KEY `compName` (`compName`) USING BTREE,
  KEY `compCode` (`compCode`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='供应商档案';

-- ----------------------------
-- Records of jichu_supplier
-- ----------------------------

-- ----------------------------
-- Table structure for `jichu_supplier_taitou`
-- ----------------------------
DROP TABLE IF EXISTS `jichu_supplier_taitou`;
CREATE TABLE `jichu_supplier_taitou` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `supplierId` int(10) NOT NULL COMMENT '坯纱供应商Id',
  `taitou` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '供应商的开票抬头',
  `memo` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='客户发票抬头';

-- ----------------------------
-- Records of jichu_supplier_taitou
-- ----------------------------

-- ----------------------------
-- Table structure for `jichu_wuliao`
-- ----------------------------
DROP TABLE IF EXISTS `jichu_wuliao`;
CREATE TABLE `jichu_wuliao` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wuCode` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '产品编码',
  `wuName` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '品名',
  `wuliaoKind` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '物料大类',
  `guige` varchar(80) COLLATE utf8_bin NOT NULL COMMENT '规格',
  `chengfen` varchar(50) COLLATE utf8_bin NOT NULL,
  `memo` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  UNIQUE KEY `wuCode` (`wuCode`),
  KEY `wuName` (`wuName`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of jichu_wuliao
-- ----------------------------

-- ----------------------------
-- Table structure for `madan_db`
-- ----------------------------
DROP TABLE IF EXISTS `madan_db`;
CREATE TABLE `madan_db` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productId` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '产品ID',
  `millNo` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '批次号',
  `cnt` decimal(13,2) NOT NULL COMMENT '数量',
  `cntM` decimal(13,2) NOT NULL COMMENT '米数',
  `unit` enum('Y','M') COLLATE utf8_bin NOT NULL DEFAULT 'M' COMMENT '单位',
  `rollNo` int(10) NOT NULL COMMENT '卷号',
  `baleNo` int(10) NOT NULL COMMENT '包号',
  `qrcode` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '条码',
  `status` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'active' COMMENT 'lock：已配货并锁定，表示被占用,active表示在仓库中，可用,finish表示已经出库,不可用',
  `memo` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '备注',
  `parentId` int(11) NOT NULL COMMENT '关联其他的码单id',
  `cntFormat` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '数量：支持+号拼接',
  `rukuDate` date DEFAULT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  KEY `productId` (`productId`)
) ENGINE=MyISAM AUTO_INCREMENT=605 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='码单表信息';

-- ----------------------------
-- Records of madan_db
-- ----------------------------

-- ----------------------------
-- Table structure for `madan_rc2madan`
-- ----------------------------
DROP TABLE IF EXISTS `madan_rc2madan`;
CREATE TABLE `madan_rc2madan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rukuId` int(11) NOT NULL COMMENT '入库表id',
  `chukuId` int(11) NOT NULL COMMENT '出库表id',
  `madanId` int(11) NOT NULL COMMENT '码单表id',
  PRIMARY KEY (`id`),
  KEY `rukuId` (`rukuId`),
  KEY `chukuId` (`chukuId`),
  KEY `madanId` (`madanId`)
) ENGINE=MyISAM AUTO_INCREMENT=1069 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='入库出库与码单表的关系表';

-- ----------------------------
-- Records of madan_rc2madan
-- ----------------------------

-- ----------------------------
-- Table structure for `mail_db`
-- ----------------------------
DROP TABLE IF EXISTS `mail_db`;
CREATE TABLE `mail_db` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `senderId` int(10) NOT NULL COMMENT '发件人',
  `accepterId` int(10) NOT NULL COMMENT '收件人',
  `title` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '标题',
  `content` text COLLATE utf8_bin NOT NULL COMMENT '内容',
  `attachment` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '附件',
  `mailCode` int(10) NOT NULL COMMENT '邮件编码，纯数字',
  `dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timeRead` datetime NOT NULL COMMENT '查看日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='邮件';

-- ----------------------------
-- Records of mail_db
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_message`
-- ----------------------------
DROP TABLE IF EXISTS `oa_message`;
CREATE TABLE `oa_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `kindName` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '生产通知或生产变更通知',
  `title` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '标题',
  `content` text COLLATE utf8_bin NOT NULL COMMENT '内容',
  `buildDate` date NOT NULL COMMENT '发布日期',
  `orderId` int(10) NOT NULL COMMENT '订单id',
  `creater` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '创建人',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='生产通知';

-- ----------------------------
-- Records of oa_message
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_message_class`
-- ----------------------------
DROP TABLE IF EXISTS `oa_message_class`;
CREATE TABLE `oa_message_class` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `className` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '类别名称',
  `isWindow` tinyint(1) NOT NULL COMMENT '是否弹出窗0否1是',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oa_message_class
-- ----------------------------

-- ----------------------------
-- Table structure for `ph_peihuo`
-- ----------------------------
DROP TABLE IF EXISTS `ph_peihuo`;
CREATE TABLE `ph_peihuo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `peihuoCode` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '配货单编号，唯一编码',
  `status` enum('finish','dead','active') CHARACTER SET utf8 NOT NULL DEFAULT 'active' COMMENT '锁定状态',
  `status_active` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '启用后状态：未下单，已下单',
  `keys` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '标题，标记，可以方便查询，搜索，相当于标签',
  `peihuoDate` date NOT NULL COMMENT '配货日期',
  `cntM` decimal(13,3) NOT NULL COMMENT '配货单总数量M',
  `productId` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '产品编号',
  `clientId` int(10) NOT NULL COMMENT '客户ID',
  `isDahou` tinyint(1) NOT NULL COMMENT '是否为大货',
  `order2proId` int(10) NOT NULL COMMENT '大货的订单明细Id',
  `ruku2proId` int(10) NOT NULL COMMENT '入库子表Id',
  `creater` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '配货操作人，会员或后台人员',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '当前时间',
  PRIMARY KEY (`id`),
  KEY `productId` (`productId`),
  KEY `clientId` (`clientId`)
) ENGINE=MyISAM AUTO_INCREMENT=228 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='配货单主表';

-- ----------------------------
-- Records of ph_peihuo
-- ----------------------------

-- ----------------------------
-- Table structure for `ph_peihuo2madan`
-- ----------------------------
DROP TABLE IF EXISTS `ph_peihuo2madan`;
CREATE TABLE `ph_peihuo2madan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phId` int(11) unsigned NOT NULL COMMENT '配货主表ID',
  `madanId` int(11) unsigned NOT NULL COMMENT '码单ID',
  PRIMARY KEY (`id`),
  KEY `phId` (`phId`),
  KEY `madanId` (`madanId`)
) ENGINE=MyISAM AUTO_INCREMENT=392 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='配货表与码单表的关系表';

-- ----------------------------
-- Records of ph_peihuo2madan
-- ----------------------------

-- ----------------------------
-- Table structure for `shenhe_db`
-- ----------------------------
DROP TABLE IF EXISTS `shenhe_db`;
CREATE TABLE `shenhe_db` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userId` int(10) NOT NULL COMMENT '审核人Id',
  `nodeId` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '审核节点id，依据节点知道这个是哪个审核功能',
  `status` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '审核状态：空表示未审核，yes表示审核通过，no表示审核拒绝',
  `memo` varchar(500) COLLATE utf8_bin NOT NULL COMMENT '审核备注',
  `tableId` int(10) NOT NULL COMMENT '审核表的id：如审核订单，表示订单表的id',
  `last` char(5) COLLATE utf8_bin NOT NULL COMMENT '是否为最后一个审核节点',
  `nodeName` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '审核名称：config_shenhe文件中键值',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '当前时间',
  PRIMARY KEY (`id`),
  KEY `tableId` (`tableId`),
  KEY `userId` (`userId`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=397 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='审核流程表';

-- ----------------------------
-- Records of shenhe_db
-- ----------------------------

-- ----------------------------
-- Table structure for `shenhe_user2node`
-- ----------------------------
DROP TABLE IF EXISTS `shenhe_user2node`;
CREATE TABLE `shenhe_user2node` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nodeId` varchar(50) COLLATE utf8_bin NOT NULL COMMENT 'config_shenhe 配置中审核节点id',
  `userId` int(10) NOT NULL COMMENT '用户id',
  PRIMARY KEY (`id`),
  KEY `nodeId` (`nodeId`),
  KEY `userId` (`userId`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='审核设置表，设置审核人权限';

-- ----------------------------
-- Records of shenhe_user2node
-- ----------------------------

-- ----------------------------
-- Table structure for `sms_log`
-- ----------------------------
DROP TABLE IF EXISTS `sms_log`;
CREATE TABLE `sms_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isSendOk` tinyint(1) NOT NULL COMMENT '是否发送成功',
  `sendDate` int(10) NOT NULL COMMENT '发送时间',
  `sendKind` int(2) NOT NULL COMMENT '短信状态',
  `returnMsg` varchar(200) NOT NULL COMMENT '返回信息',
  `userName` varchar(20) NOT NULL COMMENT '发送的名字',
  `userId` int(11) NOT NULL COMMENT '发送给的用户Id',
  `aboutId` int(11) NOT NULL COMMENT '关联Id',
  `tel` varchar(50) NOT NULL COMMENT '电话号码',
  `tels` varchar(200) NOT NULL COMMENT '原始电话列表信息(发送批次)',
  `content` varchar(200) NOT NULL COMMENT '发送内容',
  `sendCnt` int(10) NOT NULL COMMENT '短信平台发送的条数',
  `creater` varchar(50) NOT NULL COMMENT '创建人,短信发送操作人',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='短信发送日志';

-- ----------------------------
-- Records of sms_log
-- ----------------------------

-- ----------------------------
-- Table structure for `sms_sender`
-- ----------------------------
DROP TABLE IF EXISTS `sms_sender`;
CREATE TABLE `sms_sender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(11) NOT NULL COMMENT '分组编号',
  `groupName` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '分组名称',
  `index` int(11) NOT NULL COMMENT '组内元素索引',
  `item` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '设置项目',
  `itemName` varchar(50) COLLATE utf8_bin NOT NULL,
  `value` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '值',
  `isActive` int(2) NOT NULL COMMENT '是否启用',
  `time` int(10) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='系统参数设置';

-- ----------------------------
-- Records of sms_sender
-- ----------------------------

-- ----------------------------
-- Table structure for `sys_dbchange_log`
-- ----------------------------
DROP TABLE IF EXISTS `sys_dbchange_log`;
CREATE TABLE `sys_dbchange_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fileName` varchar(40) NOT NULL COMMENT '文件名',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `content` text NOT NULL,
  `memo` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fileName` (`fileName`) USING BTREE,
  KEY `dt` (`dt`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COMMENT='数据补丁执行表';

-- ----------------------------
-- Records of sys_dbchange_log
-- ----------------------------

-- ----------------------------
-- Table structure for `sys_pop`
-- ----------------------------
DROP TABLE IF EXISTS `sys_pop`;
CREATE TABLE `sys_pop` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL COMMENT '标题',
  `content` text NOT NULL,
  `dateFrom` date NOT NULL COMMENT '其实日期',
  `dateTo` date NOT NULL COMMENT '截止日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='工具箱中设置的弹窗信息';

-- ----------------------------
-- Records of sys_pop
-- ----------------------------

-- ----------------------------
-- Table structure for `sys_set`
-- ----------------------------
DROP TABLE IF EXISTS `sys_set`;
CREATE TABLE `sys_set` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `item` varchar(20) COLLATE utf8_bin NOT NULL,
  `itemName` varchar(50) COLLATE utf8_bin NOT NULL,
  `value` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='系统参数设置';

-- ----------------------------
-- Records of sys_set
-- ----------------------------

-- ----------------------------
-- Table structure for `trade_order`
-- ----------------------------
DROP TABLE IF EXISTS `trade_order`;
CREATE TABLE `trade_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单号',
  `orderCode` bigint(20) unsigned NOT NULL COMMENT '订单编号',
  `money` decimal(20,3) NOT NULL DEFAULT '0.000' COMMENT '订单需要支付的总金额',
  `money_order` decimal(20,3) NOT NULL COMMENT '该单商品的价值金额：该金额不一定等于应付款',
  `pmt_goods` decimal(20,3) NOT NULL COMMENT '商品优惠金额',
  `pmt_order` decimal(20,3) NOT NULL COMMENT '订单优惠金额',
  `pay_status` int(11) NOT NULL DEFAULT '0' COMMENT '付款状态:0,1,2,3,4,5',
  `ship_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发货状态:0,1,2,3,4',
  `is_delivery` enum('Y','N') COLLATE utf8_bin NOT NULL DEFAULT 'Y' COMMENT '是否需要发货',
  `is_setover` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否设置完成:0,1',
  `orderTime` datetime NOT NULL COMMENT '下单时间',
  `payment` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '支付方式',
  `clientId` int(10) unsigned NOT NULL COMMENT '会员用户名',
  `currency` varchar(8) COLLATE utf8_bin NOT NULL COMMENT '订单支付货币',
  `shipping` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '配送方式',
  `ship_name` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '收货人',
  `ship_addr` text COLLATE utf8_bin NOT NULL COMMENT '收货地址',
  `ship_zip` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '收货人邮编',
  `ship_tel` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '收货电话',
  `ship_email` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '收货人email',
  `ship_mobile` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '收货人手机',
  `ship_area` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '收货地区',
  `is_tax` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'false' COMMENT '是否要开发票:true,false',
  `tax_type` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'false' COMMENT '发票类型:false,personal,company',
  `tax_content` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '发票内容',
  `cost_tax` decimal(20,3) NOT NULL DEFAULT '0.000' COMMENT '订单税率',
  `tax_company` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '发票抬头',
  `memo` text COLLATE utf8_bin NOT NULL COMMENT '订单备注',
  `cur_rate` decimal(10,4) NOT NULL DEFAULT '1.0000' COMMENT '订单支付货币汇率',
  `status` enum('active','dead','finish') COLLATE utf8_bin NOT NULL DEFAULT 'active' COMMENT '订单状态',
  `cost_freight` decimal(20,3) NOT NULL DEFAULT '0.000' COMMENT '配送费用',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间',
  `traderId` int(10) NOT NULL COMMENT '业务员ID',
  `area` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '用于jxc显示地区',
  `isdelivery_desc` varchar(500) CHARACTER SET utf8 NOT NULL COMMENT '取消发货其他描述',
  `is_tax_over` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发票完成：0否1是',
  PRIMARY KEY (`id`),
  KEY `orderCode` (`orderCode`),
  KEY `orderTime` (`orderTime`) USING BTREE,
  KEY `clientId` (`clientId`),
  KEY `traderId` (`traderId`)
) ENGINE=MyISAM AUTO_INCREMENT=310 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='订单主表';

-- ----------------------------
-- Records of trade_order
-- ----------------------------

-- ----------------------------
-- Table structure for `trade_order2product`
-- ----------------------------
DROP TABLE IF EXISTS `trade_order2product`;
CREATE TABLE `trade_order2product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单明细ID',
  `orderId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `productId` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '取产品编号  为了不想改动量大  所以名字还是用productId',
  `peihuoId` int(10) NOT NULL COMMENT '配货单id，样品没有配货单',
  `danjia` decimal(13,3) NOT NULL COMMENT '明细商品单价,可能有了折扣',
  `danjia_p` decimal(13,3) NOT NULL COMMENT '该商品当时出售原价',
  `cnt` decimal(13,2) NOT NULL COMMENT '明细商品购买数量',
  `unit` varchar(10) COLLATE utf8_bin NOT NULL COMMENT '单位',
  `cntM` decimal(13,2) NOT NULL COMMENT '转换成米的数量',
  `money` decimal(20,3) NOT NULL COMMENT '该商品销售金额，需要支付的金额',
  `kind` varchar(40) CHARACTER SET utf8 NOT NULL COMMENT '销售类型：现货/样品',
  `spec_info` varchar(500) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '物品描述：规格的描述信息',
  `item_type` enum('product','pkg','gift','adjunct') CHARACTER SET utf8 NOT NULL DEFAULT 'product' COMMENT '明细商品类型：商品，赠品等',
  `memo` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '备注信息',
  `shenhe` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '审核状态：yes通过，no未通过，空未审核完成',
  `bn` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT 'ec中货号的全称',
  `peihuoCodes` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '大货的配货单号',
  `is_sms` int(1) NOT NULL DEFAULT '0' COMMENT '是否发送了短信',
  PRIMARY KEY (`id`),
  KEY `orderId` (`orderId`),
  KEY `productId` (`productId`)
) ENGINE=MyISAM AUTO_INCREMENT=334 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='订单明细表';

-- ----------------------------
-- Records of trade_order2product
-- ----------------------------
