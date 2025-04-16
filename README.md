![Alt text](images/connect4-logo.jpg)
# Connect4
## API for Connect4 game
![Alt text](https://img.shields.io/badge/release%20date-april%202025-blueviolet)

### About
Connect4 is a classic two-player strategy game where players take turns dropping colored discs into a vertical grid. The goal is to connect four of your discs in a row—horizontally, vertically, or diagonally—before your opponent does. 

This project provides a robust API for managing Connect4 games, allowing developers to integrate the game into their applications. The API handles game creation, player setup, moves, game state tracking, and more, ensuring a seamless multiplayer experience.

Whether you're building a web-based game, a mobile app, or a backend service, this API is designed to make it easy to implement Connect4 with minimal effort.

### API Endpoints
Here are the available API endpoints:

- **`api/game-confirm.php`**: Confirm readiness for the game.
- **`api/game-disconnect.php`**: Disconnect from the game and update the status.
- **`api/game-join.php`**: Join an existing game using a unique game ID.
- **`api/game-player-setup.php`**: Set up player details such as nickname and colors.
- **`api/game-put-ball.php`**: Place a ball in the specified column during your turn.
- **`api/game-revenge.php`**: Request a rematch after a game ends.
- **`api/game-start.php`**: Start a new game and initialize players.
- **`api/game-state.php`**: Fetch the current state of the game.

### Technologies used
- PHP 8

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
