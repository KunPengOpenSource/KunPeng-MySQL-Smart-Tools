### 软件介绍
MySQL Smart Tools主要实现对MySQL数据库管理系统的管理，包含对MySQL数据库管理系统的数据管理和配置管理；对MySQL数据库管理系统的性能监控；对MySQL数据库管理系统所在宿主主机的监控。MySQL数据管理主要实现MySQL数据库、数据表、数据记录、视图、触发器、用户的管理以及SQL语句的操作等功能；MySQL配置管理主要实现MySQL数据库管理系统状态信息的展示以及系统变量的修改配置；MySQL性能监控主要实现了MySQL关键性能指标数据的图形化展示、数据报表的展示与下载；MySQL宿主主机状态监控主要实现对宿主主机的运行状态，包括操作系统状态、磁盘空间、内存使用情况、用户信息、系统进程等的监控。
### 功能介绍
MySQL Smart Tools分为四个模块，分别为MySQL数据管理、MySQL性能监控、MySQL配置管理、Linux状态监控。
MySQL数据库管理包括：数据库管理，数据表管理，表字段管理，表数据管理，SQL操作，视图管理，用户管理，触发器管理。能够满足数据库管理的基本操作。
MySQL性能监控：展示数据库的并发连接数、流量信息、QPS、TPS、查询缓存命中率、索引命中率、连接缓存命中率、InnoDB缓存命中率、查询吞吐率、慢查询等性能数据。
MySQL配置管理：展示数据库的基本状态信息、用户信息、进程状态、系统变量、系统状态信息、用户登录信息。
Linux状态监控：监控信息包括操作系统状态、内存状态、硬盘状态、安装软件状态、用户信息、最后访问用户、系统进程状态。
### 访问网址
http://kunpengos.cn/Tools/mysqlsmart/index.html

ZendSkeletonApplication
=======================

Introduction
------------
This is a simple, skeleton application using the ZF2 MVC layer and module
systems. This application is meant to be used as a starting place for those
looking to get their feet wet with ZF2.

Installation
------------

Using Composer (recommended)
----------------------------
The recommended way to get a working copy of this project is to clone the repository
and use `composer` to install dependencies using the `create-project` command:

    curl -s https://getcomposer.org/installer | php --
    php composer.phar create-project -sdev --repository-url="https://packages.zendframework.com" zendframework/skeleton-application path/to/install

Alternately, clone the repository and manually invoke `composer` using the shipped
`composer.phar`:

    cd my/project/dir
    git clone git://github.com/zendframework/ZendSkeletonApplication.git
    cd ZendSkeletonApplication
    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)

Another alternative for downloading the project is to grab it via `curl`, and
then pass it to `tar`:

    cd my/project/dir
    curl -#L https://github.com/zendframework/ZendSkeletonApplication/tarball/master | tar xz --strip-components=1

You would then invoke `composer` to install dependencies per the previous
example.

Using Git submodules
--------------------
Alternatively, you can install using native git submodules:

    git clone git://github.com/zendframework/ZendSkeletonApplication.git --recursive

Web Server Setup
----------------

### PHP CLI Server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

**Note: ** The built-in CLI server is *for development only*.

### Apache Setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-tutorial.localhost
        DocumentRoot /path/to/zf2-tutorial/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/zf2-tutorial/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>
