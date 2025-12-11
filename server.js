import express from 'express';
import { createServer } from 'http';
import { Server } from 'socket.io';

const app = express();
const httpServer = createServer(app);
const io = new Server(httpServer, {
  cors: { origin: "*" } // allow requests from your Laravel site
});

const users = {}; // username → socket.id mapping

io.on("connection", (socket) => {
  console.log("New connection:", socket.id);

  // Register username (from frontend)
  socket.on("register", (username) => {
    socket.username = username;
    users[username] = socket.id;
    console.log(`${username} connected.`);
  });

  // Handle private messages
  socket.on("private_message", ({ to, message }) => {
    const toId = users[to];
    if (toId) {
      io.to(toId).emit("private_message", { from: socket.username, message });
      console.log(`Message from ${socket.username} → ${to}: ${message}`);
    }
  });

  // Cleanup on disconnect
  socket.on("disconnect", () => {
    for (const name in users) {
      if (users[name] === socket.id) {
        console.log(`${name} disconnected.`);
        delete users[name];
      }
    }
  });
});

httpServer.listen(3000, () => {
  console.log("✅ Socket.IO server running on http://localhost:3000");
});
