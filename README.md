Travel Seacher
==============

## What it is

This is an example task, created for one of the companies we supposedly
wanted to work for. It is a simple travel search engine, working with
an example database in a .json file.

The mission was to create an engine, able to search through simple
travel offers, each one consisting primarily of a country, city
and date range information.

The user should specifies destination (country, city, whatever),
a favorite date range, in which the trip should take place and number
 of consecutive days able to spend.

 The searcher should return all matching trips within that range
 and also trips overlapping the user given date range, but lasting
 enough days to be qualified.

 The former ones, exaclty matching, should be highlighted, the latter
 not.

 ## Example input



## How to install:

    composer install
    php bin/console braincrafted:bootstrap:install
    php bin/console assetic:dump --env prod
    cd web/
    bower install

Done.

## Footnotes

The default database system for this app is a JSON file.
There is support for ODM/ORM, though. A MySQL/Mongo driver has to
 be implemented as a DataProvider. An example, untested Mongo driver
 is available as MongoDataProvider.
 See DataProviderInterface for details on how to implement a new
 database driver.



