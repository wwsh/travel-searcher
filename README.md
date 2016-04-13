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

The app is fully tested through functional tests.

## Example input

        Spain, 10 days, 01.07.2016 - 30.07.2016

The output should be:

        Madrid, 25.06 - 10.07.2016
        Barcelona, 14.07 - 28.07.2016 (highlighted)

The others not showing.

## How to install:

    composer install
    php bin/console braincrafted:bootstrap:install
    php bin/console assetic:dump --env prod
    cd web/
    bower install

Done.

## Customization

### Database
The default database system for this app is a JSON file.
There is support for ODM/ORM, though. A MySQL/Mongo driver has to
 be implemented as a DataProvider. An example, untested Mongo driver
 is available as MongoDataProvider.
 See DataProviderInterface for details on how to implement a new
 database driver.

### Extending the engine

The TravelSearcher class can be decomposed into smaller classes
and the searching engine easily extended. An API interface should
be provided for the engine and the front end should communicate
purely via a REST API.



