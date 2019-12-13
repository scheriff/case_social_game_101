CREATE TABLE `user`
(
    `id`                   bigint(20) unsigned                     NOT NULL AUTO_INCREMENT,
    `created_at`           timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `username`             varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password_hash`        varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `daily_limit_reset_at` timestamp                               NULL     DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unq_user_username` (`username`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `gift`
(
    `id`         bigint(20) unsigned                     NOT NULL AUTO_INCREMENT,
    `created_at` timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `name`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `udx_gift_name` (`name`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `gift_transaction`
(
    `id`          bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `created_at`  timestamp           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `gift_id`     bigint(20) unsigned NOT NULL,
    `sender_id`   bigint(20) unsigned NOT NULL,
    `receiver_id` bigint(20) unsigned NOT NULL,
    `status`      smallint(6)         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_gift_id` (`gift_id`),
    KEY `fk_sender_id` (`sender_id`),
    KEY `fk_receiver_id` (`receiver_id`),
    CONSTRAINT `fk_gift_id` FOREIGN KEY (`gift_id`) REFERENCES `gift` (`id`),
    CONSTRAINT `fk_receiver_id` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
    CONSTRAINT `fk_sender_id` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 5
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `gift_transaction_log`
(
    `id`                  bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `created_at`          timestamp           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `gift_transaction_id` bigint(20) unsigned NOT NULL,
    `status`              smallint(6)         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_gift_transaction_id` (`gift_transaction_id`),
    CONSTRAINT `fk_gift_transaction_id` FOREIGN KEY (`gift_transaction_id`) REFERENCES `gift_transaction` (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 7
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;