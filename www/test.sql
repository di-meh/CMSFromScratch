CREATE TABLE `lbly_locman` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL DEFAULT '0',
  `title` text NOT NULL,
  `slug` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'publish'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;