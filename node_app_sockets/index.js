var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

var bodyParser  = require('body-parser');
var morgan      = require('morgan');


var config = require('./config'); 
var jwt    = require('jsonwebtoken');
app.set('superSecret', config.secret); 


// use body parser so we can get info from POST and/or URL parameters
app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());

// use morgan to log requests to the console
app.use(morgan('dev'));


// Load the bcrypt module
var bcrypt = require('bcrypt');


// =========================
// Database Configuration
// =========================
var knex = require('knex')({
  client: 'mysql',
  connection: {
    host     : '127.0.0.1',
    user     : 'root',
    password : '',
    database : 'kastella',
    charset  : 'utf8'
  }
});

var bookshelf = require('bookshelf')(knex);

var User = bookshelf.Model.extend({
  tableName: 'users'
});

// ==========================
// End Database Configuration
// ==========================



// route middleware to verify a token
app.use(function(req, res, next) {

  //en vez de * se puede definir SÓLO los orígenes que permitimos
  res.header('Access-Control-Allow-Origin', '*'); 
  //metodos http permitidos para CORS
  res.header('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE'); 
  res.header('Access-Control-Allow-Headers', 'Content-Type');
  next();


});


app.post('/authenticate', function (req, res) {

  var username = req.body.username;
  var password = req.body.password;

  User.where('username', username).fetch().then(function(response) {

      var user = response.toJSON();


      
      if(bcrypt.compareSync(password, user.password)){

        
        // if user is found and password is right
        // create a token
        var token = jwt.sign(user, app.get('superSecret'), {
          expiresIn: 360 * 24 // expires in 24 hours
        });

        // return the information including token as JSON
        res.json({
          success: true,
          message: 'Enjoy your token!',
          token: token
        });

      } else{
        
        res.json({
              success: false,
              message: 'Authentication failed. Password doesn\'t Match!',
        });          
        

      }

  }).catch(function(err) {

    res.json({
        success: false,
        message: 'Authentication failed. User not found!',
    });

  });


});


// io.use(function(socket, next){

//     // if (socket.request.headers.cookie) 
//     // 


//     // console.log(socket.request);


//     var parts = require('url').parse(socket.handshake.url,true).query;



//     if(parts.token != undefined ){

//           // check header or url parameters or post parameters for token
//           var token = parts.token;

//           // decode token
//           if (token) {

//             // verifies secret and checks exp
//             jwt.verify(token, app.get('superSecret'), function(err, decoded) {      
//               if (err) {
                
//                  next(new Error('Authentication error, No token provided.'));

//                 // return res.json({ success: false, message: 'Failed to authenticate token.' });    
              
//               } else {
//                 // if everything is good, save to request for use in other routes
//                 // req.decoded = decoded;    
//                 return next();
              
              
//               }
//             });

//           } else {

//             // if there is no token
//             // return an error
      
//             next(new Error('Authentication error, No token provided.'));

            
//           }


//     }else{

//       next(new Error('Authentication error, No token provided.'));

//     }

// });






var users = new Array();


(function() {
  var c = 0;
  var timeout = setInterval(function() {


    console.log('users:');
    console.log(users);
    console.log('End users:');
    
    var d = new Date();
    console.log(d.toLocaleString());


  }, 1000);
  
})();



io.on('connection', function(socket){



    // socket.on('authenticate', function(data){
        
    //   //check the auth data sent by the client
    
    //   checkAuthToken(data.token, function(err, success){
      
    //     if (!err && success){
      
    //       console.log("Authenticated socket ", socket.id);
    //       socket.auth = true;
        
    //     }
      
    // });


  

      // Funcion que agrega información del usuario conectado a un arreglo de usuarios
	   socket.on('storeClientInfo', function (data) {


            users.push({username: data.username, socketId :socket.id});

        });
		  

  
    // Funcion que emite un like a todos los usuarios diferentes del emisor
    socket.on('chat-message', function(info){


      for (var i = 0; i < users.length; i++) {

          var c = users[i];

          if (c.username == info.username) {

                io.to(c.socketId).emit('chat-message', info);

                console.log("message sent");
      

          }
        }
         
    });
        


    // Funcion que emite un like a todos los usuarios diferentes del emisor
  	socket.on('like', function(msg){
    	
      
  		for (var i = 0; i < users.length; i++) {

  			  var c = users[i];

  			  if (c.username != msg.username) {

  			  	    io.to(c.socketId).emit('like', msg);


  			  }

  		};

    	//io.emit('like', msg);
  	
  	});


  	socket.on('disconnect', function (data) {

            for( var i=0, len=users.length; i<len; ++i ){
                var c = users[i];

                if(c.socketId == socket.id){
                    users.splice(i,1);
                    break;
                }
            }

        });

});

http.listen(3000, function(){
  console.log('listening on *:3000');
});
