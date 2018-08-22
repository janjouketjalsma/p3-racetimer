var net = require('net');
var textChunk = '';
var server = net.createServer(function(socket) {
    socket.write(hex_to_ascii('8e023300cf02000001000104b29b0100030427fc70000408e819e6bd8a7504000502330006021000080200008104fc0504008f') + hex_to_ascii('8e023300cf02000001000104b29b0100030427fc70000408e819e6bd8a7504000502330006021000080200008104fc0504008f'), 'ascii');
    socket.write(hex_to_ascii('8e023300cf020000d1000104b29b0100030427fc70000408e819e6bd8a7504000502330006021000080200008104fc0504008f') + hex_to_ascii('8e023300cf020000d1000104b29b0100030427fc70000408e819e6bd8a750400050233000602100008020'), 'ascii');
});
server.listen(5403, '127.0.0.1');

server.on("connection", function(socket){
    setInterval(function(){
        socket.write(hex_to_ascii('8e023300cf02000001000104b29b0100030427fc70000408e819e6bd8a7504000502330006021000080200008104fc0504008f'), 'ascii');
    },10*1000);

});



function hex_to_ascii(str1)
{
    var hex  = str1.toString();
    var str = '';
    for (var n = 0; n < hex.length; n += 2) {
        str += String.fromCharCode(parseInt(hex.substr(n, 2), 16));
    }
    return str;
}