<?php
namespace Plugin\Diary\Setup;

class Worker extends \Ip\SetupWorker
{

    public function activate()
    {
        $sql1 = ' CREATE TABLE IF NOT EXISTS
           '. ipTable('diary_blog') .'
        ( `id` int(11) NOT NULL AUTO_INCREMENT,
        `author` varchar(255),
        `date` datetime,
        `content` MEDIUMTEXT,
        `title` varchar(255),
        `alias` varchar(255),
        `status` int(3),
        `modified` datetime,
        `comment` bigint(20),
        `category_id` bigint(20),
        PRIMARY KEY (`id`)
        )';
        $sql2=' CREATE TABLE IF NOT EXISTS
           ' . ipTable('diary_comments') . '
        (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `post_id` int(11),
        `author` varchar(255),
        `email` varchar(255),
        `url` varchar(255),
        `date` datetime,
         `content` text NOT NULL,
        `modified` datetime,
        `approved` tinyint(1),
        `parent` int(11),
        PRIMARY KEY (`id`)
        )';

        $sql3='CREATE TABLE IF NOT EXISTS
           ' . ipTable('diary_category') . '
        (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255),
        `description` varchar(255),
        `count` bigint(11),
         PRIMARY KEY (`id`)
        )';

        ipDb()->execute($sql1);
        ipDb()->execute($sql2);
        ipDb()->execute($sql3);
    }

    public function deactivate()
    {
        $sql1 = 'DROP TABLE IF EXISTS ' . ipTable('diary_blog');
        $sql2= 'DROP TABLE IF EXISTS ' . ipTable('diary_comments');
        $sql3= 'DROP TABLE IF EXISTS ' . ipTable('diary_category');


        ipDb()->execute($sql1);
        ipDb()->execute($sql2);
        ipDb()->execute($sql3);
    }

    public function remove()
    {

    }

}