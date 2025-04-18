![Alt text](images/connect4-logo.jpg)
# Connect4
## Browser-based game online
![Alt text](https://img.shields.io/badge/release%20date-february%202022-blueviolet)


## Update - API Version
A new version of the API is available on the `websocket-api` branch. This version introduces WebSocket support for real-time communication, improving the responsiveness and interactivity of the game. You can find the branch here: [websocket-api branch](https://github.com/ahmosman/connect4/tree/api-websocket).

### About
Game online in which you need to place four elements in a row before your opponent. 
<br>
In order to play you need start game, complete the form and pass the rendered game ID to your opponent.
<br> 
This project uses several endpoints responsible for every functionality, 
such as generating board, sending info about balls location to database or detecting if anyone wins.
All of these endpoints are invoked using AJAX on the change of the player's status e.g. waiting, ready, player/opponent move or win/lose/draw.

### Technologies used
- PHP 8
- CSS
- JS
- AJAX

### Installation

1. Clone the repository to your machine
1. Create .env file in the root directory and set up your database connection parameters. You can use the .env.example file as a template.
1. Create a database and import the SQL file located in the `setup` directory. The SQL file contains the necessary tables and initial data for the application.
1. To install the required dependencies, run the following command in the project directory:
```bash
composer install
```

### License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.