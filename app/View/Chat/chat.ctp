<div class="sidebar sidebar-chat left  chat-skin-white sidebar-visible-desktop" id="">
      <div class="split-vertical">
        <div class="chat-search">
          <input type="text" class="form-control" placeholder="Buscar">
        </div>

        <ul class="chat-filter nav nav-pills ">
          <li class="active"><a href="#" data-target="li">Todos</a></li>
          <li><a href="#" data-target=".online">Online</a></li>
          <li><a href="#" data-target=".offline">Offline</a></li>
        </ul>
        <div class="split-vertical-body">
          <div class="split-vertical-cell">
            <div data-scrollable="" tabindex="1" style="overflow-y: hidden; outline: none;">
              <ul class="chat-contacts ks-chat-contacts">
                


              <?php foreach ($users as $user) : ?>
				
				      <li class="online" data-user-id="<?php echo $user['User']['id']; ?>" data-user-username="<?php echo $user['User']['username']; ?>" data-user-profile-pic="<?php echo $user['User']['profilePic']; ?>">

                  <a href="#">
                    <div class="media">
                      <div class="pull-left">
                        <span class="status"></span>
                        <div class="circled-image" style="background-image:url('<?php echo $user['User']['profilePic']; ?>')">
                        	
                        </div>
                 
                      </div>
                      <div class="media-body">

                        <div class="contact-name"><?php echo $user['User']['username']; ?></div>
                        <small>"Free Today"</small>
                      </div>
                    </div>
                  </a>
                </li>
              	
              <?php endforeach; ?>

              </ul>
            </div>
          </div>
        </div>
      </div>
    

    <div id="ascrail2001" class="nicescroll-rails" style="width: 5px; z-index: 2; cursor: default; position: absolute; top: 87px; left: 195px; height: 439px; opacity: 0;">
    		<div style="position: relative; top: 0px; float: right; width: 5px; height: 680px; border: 0px; border-radius: 5px; background-color: rgb(52, 152, 219); background-clip: padding-box;">
    		
    		</div>
    </div>

    </div>



	<div class="chat-window-container" id="Main-Chat-Container">

	    
    </div>


<?php 


    /**
     * Arreglo con los scripts necesarios
     * @var Array
     */
    $scripts = Array(

        /**
         * Jquery ui
         */
        'chat/chat',

    );   
    
    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 



?>

