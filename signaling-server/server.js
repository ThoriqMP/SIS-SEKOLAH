const http = require('http');
const { Server } = require('socket.io');

const PORT = process.env.PORT || 3000;

const server = http.createServer((req, res) => {
    if (req.url === '/health') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ status: 'ok', service: 'sis-sekolah-signaling', uptime: process.uptime() }));
        return;
    }
    res.writeHead(200, { 'Content-Type': 'text/plain' });
    res.end('SIS SEKOLAH WebRTC Signaling Server Running');
});

const io = new Server(server, {
    cors: {
        origin: process.env.CORS_ORIGIN || '*',
        methods: ['GET', 'POST'],
    },
    transports: ['websocket', 'polling'],
});

const rooms = new Map();

io.on('connection', (socket) => {
    socket.on('join-room', ({ roomId, userId, userName, role }) => {
        if (!roomId) return;
        socket.join(roomId);
        socket.data = { roomId, userId, userName, role };

        if (!rooms.has(roomId)) {
            rooms.set(roomId, new Map());
        }
        const roomPeers = rooms.get(roomId);

        const existingPeers = Array.from(roomPeers.values());
        socket.emit('existing-peers', existingPeers);

        roomPeers.set(socket.id, { socketId: socket.id, userId, userName, role });
        socket.to(roomId).emit('user-joined', { socketId: socket.id, userId, userName, role });
    });

    socket.on('offer', ({ to, offer }) => {
        io.to(to).emit('offer', { from: socket.id, offer, user: socket.data });
    });

    socket.on('answer', ({ to, answer }) => {
        io.to(to).emit('answer', { from: socket.id, answer });
    });

    socket.on('ice-candidate', ({ to, candidate }) => {
        io.to(to).emit('ice-candidate', { from: socket.id, candidate });
    });

    socket.on('chat-message', ({ roomId, message, userName }) => {
        io.to(roomId).emit('chat-message', { userName, message, time: new Date().toISOString() });
    });

    socket.on('disconnect', () => {
        const { roomId, userId, userName } = socket.data || {};
        if (roomId && rooms.has(roomId)) {
            const roomPeers = rooms.get(roomId);
            roomPeers.delete(socket.id);
            if (roomPeers.size === 0) {
                rooms.delete(roomId);
            }
            socket.to(roomId).emit('user-left', { socketId: socket.id, userId, userName });
        }
    });
});

server.listen(PORT, '0.0.0.0', () => {
    console.log(`[Signaling] SIS SEKOLAH WebRTC Signaling listening on port ${PORT}`);
});
