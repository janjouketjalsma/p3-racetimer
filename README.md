# p3-racetimer
Race timer software that works with the AMB P3 protocol over a TCP socket. Collects lap times and displays these in real time (using websockets) on a webpage.

## Install
1. Run `composer install` (requires composer from getcomposer.org)
2. Copy `.env.example` to `.env` and change settings for your setup (most important one is the `P3_HOST`)


## Import participants
`php bin\console.php importParticipants "/path/to/your/particpants.csv"`

## Run
After importing participants start the services in this order:
1. `php bin\console.php capture` (starts the capturing of data)
2. `php bin\console.php eventprocessor` (processes captured data into laps and hosts websocket server)
3.`composer start` (runs webserver for static page)

## Use display
Navigate your browser to `localhost:9090`.
