var net = require('net');
var textChunk = '';
var server = net.createServer(function(socket) {
	//socket.write('8e:02:33:00:cf:02:00:00:01:00:01:04:b2:9b:01:00:03:04:27:fc:70:00:04:08:e8:19:e6:bd:8a:75:04:00:05:02:33:00:06:02:10:00:08:02:00:00:81:04:fc:05:04:00:8f\r\n');
	//socket.write("�3 �   �� '�p �潊u 3    �� �", 'ascii');
	//socket.write("8e:02:33:00:cf:02:00:00:01:00:01:04:b2:9b:01:00:03:04:27:fc:70:00:04:08:e8:19:e6:bd:8a:75:04:00:05:02:33:00:06:02:10:00:08:02:00:00:81:04:fc:05:04:00:8f", 'ascii');
	//socket.write("3Ï²'üpèæ½u3ü", 'ascii');
	socket.write(hex_to_ascii('8e023300cf02000001000104b29b0100030427fc70000408e819e6bd8a7504000502330006021000080200008104fc0504008f'), 'ascii');
});
server.listen(5403, '127.0.0.1');


function hex_to_ascii(str1)
 {
	var hex  = str1.toString();
	var str = '';
	for (var n = 0; n < hex.length; n += 2) {
		str += String.fromCharCode(parseInt(hex.substr(n, 2), 16));
	}
	return str;
 }