# Social Game - Gifts
Demonstrating of a social game where users of the game are able to send gifts to each
other and claim gifts that other users sent.

## Installation with Docker
- Docker setup forked from https://github.com/atillay/docker-lemp and extended for my purposes
- Install and launch Docker  
- `cp .env.dist .env`  
- `docker-compose up -d`
- DB tables created automatically on start. For manual installations run initdb/schema.sql
- Connect to docker instance and run `composer install` for PHPUnit installation

| Service      | Path                    |
| ------------ | ----------------------- |
| Website      | `http://localhost:8080` | 
| PhpMyAdmin   | `http://localhost:8081` |
| Redis        | `http://localhost:6379` |
| Logs         | `log/`                  |

## Optimization & Cache Scenarios
- For social scoreboard, use Redis key-value store with sorted sets. Utilize ZINCRBY command with score values
- Check if User-A can send a gift to User-B: Set user-id-1:user-id-2 with 24 hour expiration duration. Check this key instead of DB
- PHP session handler can be used as memcached or redis
- Members can be stored in Redis set with SADD command
