-- MySQL dump 10.13  Distrib 5.5.53, for Win32 (AMD64)
--
-- Host: localhost    Database: shop
-- ------------------------------------------------------
-- Server version	5.5.53

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_groups`
--

DROP TABLE IF EXISTS `admin_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_groups` (
  `gid` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `title` varchar(20) NOT NULL COMMENT '角色名称',
  `rights` text NOT NULL COMMENT '角色权限，json',
  PRIMARY KEY (`gid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_groups`
--

LOCK TABLES `admin_groups` WRITE;
/*!40000 ALTER TABLE `admin_groups` DISABLE KEYS */;
INSERT INTO `admin_groups` VALUES (8,'商品管理员','[4,24,25,26,27,28,5,29,30,31,32,33,34,35,36,37,6,38,39,40,41,42,43,44]'),(1,'系统管理员','[1,7,8,9,10,11,12,13,2,21,22,23,3,14,15,16,17,18,19,4,24,25,26,27,28,5,29,30,31,32,33,34,35,36,37,6,38,39,40,41,20,45,42,43,44]');
/*!40000 ALTER TABLE `admin_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_menus`
--

DROP TABLE IF EXISTS `admin_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_menus` (
  `mid` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '上级菜单的 mid',
  `ord` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `title` varchar(20) NOT NULL,
  `controller` varchar(30) NOT NULL COMMENT '控制器名称',
  `method` varchar(30) NOT NULL COMMENT '菜单名称',
  `ishidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏：0正常显示，1隐藏',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0正常，1禁用',
  `isdel` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0' COMMENT '是否已删除：0未删除，1已删除',
  PRIMARY KEY (`mid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_menus`
--

LOCK TABLES `admin_menus` WRITE;
/*!40000 ALTER TABLE `admin_menus` DISABLE KEYS */;
INSERT INTO `admin_menus` VALUES (1,0,0,'管理员','Admins','',0,1,0),(2,0,0,'管理员角色','Roles','',0,1,0),(3,0,0,'菜单管理','Menu','',0,1,0),(4,0,0,'商品分类','Cates','',0,1,0),(5,0,0,'商品管理','Product','',0,1,0),(6,0,0,'幻灯片','Slide','',0,1,0),(7,1,0,'管理员列表','Admins','adminList',0,1,0),(8,1,0,'管理员状态更改','Admins','setStatus',1,1,0),(9,1,0,'管理员编辑','Admins','adminEdit',1,1,0),(10,1,0,'管理员编辑保存','Admins','doEdit',1,1,0),(11,1,0,'管理员添加','Admins','adminAdd',1,1,0),(12,1,0,'管理员添加保存','Admins','doAdd',1,1,0),(13,1,0,'管理用户查询','Admins','select',1,1,0),(14,3,0,'菜单列表','Menu','index',0,1,0),(15,3,0,'菜单添加','Menu','add',1,1,0),(16,3,0,'菜单保存','Menu','save',1,1,0),(17,3,0,'菜单编辑','Menu','edit',1,1,0),(18,3,0,'开关-更新','Menu','set_switch',1,1,0),(19,3,0,'菜单删除','Menu','del',1,1,0),(20,0,0,'系统设置','Site','',0,1,0),(21,2,0,'角色列表','Roles','index',0,1,0),(22,2,0,'角色-保存','Roles','save',1,1,0),(23,2,0,'角色删除','Roles','del',1,1,0),(24,4,0,'分类列表','Cates','index',0,1,0),(25,4,0,'分类添加-保存','Cates','save',1,1,0),(26,4,0,'状态-更新','Cates','set_switch',1,1,0),(27,4,0,'添加规格','Cates','add_py_name',1,1,0),(28,4,0,'保存规格','Cates','py_name_save',1,1,0),(29,5,0,'商品列表','Product','index',0,1,0),(30,5,0,'商品添加','Product','add',1,1,0),(31,5,0,'商品添加-规格添加','Product','get_sku_view',1,1,0),(32,5,0,'图片上传','Product','up_img',1,1,0),(33,5,0,'商品添加-保存','Product','save',1,1,0),(34,5,0,'商品编辑','Product','edit',1,1,0),(35,5,0,'商品编辑-获取规格列表','Product','get_sku_table',1,1,0),(36,5,0,'商品编辑-保存','Product','save_edit',1,1,0),(37,5,0,'商品删除','Product','delete',1,1,0),(38,6,0,'首页首屏','Slide','index',0,1,0),(39,6,0,'首屏添加','Slide','add',1,1,0),(40,6,0,'首屏编辑','Slide','edit',1,1,0),(41,6,0,'首屏删除','Slide','delete',1,1,0),(42,0,0,'后台','Home','',1,1,0),(43,42,0,'后台主页','Home','index',1,1,0),(44,42,0,'欢迎页面','Home','welcome',1,1,0),(45,20,0,'网站设置','Site','index',0,1,0);
/*!40000 ALTER TABLE `admin_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '用户名',
  `pass` varchar(32) NOT NULL COMMENT '密码',
  `truename` varchar(20) NOT NULL COMMENT '真实姓名',
  `gid` int(10) unsigned NOT NULL COMMENT '角色id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态：1正常，0禁用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  `count_login` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'admin','a66abb5684c45962d887564f08346e8d','张三丰',1,0,1515650649,0,0,0),(2,'test','a66abb5684c45962d887564f08346e8d','tests',8,0,0,0,0,0),(4,'clin','clin','翠林',1,1,0,0,0,93),(5,'yuye','yuye','雨夜',1,1,0,0,0,1),(6,'li','123456','李白',8,1,0,0,0,0),(7,'libai','libai123','李白',8,1,0,0,0,0);
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '用户uid',
  `product_id` int(10) NOT NULL COMMENT '商品id',
  `sku_id` int(10) NOT NULL COMMENT '规格',
  `count` int(10) NOT NULL COMMENT '数量',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='购物车';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (28,2,25,111,1,1552919448),(27,2,27,124,1,1544922739),(26,2,28,129,1,1543548191);
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_product`
--

DROP TABLE IF EXISTS `order_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(20) NOT NULL COMMENT '订单号',
  `product_id` int(10) NOT NULL COMMENT '商品id',
  `sku_id` int(10) NOT NULL COMMENT '规格id',
  `price` decimal(10,2) NOT NULL COMMENT '购买时价格',
  `count` int(10) NOT NULL COMMENT '购买数量',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_product`
--

LOCK TABLES `order_product` WRITE;
/*!40000 ALTER TABLE `order_product` DISABLE KEYS */;
INSERT INTO `order_product` VALUES (4,'15270462950001521642',4,7,88.00,1),(5,'15270462950001521642',4,1,60.00,1),(6,'15271447660001298642',14,46,8000.00,1),(7,'15271447660001298642',4,7,88.00,1),(8,'15271453650001546740',14,46,8000.00,1),(9,'15271453650001546740',4,7,88.00,1),(10,'15271453650001546740',4,1,60.00,1),(11,'15271454960001171922',14,46,8000.00,1),(12,'15271454960001171922',4,7,88.00,1),(13,'15282855790001500701',4,1,60.00,1),(14,'15282870960001426933',4,7,88.00,1);
/*!40000 ALTER TABLE `order_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(20) NOT NULL COMMENT '订单号',
  `uid` int(10) NOT NULL COMMENT '用户uid',
  `money` decimal(10,2) NOT NULL COMMENT '订单金额',
  `pay_money` decimal(10,2) NOT NULL COMMENT '实际支付金额',
  `ship_no` varchar(20) NOT NULL COMMENT '快递单号',
  `ship_status` tinyint(1) NOT NULL COMMENT '快递状态：0未发货，1已发货，2已签收',
  `ship_time` int(10) NOT NULL COMMENT '发货时间',
  `status` int(1) NOT NULL COMMENT '订单状态：-1未支付已关闭，0未付款，1已付款，2已完成',
  `add_time` int(10) NOT NULL COMMENT '订单生成时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (10,'15270462950001521642',1,148.00,148.00,'',0,0,1,1527046295),(13,'15271454960001171922',1,8088.00,8088.00,'',0,0,1,1527145496),(14,'15282855790001500701',1,60.00,60.00,'',0,0,1,1528285579),(15,'15282870960001426933',1,88.00,88.00,'',0,0,1,1528287096);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL COMMENT '分类',
  `title` varchar(100) NOT NULL COMMENT '商品名称',
  `pro_no` varchar(20) NOT NULL COMMENT '商品编码',
  `keywords` varchar(255) NOT NULL COMMENT '关键字',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  `img` varchar(255) NOT NULL COMMENT '商品主图',
  `price` decimal(10,2) NOT NULL COMMENT '商品最低价格(SKU价格最低的产品）',
  `cost` decimal(10,2) NOT NULL COMMENT '成本（SKU价格最低的产品）',
  `pv` int(10) NOT NULL DEFAULT '0' COMMENT '点击量',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：-1已删除，0下架，1正常',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (29,6,'OPPO K1','53074154278028971608','OPPO K1','OPPO 【三期免息+3色可选！】K1 首款千元屏下指纹解锁手机 6.4英寸水滴屏 梵星蓝（6+64G）','20181121/a8f6c4c20af5ceda5fd1626b9f4496d0.jpg',1799.00,800.00,20,1,1542780289),(28,5,'vivo X23','78713154255709373014','vivo X23','vivo X23全息幻彩 6GB+128GB 北极晨曦 水滴屏全面屏 游戏手机 移动联通电信全网通4G手机','20181119/b166a5b984e37ab9922cbf35c9bed7cf.jpg',2798.00,1500.00,16,1,1542557093),(27,3,'小米8青春版','14980154252819496699','小米8青春版','小米8青春版 镜面渐变AI双摄 6GB+64GB 深空灰 全网通4G 双卡双待 全面屏拍照游戏智能手机','20181118/47149a485d6b61a5e14640e3cadfeabe.jpg',1699.00,520.00,21,1,1542528194),(25,4,'荣耀9i','19243154252624175695','荣耀9i','荣耀9i 4GB+64GB 魅海蓝 移动联通电信4G全面屏手机 双卡双待','20181118/fe3ff179da346128ffd711071e34dcc3.jpg',1199.00,540.00,43,1,1542526241),(26,3,'小米8SE','32865154252775683355','小米8SE','小米8SE 全面屏智能游戏拍照手机 6GB+64GB 灰色 骁龙处理器 全网通4G 双卡双待','20181118/263cedd7ac8e1359e2f2f508036c3b39.jpg',1799.00,520.00,27,1,1542527756),(24,4,'荣耀10','37316154252583899250','荣耀10','荣耀10 GT游戏加速 AIS手持夜景 6GB+64GB 幻影蓝全网通 移动联通电信4G 双卡双待 游戏手机','20181118/8bd9c496df571395d9486c0f78c0ef71.jpg',2279.00,540.00,10,1,1542525838),(22,4,'华为 HUAWEI Mate 20 ','27036154252370177774','华为','华为 HUAWEI Mate 20 麒麟980AI智能芯片全面屏超微距影像超大广角徕卡三摄6GB+128GB亮黑色全网通版双4G手机','20181118/bbb0d8441d28e9c287c6a2a1700ce746.jpg',1.00,3.00,5,1,1542523701),(21,1,'iPhone8','32824154247563587017','iPhone','新品上线','20181118/05d02937cd438d97e200858641a2cf2d.jpg',0.00,0.00,0,1,1542475635);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_cates`
--

DROP TABLE IF EXISTS `product_cates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_cates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL COMMENT '上级分类id',
  `ord` int(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `title` varchar(50) NOT NULL COMMENT '标签标题',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常，0禁用',
  `create_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品类目表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_cates`
--

LOCK TABLES `product_cates` WRITE;
/*!40000 ALTER TABLE `product_cates` DISABLE KEYS */;
INSERT INTO `product_cates` VALUES (1,0,0,'iphone',1,0,0),(2,0,0,'荣耀',1,0,0),(3,0,0,'小米',1,0,0),(4,0,0,'华为',1,0,0),(5,0,0,'vivo',1,0,0),(6,0,0,'OPPO',1,0,0),(21,0,0,'三星',1,0,0),(23,0,0,'苹果',1,0,0),(24,0,0,'1+',1,0,0),(25,0,0,'锤子',1,0,0);
/*!40000 ALTER TABLE `product_cates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_img`
--

DROP TABLE IF EXISTS `product_img`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_img` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL COMMENT '商品id',
  `img` varchar(255) NOT NULL COMMENT '商品图片url',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=87 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_img`
--

LOCK TABLES `product_img` WRITE;
/*!40000 ALTER TABLE `product_img` DISABLE KEYS */;
INSERT INTO `product_img` VALUES (59,25,'20181118/fe3ff179da346128ffd711071e34dcc3.jpg',1542526241),(58,25,'20181118/577386f23b5676951cf86b8555c301a4.jpg',1542526241),(57,25,'20181118/246d2ebf6d6135014a4b251e4dc71b31.jpg',1542526241),(56,25,'20181118/7c73f639371948a3c9542b2ff6ad2ed0.jpg',1542526241),(55,25,'20181118/0a234227d98ff6f22c284e21401a4d3a.jpg',1542526241),(54,24,'20181118/21f3538aba61fa7b967e89e2d29059ac.jpg',1542525838),(53,24,'20181118/9579ac8a9d54792573a5d23ea16b0b4b.jpg',1542525838),(52,24,'20181118/629ab6d4b0fa15b93a31c6917ffab03b.jpg',1542525838),(51,24,'20181118/8bd9c496df571395d9486c0f78c0ef71.jpg',1542525838),(46,22,'20181118/bbb0d8441d28e9c287c6a2a1700ce746.jpg',1542523701),(45,22,'20181118/674bcfccae1046d5c5657682fa56cddc.jpg',1542523701),(43,22,'20181118/63c8c17b685b4350f4d414db08cc0064.jpg',1542523701),(44,22,'20181118/2ec05fbd9c06e8e1d95ffa57cd6b968f.jpg',1542523701),(42,21,'20181118/23e7fdfff52e5a0d49d02b59e77a8012.jpg',1542477804),(41,21,'20181118/ea3b46c805aa396bb8d39e231a2321f4.jpg',1542477804),(40,21,'20181118/850cc869b732985aebe72f91ce358d8d.jpg',1542477804),(39,21,'20181118/cad11e6667c6ea352756ad88f77f174f.jpg',1542477804),(38,21,'20181118/05d02937cd438d97e200858641a2cf2d.jpg',1542475635),(60,26,'20181118/263cedd7ac8e1359e2f2f508036c3b39.jpg',1542527756),(61,26,'20181118/21476b909b1863d83a8184f0d76eb789.jpg',1542527792),(62,26,'20181118/dcd6308b60bffcfd26ede24ad14a3e9e.jpg',1542527792),(63,26,'20181118/1f517065b9ffbfb161faf7fbdac3777b.jpg',1542527792),(64,26,'20181118/9e5235149f94817bda6e8319f6ef4168.jpg',1542527792),(65,26,'20181118/aa03d2046f517ee085302c03c1eb0162.jpg',1542527792),(66,27,'20181118/47149a485d6b61a5e14640e3cadfeabe.jpg',1542528194),(67,27,'20181118/01abd2cb98acea27147ab491a1ae806a.jpg',1542528194),(68,27,'20181118/51499b486ebd5c34e77cf54ce27025d1.jpg',1542528194),(69,27,'20181118/7dbc40fe6359e215a5d8451a993456ac.jpg',1542528194),(81,28,'20181119/600a3111b6ab245d6ff8f3dfa95ba180.jpg',1542557491),(80,28,'20181119/f5ed494cb69df6c6b87b6e0d5bba51ef.jpg',1542557491),(79,28,'20181119/bb3eb592d81984ee2742f97a97970ee9.jpg',1542557491),(78,28,'20181119/f41616d64850bcf6b128b77ffd488d1c.jpg',1542557491),(77,28,'20181119/b166a5b984e37ab9922cbf35c9bed7cf.jpg',1542557491),(83,29,'20181121/a8f6c4c20af5ceda5fd1626b9f4496d0.jpg',1542780289),(82,28,'20181119/fbb1c70f8ae739d1aff96b731c7c9d97.jpg',1542557491),(84,29,'20181121/600b3e317950cbd8b423a889b007e3aa.jpg',1542780412),(85,29,'20181121/98e08dc2c140105c0c1d1954f5218914.jpg',1542780412),(86,29,'20181121/cdbd6b90a914d923574b729a248f3b7a.jpg',1542780412);
/*!40000 ALTER TABLE `product_img` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_sku`
--

DROP TABLE IF EXISTS `product_sku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_sku` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL COMMENT '商品id',
  `properties` text NOT NULL COMMENT '属性键值对：property_name:property_value',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `cost` decimal(10,2) NOT NULL COMMENT '成本',
  `stock` int(10) NOT NULL COMMENT '库存',
  `weight` int(10) NOT NULL COMMENT '重量：克',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=136 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_sku`
--

LOCK TABLES `product_sku` WRITE;
/*!40000 ALTER TABLE `product_sku` DISABLE KEYS */;
INSERT INTO `product_sku` VALUES (122,26,'15:112;16:111;17:110',2099.00,610.00,1,0),(114,25,'11:107;13:106',1599.00,730.00,0,0),(113,25,'11:107;13:105',1199.00,540.00,7,0),(101,21,'2:96',0.00,0.00,0,0),(102,21,'2:95',0.00,0.00,0,0),(103,22,'11:97',1.00,3.00,3,0),(104,22,'11:98',1.00,3.00,3,0),(105,24,'11:99;13:100',2279.00,540.00,3,0),(106,24,'11:99;13:101',3279.00,760.00,2,0),(107,24,'11:102;13:100',2279.00,540.00,7,0),(108,24,'11:102;13:101',3279.00,760.00,4,0),(109,24,'11:103;13:100',2279.00,540.00,11,0),(110,24,'11:103;13:101',3279.00,760.00,2,0),(111,25,'11:104;13:105',1199.00,540.00,3,0),(112,25,'11:104;13:106',1599.00,730.00,4,0),(121,26,'15:112;16:109;17:110',1799.00,520.00,7,0),(120,26,'15:108;16:111;17:110',2099.00,610.00,0,0),(119,26,'15:108;16:109;17:110',1799.00,520.00,2,0),(123,27,'15:113;16:114;17:115',1699.00,520.00,0,0),(124,27,'15:113;16:116;17:115',1999.00,540.00,2,0),(125,27,'15:117;16:114;17:115',1699.00,520.00,1,0),(126,27,'15:117;16:116;17:115',1999.00,540.00,4,0),(130,28,'18:119',2798.00,1500.00,12,0),(129,28,'18:118',2798.00,1500.00,33,0),(135,29,'22:121',1899.00,800.00,1,0),(134,29,'22:120',1799.00,800.00,6,0);
/*!40000 ALTER TABLE `product_sku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `property`
--

DROP TABLE IF EXISTS `property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL COMMENT '商品id',
  `name_id` int(10) NOT NULL COMMENT '类目属性id（property_name表id）',
  `value_id` int(10) NOT NULL COMMENT '类目属性值id（property_value表id）',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `property`
--

LOCK TABLES `property` WRITE;
/*!40000 ALTER TABLE `property` DISABLE KEYS */;
INSERT INTO `property` VALUES (102,27,16,116),(101,27,17,115),(100,27,16,114),(99,27,15,113),(98,26,15,112),(97,26,16,111),(96,26,17,110),(95,26,16,109),(94,26,15,108),(93,25,11,107),(92,25,13,106),(91,25,13,105),(90,25,11,104),(89,24,11,103),(88,24,11,102),(87,24,13,101),(86,24,13,100),(85,24,11,99),(84,22,11,98),(82,21,2,96),(81,21,2,95),(83,22,11,97),(103,27,15,117),(104,28,18,118),(105,28,18,119),(106,29,22,120),(107,29,22,121);
/*!40000 ALTER TABLE `property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `property_name`
--

DROP TABLE IF EXISTS `property_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_name` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL COMMENT '所属类目id',
  `title` varchar(255) NOT NULL COMMENT '属性名',
  `is_sale` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否销售属性：0否，1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `property_name`
--

LOCK TABLES `property_name` WRITE;
/*!40000 ALTER TABLE `property_name` DISABLE KEYS */;
INSERT INTO `property_name` VALUES (1,1,'颜色',1),(2,1,'版本',1),(3,1,'内存',1),(17,3,'搭配',1),(16,3,'内存',1),(15,3,'颜色',1),(14,4,'购买方式',1),(13,4,'内存',1),(12,4,'版本',1),(11,4,'颜色',1),(18,5,'颜色',1),(22,6,'颜色',1),(20,25,'内存',1),(21,25,'版本',1);
/*!40000 ALTER TABLE `property_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `property_value`
--

DROP TABLE IF EXISTS `property_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `name_id` int(10) NOT NULL COMMENT '属性名称id(property_name表id）',
  `value` varchar(255) NOT NULL COMMENT '属性值',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=123 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='SKU属性值';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `property_value`
--

LOCK TABLES `property_value` WRITE;
/*!40000 ALTER TABLE `property_value` DISABLE KEYS */;
INSERT INTO `property_value` VALUES (99,24,11,'幻影蓝'),(97,22,11,'极光蓝'),(95,21,2,'全网通'),(96,21,2,'移动版'),(98,22,11,'黑金色'),(100,24,13,'6G 64G'),(101,24,13,'8G 128G'),(102,24,11,'幻影紫'),(103,24,11,'幻影黑'),(104,25,11,'梦幻紫'),(105,25,13,'4G 64G'),(106,25,13,'4G 128G'),(107,25,11,'碧海青'),(108,26,15,'金'),(109,26,16,'4G 64G'),(110,26,17,'裸机'),(111,26,16,'6G 128G'),(112,26,15,'蓝'),(113,27,15,'暮光金'),(114,27,16,'6G 64G'),(115,27,17,'裸机'),(116,27,16,'6G 128G'),(117,27,15,'梦幻蓝'),(118,28,18,'北极晨曦'),(119,28,18,'星夜海蓝'),(120,29,22,'银光绿'),(121,29,22,'梵星蓝');
/*!40000 ALTER TABLE `property_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sites`
--

DROP TABLE IF EXISTS `sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sites`
--

LOCK TABLES `sites` WRITE;
/*!40000 ALTER TABLE `sites` DISABLE KEYS */;
INSERT INTO `sites` VALUES (1,'site','\"JD\\u624b\\u673a\\u5546\\u57ce\\u5546\\u57ce\"');
/*!40000 ALTER TABLE `sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slide`
--

DROP TABLE IF EXISTS `slide`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slide` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '类型：0首页首屏',
  `ord` tinyint(2) NOT NULL DEFAULT '0' COMMENT '排序',
  `title` varchar(30) NOT NULL COMMENT '标题',
  `url` varchar(255) NOT NULL COMMENT '链接地址',
  `img` varchar(255) NOT NULL COMMENT '图片地址',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slide`
--

LOCK TABLES `slide` WRITE;
/*!40000 ALTER TABLE `slide` DISABLE KEYS */;
INSERT INTO `slide` VALUES (8,0,0,'iPhone X 领劵立减300','https://sale.jd.com/act/8yaD0qZuE6VFk.html?cpdad=1DLSUE','20181115/23bc277b8ab78f0fe1695c5e5f93b8c8.jpg'),(9,0,0,'华为Mate20 新品上市','https://sale.jd.com/act/DhKrOjXnFcGL.html?cpdad=1DLSUE','20181115/9317cd280a98c20df0f649ba5486863e.jpg'),(10,0,0,'抢手机1111元神劵','https://sale.jd.com/act/k1Apil0bIJQv4uS.html?cpdad=1DLSUE','20181115/bc49cfc649154d25b80b5803d3b9c535.jpg'),(11,0,0,'手机大牌让利','https://sale.jd.com/act/fpgmdSRPst.html?cpdad=1DLSUE','20181115/51d8b8f9c9916f59d52eb7c2cb37bcdd.jpg'),(15,0,0,'荣耀10青春版发布','https://sale.jd.com/mall/hF2HXITYgxb5c0.html?cpdad=1DLSUE','20181121/fa14c6c6e0345bbae2018716e57e0cda.jpg'),(14,0,0,'手机主会场','https://sale.jd.com/act/fpgmdSRPst.html?cpdad=1DLSUE','20181115/ded0860796e41f96363cf9c871cfc21c.jpg');
/*!40000 ALTER TABLE `slide` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `sku_id` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=311 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test`
--

LOCK TABLES `test` WRITE;
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
INSERT INTO `test` VALUES (290,769,66),(291,909,66),(292,267,66),(293,772,66),(294,471,66),(295,358,66),(296,710,66),(297,334,66),(298,386,66),(299,905,66),(300,867,66),(301,231,66),(302,623,66),(303,527,66),(304,670,66),(305,128,66),(306,430,66),(307,854,66),(308,526,66),(309,800,66),(310,703,66);
/*!40000 ALTER TABLE `test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `uid` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码：md5(username+pwd)',
  `add_time` int(10) NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'test','47ec2dd791e31e2ef2076caf64ed9b3d',0),(2,'clin','clin',0),(3,'baidu','baidu',1542738253);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-08 13:44:12
