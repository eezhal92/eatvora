# Eatvora

[![CircleCI](https://circleci.com/bb/eatvora-id/eatvora-web.svg?style=svg)](https://circleci.com/bb/eatvora-id/eatvora-web)

## Requirements

- PHP 7.1+
- Sqlite 3.19.3 (For DB Testing)

## Known Issues

### Testing

When using sqlite as testing db, it has some weird behaviour. The integer value of foreign key, turned into string in json response while when using mysql, it's still same integer.

Also when passing date string in query, it should be parsed as carbon object.
