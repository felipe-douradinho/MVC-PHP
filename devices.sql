CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ip_address` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `manufacturer` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;