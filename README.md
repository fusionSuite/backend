# FusionSuite::Backend

## Introduction

It's the backend for the IT Service Management.

It will offer REST API of data.


## Technologies

It's written in PHP and use:

* slim (microframework)
* eloquent (database queries)
* phinx (database migrations)

## Install

Here are the steps to install the backend:

* install dependencies `composer install`
* configure into phinx.php the database information
* run command `./vendor/bin/phinx migrate`


