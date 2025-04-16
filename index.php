<?php
header('Content-Type: application/json');
http_response_code(200);

echo json_encode([
    'message' => 'Welcome to the Connect4 API!',
    'info' => 'This is an API instance. Please use the proper endpoints to interact with the system.',
    'endpoints (begin with api/)' => [
        'game-confirm.php' => 'Confirm readiness for the game.',
        'game-disconnect.php' => 'Disconnect from the game and update the status.',
        'game-join.php' => 'Join an existing game using a unique game ID.',
        'game-player-setup.php' => 'Set up player details such as nickname and colors.',
        'game-put-ball.php' => 'Place a ball in the specified column during your turn.',
        'game-revenge.php' => 'Request a rematch after a game ends.',
        'game-start.php' => 'Start a new game and initialize players.',
        'game-state.php' => 'Fetch the current state of the game.',
    ]
]);
exit;