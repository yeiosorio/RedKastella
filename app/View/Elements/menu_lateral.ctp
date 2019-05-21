
<?php


      //http://enlacabezadecesar.com/blog/menu-desplegable-con-css/
    $id=$this->session->read('User.id');
    $username=$this->session->read('User.username');
    $avatar=$this->session->read('User.avatar');
    //print_r($opciones_menu);
    //
     $loggedUser = AuthComponent::user();  

     $my_rol=$this->Session->read('User.rol_id');

     $serverUrl = Router::url('/', true);
?>

<style>
    .sidebar-menu > li > a
    {
        font-size:1rem !important;
        padding-left: 5px !important;
    }
</style>

 <!-- Sidebar component with st-effect-1 (set on the toggle button within the navbar) -->
    <div class="sidebar left sidebar-size-2 sidebar-offset-0 sidebar-visible-desktop sidebar-visible-mobile sidebar-skin-dark" id="sidebar-menu" style="bottom: 5px;">
      
      <!-- Ocultamos la barra de Scroll -->
      <div data-scrollable="" tabindex="1" style="overflow-y: hidden !important; outline: none;">
      
      <?php 
            if (isset($id)) { 
      ?>
        <div class="sidebar-block">
          <div class="profile">
              
               <!-- Imagen de perfil -->
              <a href="<?php echo $serverUrl.'users/profile/'.$loggedUser['username']; ?>">
                <div class="left-menu-pic edit-pic-prof" style="background-image:url('<?php echo $loggedUser['profilePic']; ?>')"></div>
              </a>
        
            <h4>  
                <?php echo $loggedUser['username']; ?>
            </h4>
        
          </div>
        </div>
        <div class="category"></div>
        <div class="sidebar-block">
          <ul class="sidebar-menu sm-bordered sm-active-item-bg" id="side-menu">
            

            <?php
    

              $opciones_menu = $this->requestAction(array('plugin'=>'acl','controller'=>'acl', 'action'=>'getRoleActions',$loggedUser['role_id']));



              if (isset($id)) { //solo muestra el menu, si hay un usuario logueado
              foreach($opciones_menu as $opcion_menu) {
              ?>  

                <li>
                  <a href="<?php echo $serverUrl.$opcion_menu['Aros']['name'].'/'.$opcion_menu['Acos']['name']; ?>"> 
                  <i class="<?php echo $opcion_menu['Acos']['icon']; ?>"></i><?php echo $opcion_menu['Acos']['visible_name']; ?>
                  </a>
                </li>   
              <?php
                }
              }
            ?> 

              
          </ul>
        </div>
        <?php /*<div class="category">Photos</div>
        <div class="sidebar-block">
          <div class="sidebar-photos">
            <ul>
              <li>
                <a href="#">
                  <img src="images/place1.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="images/place2.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="images/place3.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="images/food1.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="images/food1.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="images/place3.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="images/place2.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="images/place1.jpg" alt="people" />
                </a>
              </li>
            </ul>
            <a href="#" class="btn btn-primary btn-xs">view all</a>
          </div>
        </div>
        <div class="category">Activity</div>
        <div class="sidebar-block">
          <ul class="sidebar-feed">
            <li class="media">
              <div class="media-left">
                <span class="media-object">
                            <i class="fa fa-fw fa-bell"></i>
                        </span>
              </div>
              <div class="media-body">
                <a href="" class="text-white">Adrian</a> just logged in
                <span class="time">2 min ago</span>
              </div>
              <div class="media-right">
                <span class="news-item-success"><i class="fa fa-circle"></i></span>
              </div>
            </li>
            <li class="media">

              <div class="media-left">
                <span class="media-object">
                            <i class="fa fa-fw fa-bell"></i>
                        </span>
              </div>
              <div class="media-body">
                <a href="" class="text-white">Adrian</a> just added <a href="" class="text-white">mosaicpro</a> as their office
                <span class="time">2 min ago</span>
              </div>
              <div class="media-right">
                <span class="news-item-success"><i class="fa fa-circle"></i></span>
              </div>
            </li>
            <li class="media">
              <div class="media-left">
                <span class="media-object">
                            <i class="fa fa-fw fa-bell"></i>
                        </span>
              </div>
              <div class="media-body">
                <a href="" class="text-white">Adrian</a> just logged in
                <span class="time">2 min ago</span>
              </div>
            </li>
            <li class="media">
              <div class="media-left">
                <span class="media-object">
                            <i class="fa fa-fw fa-bell"></i>
                        </span>
              </div>
              <div class="media-body">
                <a href="" class="text-white">Adrian</a> just logged in
                <span class="time">2 min ago</span>
              </div>
            </li>
            <li class="media">
              <div class="media-left">
                <span class="media-object">
                            <i class="fa fa-fw fa-bell"></i>
                        </span>
              </div>
              <div class="media-body">
                <a href="" class="text-white">Adrian</a> just logged in
                <span class="time">2 min ago</span>
              </div>
            </li>
          </ul>
        </div>*/ ?>
    <?php } //fin data scrollable ?>
      </div>
    </div>

 


<?php if (isset($opciones_menu)) : ?> 

<script type="text/javascript">
  
  //var menu_arr = <?php echo json_encode($opciones_menu); ?>;

  //console.log(menu_arr);


</script>

<?php endif; ?>







