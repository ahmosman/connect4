![Alt text](images/connect4-logo.jpg)
# Connect4
## API for Connect4 game
![Alt text](https://img.shields.io/badge/release%20date-april%202025-blueviolet)

### About
Connect4 is a classic two-player strategy game where players take turns dropping colored discs into a vertical grid. The goal is to connect four of your discs in a row—horizontally, vertically, or diagonally—before your opponent does. 

This project provides a robust API for managing Connect4 games, allowing developers to integrate the game into their applications. The API handles game creation, player setup, moves, game state tracking, and more, ensuring a seamless multiplayer experience.

Whether you're building a web-based game, a mobile app, or a backend service, this API is designed to make it easy to implement Connect4 with minimal effort.

### Compatible Clients

-  Connect4Mobile: available at [Connect4Mobile GitHub Repository](https://github.com/ahmosman/Connect4Mobile).

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

### Installation

1. Clone the repository to your machine
1. Create .env file in the root directory and set up your database connection parameters. You can use the .env.example file as a template.
1. Create a database and import the SQL file located in the `setup` directory. The SQL file contains the necessary tables and initial data for the application.
1. To install the required dependencies, run the following command in the project directory:
```bash
composer install
```

### WebSocket Server
You can use the provided WebSocket server to handle real-time communication between players. The server is implemented in Node.js and uses the `socket.io` library to manage WebSocket connections.

#### Prerequisites
- Node.js (version 14 or higher)
- npm (Node Package Manager)

#### Installation
1. Navigate to the `websocket` directory.
2. Install the required dependencies by running the following command:
   ```bash
   npm install
   ```

#### Starting the Server
To start the WebSocket server, run the following command:
```bash
node websocket-server.js
```

#### Usage
The WebSocket server facilitates real-time updates for the following game events:
- **`joinGame`**: A player joins a specific game room.
- **`confirmGame`**: A player confirms readiness for the game.
- **`revengeRequest`**: A player requests a rematch after the game ends.
- **`playerMove`**: A player makes a move by placing a ball in a specific column.
- **`leaveGame`**: A player leaves the game room.
- **`disconnect`**: A player disconnects from the server.

Each event is broadcast to all players in the same game room, ensuring real-time updates for all participants.

#### `gameUpdate` Event
The `gameUpdate` event is the primary mechanism used by the server to broadcast updates to all players in a game room. It is triggered for various game events, such as:
- A player joining or leaving the game.
- A player confirming readiness.
- A player making a move.
- A player requesting a rematch.

The `gameUpdate` event sends a JSON object to all players in the room.

#### Notes
- The server listens on port `3000` by default. You can modify this in the `websocket-server.js` file if needed.
- The server uses CORS to allow connections from any origin. For production, you should restrict this to trusted domains.

This WebSocket server ensures a seamless real-time multiplayer experience for the Connect4 game.


### Technologies used
- PHP 8

### License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
