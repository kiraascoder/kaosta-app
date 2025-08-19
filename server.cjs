const io = require("socket.io")(6001, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"],
    },
});

io.on("connection", (socket) => {
    console.log("User connected");

    socket.on("disconnect", () => {
        console.log("User disconnected");
    });

    socket.on("notification", (data) => {
        io.emit("notification", data);
    });
});

console.log("Socket.IO server running on port 6001");
