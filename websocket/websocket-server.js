const io = require('socket.io')(3000, {
    cors: { origin: '*' }, // Allow all origins
});

io.on('connection', (socket) => {
    console.log(`Client connected: ${socket.id}`);

    // Handle game events
    const handleEvent = (event, gameId, message) => {
        console.log(`${event} in game ${gameId} by ${socket.id}`);
        io.to(gameId).emit('gameUpdate', { message });
    };

    socket.on('joinGame', (gameId) => {
        socket.join(gameId);
        handleEvent('joinGame', gameId, `Player ${socket.id} joined the game!`);
    });

    socket.on('confirmGame', (gameId) => {
        handleEvent('confirmGame', gameId, `Game ${gameId} confirmed by player ${socket.id}`);
    });

    socket.on('revengeRequest', (gameId) => {
        handleEvent('revengeRequest', gameId, `Player ${socket.id} requested a rematch!`);
    });

    socket.on('playerMove', ({ gameId, column }) => {
        console.log(`Move in game ${gameId}: column ${column}`);
        io.to(gameId).emit('gameUpdate', { column });
    });

    socket.on('leaveGame', (gameId) => {
        socket.leave(gameId);
        handleEvent('leaveGame', gameId, `Player ${socket.id} left the game!`);
    });

    socket.on('disconnect', () => {
        console.log(`Client disconnected: ${socket.id}`);
    });
});

console.log('WebSocket server is running on port 3000');