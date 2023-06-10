SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS `escape_rooms`;
CREATE DATABASE IF NOT EXISTS `escape_rooms` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `escape_rooms`;


CREATE TABLE IF NOT EXISTS `levels` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) NOT NULL,
  `logo` varchar(1000) NOT NULL,
  `lock_type` varchar(15) NOT NULL,
  `category` varchar(15) NOT NULL,
  `type` varchar(6) NOT NULL,
  `answer` varchar(1000) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 1,
  `points` int(11) NOT NULL DEFAULT 1,
  `resource` varchar(1000) NOT NULL,
  `description` varchar(1000) NOT NULL DEFAULT "No description available",
  `open_url` varchar(1000) NOT NULL,
  `duration` double NOT NULL,
  `public` boolean NOT NULL DEFAULT TRUE, -- visible to other users
  `show_config` boolean NOT NULL DEFAULT TRUE,
  `config_pass` varchar(1000) NOT NULL DEFAULT "", -- for exporting the level
  `check_answer` boolean NOT NULL DEFAULT TRUE,
  `theme` varchar(1000) NOT NULL,
  `language` varchar(1000) NOT NULL,
  `user` varchar(128) NOT NULL, -- username of the creator
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) NOT NULL,
  `logo` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `games_levels` (
  `game_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  PRIMARY KEY (`game_id`, `level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(128) NOT NULL, 
  `password` varchar(128) NOT NULL,
  `role` varchar(10) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `users_levels` (
  `username` varchar(128) NOT NULL, 
  `level_id` varchar(10) NOT NULL,
  PRIMARY KEY (`username`, `level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (username, password, role)
VALUES ('admin','$2y$10$Xkb0.jj.LJZ86CyLhRk1LOt.QDV.MT5sKFall13N3rjg/cwo79p/O', 'admin');

INSERT INTO `levels` (`name`, `logo`, `lock_type`, `category`, `type`, `answer`, `attempts`, `points`, `resource`, `description`, `open_url`, `duration`, `public`, `show_config`, `config_pass`, `check_answer`, `theme`, `language` ,`user`)
VALUES ("Forest Escape", "../generated/level_logos/1", "Direction", "Survival", "Online", "south", 1, 2, "../generated/resources/1.pdf", "Riddle", "../views/test_level.php?theme=light&lang_UI=en&lang_DATA=en&action=play&callback_url=../views/selected_level.php", 0.2, 0, 0, "123", 0, "dark", "en", "admin");

INSERT INTO `levels`  (`name`, `logo`, `lock_type`, `category`, `type`, `answer`, `attempts`, `points`, `resource`, `description`, `open_url`, `duration`, `public`, `show_config`, `config_pass`, `check_answer`, `theme`, `language`, `user`)
VALUES ('Stove is on!','https://i.pinimg.com/originals/98/92/5f/98925f58c9db6a235530b1966e2bcfd8.jpg','3 Digit Lock', 'Horror', 'Online','180',5,1,'https://breakoutgames.com/company/design/kidnapping','Stop the stove before the food gets burnt', "../views/test_level.php?theme=light&lang_UI=en&lang_DATA=en&action=play&callback_url=../views/selected_level.php", 0.2, 1, 1, "", 0, "dark", "en", "admin"),
       ('Mate in 2','https://images.freeimages.com/images/large-previews/f26/chess-black-army-3-1164256.jpg','Word', 'Puzzle', 'Live','Zugzwang',10,5,'https://breakoutgames.com/company/design/kidnapping','How is a forced bad move called?', "../generated/resources/1.pdf", 10, 1, 1, "", 1, "blue", "bg", "admin"),
       ('Sail away','https://images.freeimages.com/images/large-previews/d8a/ships-1-1186982.jpg','4 Digit Lock', 'Creative','Live','9856',10,5,'https://breakoutgames.com/company/design/submarine','Steal the boat to escape', "../generated/resources/1.pdf", 0.5, 0, 0, "", 1, "dark", "bg", "admin"),
       ('Escape the forest','https://images.freeimages.com/images/large-previews/5ab/waterfall-at-crystal-gardens-1371456.jpg','Direction', 'Survival', 'Live','s-e-s-w',16,10,'https://breakoutgames.com/company/design/island','Escape the forest or die of hunger', "../generated/resources/1.pdf", 20, 1, 1, "", 1, "red", "en", "admin");

INSERT INTO `games` VALUES (1,'Lonely island','../generated/game_logos/1');

INSERT INTO `games_levels` VALUES (1,2), (1,3);

COMMIT;
