const io = require("socket.io")(server);

io.on("connection", (socket) => {
    console.log("New user connected");

    socket.on("sendMessage", (message) => {
        io.emit("receiveMessage", message);
    });
});
