# REST API

> The Open DRadio REST API - [https://api.opendradio.org](https://api.opendradio.org/).

[![Build Status: Linux](https://img.shields.io/travis/opendradio/rest-api.svg?branch=master&style=flat-square)](https://travis-ci.org/opendradio/rest-api)
[![Dependency Status](https://img.shields.io/versioneye/d/opendradio/rest-api.svg?style=flat-square)](https://www.versioneye.com/php/opendradio:rest-api)
[![Issues](https://img.shields.io/github/issues/opendradio/rest-api.svg?style=flat-square)](https://github.com/opendradio/rest-api/issues)
![License](https://img.shields.io/badge/license-AGPL--3.0-blue.svg?style=flat-square)

---

## Architecture

[![PHP](https://raw.githubusercontent.com/pixel-cookers/built-with-badges/master/php/php-short-flat.png)](https://php.net/)
[![MongoDB](https://raw.githubusercontent.com/pixel-cookers/built-with-badges/master/mongoDB/mongodb-short-flat.png)](https://www.mongodb.org/)

Build with the use of [Slim Framework](http://www.slimframework.com), [Eloquent ORM](http://laravel.com/docs/eloquent) with support for Facades, [MongoDB](https://www.mongodb.org/) and [Redis](http://redis.io/) caching.

### Facades

Facades provide a "static" interface to classes that are available in [Laravel's IoC container](http://laravel.com/docs/5.0/container). They serve as "static proxies" to underlying classes in the service container, providing the benefit of a terse, expressive syntax while maintaining more testability and flexibility than traditional static methods.

### Caching

This API uses the [Redis PHP extension](http://pecl.php.net/package/redis) to enable [in-memory](http://en.wikipedia.org/wiki/In-memory_database) caching for MongoDB query results.

You can flush the cache with [redis-cli](http://redis.io/commands):

```sh
$ redis-cli flushall
```

## Methods

### Overview

**Schema**

With the API, the general format of a response is:

```json
{
  "success": true,
  "message": "200 OK",
  "data": { "..." }
}
```

However, `data` may contain any kind of result, depending on the method.

**Client Errors**

If the API encounters an error, the response will be:

```json
{
  "success":  false,
  "message": "404 Not Found"
}
```

### Root Endpoint (URL)

You can issue a `GET` request to the root endpoint to get all the endpoints that the API supports:

```sh
$ curl https://api.opendradio.org/
```

### HTTP Protocols

To promote Google's "[Let's make the web faster](https://developers.google.com/speed/)" initiative, the API supports the `SPDY` transport protocol.

```sh
$ openssl s_client -connect api.opendradio.org:443 -nextprotoneg ''
CONNECTED(00000003)
Protocols advertised by server: spdy/3.1, http/1.1
```

### HTTP Verbs

| Verb          | Description |
| ------------- | ------------- |
| `GET`         | Used for retrieving resources. |
| `POST`        | Used for creating resources. |
| `PATCH`       | Used for updating resources with partial JSON data. For instance, an resource has title and body attributes. A PATCH request may accept one or more of the attributes to update the resource. PATCH is a relatively new and uncommon HTTP verb, so resource endpoints also accept POST requests. |
| `DELETE`      | Used for deleting resources. |

### Date Time

The default date format for this API is [W3C](http://www.w3.org/TR/html-markup/datatypes.html#common.data.datetime) (also known as `RFC3339`). 

```txt
Y-m-d\TH:i:sP
```

**Time Zone**

The default time zone is `UTC+1` (Europe/Berlin), the location of Deutschlandradio headquarters.

### Pagination

Requests that return multiple items will be paginated to 30 items by default. You can specify further pages with the `?page` parameter. Note that for technical reasons not all endpoints respect the `?per_page` parameter.

```sh
GET /news/from/2015-08-15/to/2015-08-17?page=2&per_page=10
```

### Sorting

Requests that return multiple items can be sorted ascending `asc` or descending `desc` with the `?sort` parameter.

```sh
GET /broadcasts/latest?sort=desc
```

## REST Resources

### Playlist

#### Get all stations and audio streams

```sh
GET /playlist
```

**Parameters**

| Name          | Type          | Description |
| ------------- | ------------- | ----------- |
| `sort`        | `string`      | The direction of the sort. Can be either `asc` or `desc`. Default: `asc` (newest first) |
| `permanent`   | `boolean`     | Either `true` or `✓` to show only permanent stations, `false` to show temporary stations. |

**Response**

```json
{
  "success": true,
  "message": "200 OK",
  "data": [
    {
      "_id": "4af9f23d8emd0e1d32000000",
      "title": "Deutschlandfunk",
      "name": "dlf",
      "slug": "deutschlandfunk",
      "permanent": true,
      "website": "http://example.com/",
      "updated_at": "2015-08-15T15:52:01+01:00",
      "streams": [
        {
          "type": "ogg",
          "quality": "hq",
          "bitrate": 96,
          "url": "http://example.com/link-to-ogg"
        },
        {
          "type": "mp3",
          "quality": "hq",
          "bitrate": 128,
          "url": "http://example.com/link-to-mp3"
        }
      ]
    }
  ]
}
```

**Example**

```sh
GET /playlist?sort=desc&permanent=✓
```

### Broadcasts

#### List the latest broadcasts

```sh
GET /broadcasts/latest
```

**Parameters**

| Name          | Type          | Description |
| ------------- | ------------- | ----------- |
| `sort`        | `string`      | The direction of the sort. Can be either `asc` or `desc`. Default: `asc` (newest first) |
| `station`     | `string`      | Indicates the station of the items to return. Can be one of: <ul><li>`dlf`: [Deutschlandfunk](http://www.deutschlandfunk.de/)</li><li>`dlk`: [Deutschlandradio Kultur](http://www.deutschlandradiokultur.de/)</li><li>`dlw`: [DRadio Wissen](http://dradiowissen.de/)</li></ul> Default: `all` |

**Response**

```json
{
  "success": true,
  "message": "200 OK",
  "data": [
    {
      "_id": "4af9f23d8ead0f1d32000000",
      "title": "Lorem ipsum dolor sit amet",
      "starts_at": "2015-08-15T15:35:00+01:00",
      "type": "branchy",
      "ends_at": "2015-08-15T16:00:00+01:00",
      "duration": 1500,
      "full_duration": 14100,
      "part": 5,
      "parent_id": "4af9f23d8ead0f1d31000000",
      "description": "...",
      "station_id": "4af9f23d8emd0e1d32000000",
      "updated_at": "2015-08-15T15:52:01+01:00"
    }
  ]
}
```

**Example**

```sh
GET /broadcasts/latest?sort=desc&station=dlw
```

#### Get the broadcasts for a given date range

> Note: The default date format for this API is [W3C](http://www.w3.org/TR/html-markup/datatypes.html#common.data.datetime) `Y-m-d\TH:i:s` (also known as `RFC3339`).  
A JavaScript `Date` instance will parse this format right out of the box.

```sh
GET /broadcasts/from/2015-08-15/to/2015-08-17
```

**Parameters**

| Name          | Type          | Description |
| ------------- | ------------- | ----------- |
| `sort`        | `string`      | The direction of the sort. Can be either `asc` or `desc`. Default: `asc` (newest first) |
| `station`     | `string`      | Indicates the station of the items to return. Can be one of: <ul><li>`dlf`: [Deutschlandfunk](http://www.deutschlandfunk.de/)</li><li>`dlk`: [Deutschlandradio Kultur](http://www.deutschlandradiokultur.de/)</li><li>`dlw`: [DRadio Wissen](http://dradiowissen.de/)</li></ul> Default: `all` |
| `page`        | `number`      | Results will be limited to 30 items by default. You can specify further pages. Default: `1` |
| `per_page`    | `number`      | Indicates how many items you want each page to return. Default: `30` |

**Example**

```sh
GET /broadcasts/from/2015-08-15/to/2015-08-17?station=dlk&page=4&per_page=10
```

#### Get a single broadcast by its ObjectId

> Note: [ObjectId](http://docs.mongodb.org/manual/reference/glossary/#term-objectid) is a 12-byte [BSON](http://docs.mongodb.org/manual/reference/glossary/#term-bson) type.

```sh
GET /broadcasts/id/4af9f23d8ead0e1d32000000
```

**Response**

```json
{
  "success": true,
  "message": "200 OK",
  "data": {
    "_id": "4af9f23d8ead0f1d32000000",
    "title": "Lorem ipsum dolor sit amet",
    "starts_at": "2015-08-15T15:35:00+01:00",
    "type": "branchy",
    "ends_at": "2015-08-15T16:00:00+01:00",
    "duration": 1500,
    "full_duration": 14100,
    "part": 5,
    "parent_id": "4af9f23d8ead0f1d31000000",
    "description": "...",
    "station_id": "4af9f23d8emd0e1d32000000",
    "updated_at": "2015-08-15T15:52:01+01:00"
  }
}
```

### News

#### List the latest news

```sh
GET /news/latest
```

**Parameters**

| Name          | Type          | Description |
| ------------- | ------------- | ----------- |
| `sort`        | `string`      | The direction of the sort. Can be either `asc` or `desc`. Default: `asc` (newest first) |
| `station`     | `string`      | Indicates the station of the items to return. Can be one of: <ul><li>`dlf`: [Deutschlandfunk](http://www.deutschlandfunk.de/)</li><li>`dlk`: [Deutschlandradio Kultur](http://www.deutschlandradiokultur.de/)</li><li>`dlw`: [DRadio Wissen](http://dradiowissen.de/)</li></ul> Default: `all` |

**Response**

```json
{
  "success": true,
  "message": "200 OK",
  "data": [
    {
      "_id": "4af9f23d8ead0f1d32000000",
      "title": "Lorem ipsum dolor sit amet",
      "text": "...",
      "publicated_at": "2015-08-15T15:52:01+01:00",
      "station_id": "4af9f23d8emd0e1d32000000",
      "updated_at": "2015-08-15T15:52:01+01:00"
    }
  ]
}
```

**Example**

```sh
GET /news/latest?sort=desc&station=dlk
```

#### Get the news for a given date range

> Note: The default date format for this API is [W3C](http://www.w3.org/TR/html-markup/datatypes.html#common.data.datetime) `Y-m-d\TH:i:s` (also known as `RFC3339`).  
A JavaScript `Date` instance will parse this format right out of the box.

```sh
GET /news/from/2015-08-15/to/2015-08-17
```

**Parameters**

| Name          | Type          | Description |
| ------------- | ------------- | ----------- |
| `sort`        | `string`      | The direction of the sort. Can be either `asc` or `desc`. Default: `asc` (newest first) |
| `station`     | `string`      | Indicates the station of the items to return. Can be one of: <ul><li>`dlf`: [Deutschlandfunk](http://www.deutschlandfunk.de/)</li><li>`dlk`: [Deutschlandradio Kultur](http://www.deutschlandradiokultur.de/)</li><li>`dlw`: [DRadio Wissen](http://dradiowissen.de/)</li></ul> Default: `all` |
| `page`        | `number`      | Results will be limited to 30 items by default. You can specify further pages. Default: `1` |
| `per_page`    | `number`      | Indicates how many items you want each page to return. Default: `30` |


**Example**

```sh
GET /news/from/2015-08-15/to/2015-08-17?station=dlk&page=4&per_page=10
```

#### Get a single news

> Note: [ObjectId](http://docs.mongodb.org/manual/reference/glossary/#term-objectid) is a 12-byte [BSON](http://docs.mongodb.org/manual/reference/glossary/#term-bson) type.

```sh
GET /news/id/4af9f23d8ead0e1d32000000
```

**Response**
```json
{
  "success": true,
  "message": "200 OK",
  "data": {
    "_id": "4af9f23d8ead0f1d32000000",
    "title": "Lorem ipsum dolor sit amet",
    "text": "...",
    "publicated_at": "2015-08-15T15:52:01+01:00",
    "station_id": "4af9f23d8emd0e1d32000000",
    "updated_at": "2015-08-15T15:52:01+01:00"
  }
}
```

### Geo Frequencies

#### Get all geo frequencies as an aggregated result

```sh
GET /geo-frequencies
```

**Response**

```json
{
  "success": true,
  "message": "200 OK",
  "data": [
    {
      "country": "BE",
      "states": [
        {
          "state": "DE-BE",
          "cities": [
            {
              "_id": "4af9f23d8emd0e1d31000000",
              "city": "Berlin",
              "coords": {
                "latitude": 52.5200066,
                "longitude": 13.404954
              },
              "frequencies": [
                {
                  "_id": "4af9f23d8emd0e1d31000001",
                  "station_id": "4af9f23d8emd0e1d32000000",
                  "vhf": "97.7"
                },
                {
                  "_id": "4af9f23d8emd0e1d31000002",
                  "mf": "990",
                  "station_id": "4af9f23d8emd0e1d32000000"
                },
                {
                  "_id": "4af9f23d8emd0e1d31000002",
                  "station_id": "54af9f23d8emd0e1d33000000",
                  "vhf": "89.6"
                }
              ],
              "updated_at": "2015-08-15T15:52:01+01:00"
            }
          ]
        }
      ]
    }
  ]
}
```

### Geolocation

#### Geo clue the location of the request ip address

> Note: A fallback service for the [Geolocation Web API Interface](https://developer.mozilla.org/en-US/docs/Web/API/Geolocation/Using_geolocation). This filter uses [Maxmind GeoIP](https://www.maxmind.com/en/geoip2-services-and-databases) databases to determine the closest position.

```sh
GET /geolocation
```

**Response**

```json
{
  "success": true,
  "message": "200 OK",
  "data": {
    "timestamp": 1424822550,
    "coords": {
      "latitude": 52.5167,
      "longitude": 13.3833,
      "altitude": 0
    }
  }
}
```

## Installation

[![Composer](https://raw.githubusercontent.com/pixel-cookers/built-with-badges/master/composer/composer-long-flat.png)](https://getcomposer.org/)

### Getting Started

You need **PHP** `>= 5.4` for this to work predictably.

### Broadcasts

#### Prerequisites 

Running the application requires the latest version of [Composer](https://getcomposer.org/) to install the dependencies.

* **Composer** - Dependency Manager for PHP.
Installing [Composer](https://getcomposer.org/) is easy:

        $ curl -sS https://getcomposer.org/installer | php

* **MongoDB** `>= 2.6.6` - *The leading NoSQL database*.
* **Redis** `>= 2.8.19` - *An advanced key-value cache and store*.

### Usage

#### Prepare Your Environment

1. Change to the top-level project directory.
2. Install the dependencies:

        $ php composer.phar update

Your environment is now ready to work on the source.

## Testing [![PHPUnit](https://raw.githubusercontent.com/pixel-cookers/built-with-badges/master/phpunit/phpunit-long.png)](http://www.phpunit.de)

### Getting Started

#### Prerequisites 

You need the latest version of [PHPUnit](https://www.phpunit.de/) for this to work predictably. Read the [Getting Started](https://phpunit.de/getting-started.html) guide for installation instructions.

### Unit Testing

How to run the tests:

1. Install the latest version of [PHPUnit](https://www.phpunit.de/).
2. Run PHPUnit tests - From the top-level project directory change to the `app/tests` directory. You may run all unit tests or specific unit tests.
3. Start the test runner:

        $ phpunit tests

#### PHP support

All unit tests require PHP `>= 5.4`.

## License

[Open DRadio](https://opendradio.org/) is an non-profit, open source project.

This code is open-sourced software licensed under the [AGPL-3.0 license](http://www.gnu.org/licenses/agpl-3.0.html).
