var bodyParser = require('body-parser')
var request = require('request');
var osu = require('node-os-utils');
const { rastrearEncomendas } = require('correios-brasil');
var twoFactor = require('node-2fa');
var cpu = osu.cpu;
var mem = osu.mem;

var app = require('express')();
var fs = require('fs');
var options = {
    key: fs.readFileSync(__dirname + '/ssl/private.pem'),
    cert: fs.readFileSync(__dirname + '/ssl/certificate.pem'),
    ca: fs.readFileSync(__dirname + '/ssl/chain.pem')
};
var http = require('https').Server(options, app);
var io = require('socket.io')(http);
var rp = require('request-promise');

app.get('/', function (req, res) {
    res.redirect('https://gree-app.com.br/login');
});

app.get("/correios/rastreamento", function(req, res) {
  var codes = req.query.codes;
  var arrCodes = codes.split(",");
  let codRastreio = arrCodes// array de códigos de rastreios
 
  rastrearEncomendas(codRastreio).then((response) => {

    var json = new Array();
    for (let index = 0; index < codRastreio.length; index++) {
      var row = response[index];
      var pos = row.length - 1;

      json.push(row[pos]);
      
    }

    if (req.query.option == 1)
    res.send(json);
    else
    res.send(response);
  });
  
});

app.get("/twofa/criar", function(req, res) {
  var name = req.query.name;
  var code = req.query.code;
	
  var newSecret = twoFactor.generateSecret({name: name, account: code, qrw: 250, qrh: 250});
	
  res.send(JSON.stringify(newSecret));
  
});

app.get("/twofa/verificar", function(req, res) {
  var token = req.query.token;
  var code = req.query.code;
	
  var result = twoFactor.verifyToken(token, code);
  if (result)
  	res.send('true');
  else  
	res.send('false');
});

app.use( bodyParser.json() );       // to support JSON-encoded bodies
app.use(bodyParser.urlencoded({     // to support URL-encoded bodies
  extended: true
})); 

setInterval(() => {
	mem.info()
		.then(info => {
		var block_2 = (100 - info.freeMemPercentage).toFixed(2);
		io.emit('memory usage', {
			memomy: block_2,
		});
	})
	cpu.usage()
		.then(cpuPercentage => {
		var block_1 = cpuPercentage;
		io.emit('cpu usage', {
			cpu: block_1,
		});
	})
}, 10000);

io.on('connection', function(socket){

  // SEE USER CONNECTED
  socket.on('user', function(data){
    socket.r_code = data.id;
    socket.name = data.name;
    socket.picture = data.picture;
    socket.sector = data.sector;

    // CONNECT ANNOUNCE FOR USERS
    io.emit('user status', {
        status: 1,
        id: data.id,
    });
	  
	  var options = {
			method: 'POST',
			uri: 'https://gree-app.com.br/chat/status/' + data.id + '/1',
			body: {
				some: 'payload'
			},
			json: true // Automatically stringifies the body to JSON
		};

		rp(options)
			.then(function (parsedBody) {
				console.log(data.name + ' user connected');
			})
			.catch(function (err) {
				// POST failed...
			});

    
    socket.on('disconnect', function(){
      io.emit('user status', {
          status: 2,
          id: data.id,
      });

		var options = {
			method: 'POST',
			uri: 'https://gree-app.com.br/chat/status/' + data.id + '/0',
			body: {
				some: 'payload'
			},
			json: true // Automatically stringifies the body to JSON
		};

		rp(options)
			.then(function (parsedBody) {
				console.log(data.name + ' user disconnected');
			})
			.catch(function (err) {
				// POST failed...
			});
      
    });
  });

  
  
// Receiver request laravel
app.post("/newMessage", function(req, res) {
  var params = req.body;
  io.emit('chat message', {
     msg: params.msg,
     msg_total: params.msg_total,
     receiver: params.receiver,
     attach: params.attach,
     id: params.id,
     picture: params.picture,
     name: params.name,
     sector: params.sector,
     time: params.time,
     date: params.date,
     pm_id: params.pm_id
  });

  res.send();
});

app.post("/newVersion", function(req, res) {
  var params = req.body;
  io.emit('new version', {
      version: params.version,
  });

  res.send();
});	
	
app.post("/promoteGetPosition", function(req, res) {
  var params = req.body;
  io.emit('new position', {
      promoter_id: params.promoter_id,
      latitude: params.latitude,
      longitude: params.longitude,
  });

  res.send();
});

  
  
});


http.listen(3000, function(){
  console.log('listening on *:3000');
});