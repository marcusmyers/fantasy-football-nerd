# Fantasy Football Nerd API PHP package

A PHP package for the [Fantasy Football Nerd
API](http://www.fantasyfootballnerd.com/fantasy-football-api) which

> takes the "wisdom of the crowd" to a new level by aggregating the
> fantasy football rankings of the best fantasy football sites on the
> planet to analyze the rankings given to each player to produce a
> consensus ranking.

This packge currently supports all Level 1 and Level 2 streams which cost
$14.95 per season -- a total no-brainer if you're into fantasy sports
and programming.

## Install

First, [sign up](http://www.fantasyfootballnerd.com/create-account) for
a Fantasy Football Nerd account.

Then run the following in your project to work with the api.

```
composer require marcusmyers/fantasy-football-nerd
```

## Usage

If working with a framework you could do the following.

```php
use MarkMyers\FFNerd\FFNerd;

$client = new FFNerd();

$byes = $client->byes();
```

When working with plain old php you could something like the following.

```php
require_once 'vendor/autoload.php';

$client = new FFNerd();

$byes = $client->byes();
```

If you have problems using the package please create an [issue](https://github.com/marcusmyers/fantasy-football-nerd/issues/new) and I'll try and address it when I have time.