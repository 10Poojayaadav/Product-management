
import io from 'socket.io-client';
const socket = io(import.meta.env.VITE_SOCKET_URL, {
    transports: ['websocket']
});
socket.on('connect', function () {
    console.log('Socket Has been Connected.')
});

socket.on('disconnect', function () {
    console.log('Socket Has been Disconnected.')
});

export default socket;


