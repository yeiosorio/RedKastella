
  // websocket
  var socket;


  var urlNodeServer = "http://127.0.0.1:3001/";

  function getWsSessionToken(){
      
      return $.ajax({
            url: baseUrl+'users/getSessionToken',
            type: 'get',
            dataType: 'json',
      });

    }


    function connectWs(){


      /**
        * Conectamos al servidor
       */
       socket = io(urlNodeServer,{ query: 'token=' + localStorage.getItem("KastNotificationsToken")} );

       if(socket){

        console.log(socket);

           console.log("connected");
       
       }else{

          console.log("not connected");
       
       }

    }


    if(localStorage.getItem('KastNotificationsToken') == 'null' || localStorage.getItem('KastNotificationsToken') == null || localStorage.getItem('KastNotificationsToken') == undefined ){

        /**
         * Get token from current session
         */
        getWsSessionToken().done(function(res) {

                
               localStorage.setItem("KastNotificationsToken", res.token);

              /**
                * Conectamos al servidor
               */
              connectWs();

        })
        .fail(function(x) {
            console.log(x);
        });

    }else{


        /**
          * Conectamos al servidor
         */
        connectWs();
      

    }


    // Intercepción de logout
    $('.logout-action').click(function(e){

      e.preventDefault();

      var url = $(this).attr('href');
      localStorage.removeItem('KastNotificationsToken');

      setTimeout(function(){ 

  
      window.location.href = url;


    }, 1000);

    });

  $(window).on('beforeunload', function(){
      
      socket.close();
      

      console.log("closed socket");
  });



