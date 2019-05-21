
<?php 
    
    /**
     * Usuario logueado
     * @var Array
     */
    $loggedUser = AuthComponent::user();  


    /**
     * Opciones del menu principal
     * @var Array
     */
    $menuOptions = $this->requestAction(array('plugin'=>'acl','controller'=>'acl', 'action'=>'getRoleActions',$loggedUser['role_id']));
      

    /**
     * Url del servidor
     * @var Array
     */
    $serverUrl = Router::url('/', true);
?>


<style> 
    .sidebar-menu > li > a
    {
        font-size:1rem !important;
        padding-left: 5px !important;
    }
</style>

 


<!--
<a href="" class="sidebar-toggle-box">Toogle</a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
            <span class="sr-only">Toggle navigation2</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
<a href="#menu-toggle" class="btn btn-default" id="menu-toggle" style="position:absolute; z-index:100; background-color:red">Toggle MenuUUUUUUU</a>
-->




   <div id="sidebar-wrapper">
      <div class="profile">
              
               <!-- Imagen de perfil -->
              <a href="<?php echo $serverUrl.'users/profile/'.$loggedUser['username']; ?>">

                <div class="left-menu-pic edit-pic-prof" style="background-image:url('<?php echo $loggedUser['profilePic']; ?>')"></div>
              </a>
        
            <h4>  
                <?php echo $loggedUser['username']; ?>
            </h4>
        
          </div>
      <!--   <div class="category"></div> -->
        <div class="sidebar-block">

        <!-- sm-bordered -->
          <ul class="sidebar-menu" id="side-menu">

              <?php foreach($menuOptions as $menuOption) : ?>  

                <!-- active -->
                
                <li>
                  <a href="<?php echo $serverUrl.$menuOption['Aros']['name'].'/'.$menuOption['Acos']['name']; ?>" class="menu-links-heigth <?php echo 'idenT'.$menuOption['Acos']['name'];  ?>" > 
                  <i class="<?php echo $menuOption['Acos']['icon']; ?>"></i><?php echo $menuOption['Acos']['visible_name']; ?>
                  </a>
                </li>   
                
              <?php endforeach; ?> 


<!--           <li class="active"><a href="essential-buttons.html"><i class="fa fa-th"></i> <span>Buttons</span></a></li>
          <li><a href="essential-icons.html"><i class="fa fa-paint-brush"></i> <span>Icons</span></a></li>
          <li><a href="essential-progress.html"><i class="fa fa-tasks"></i> <span>Progress</span></a></li>
          <li><a href="essential-grid.html"><i class="fa fa-columns"></i> <span>Grid</span></a></li>
          <li><a href="essential-forms.html"><i class="fa fa-sliders"></i> <span>Forms</span></a></li>
          <li><a href="essential-tables.html"><i class="fa fa-table"></i> <span>Tables</span></a></li>
          <li><a href="essential-tabs.html"><i class="fa fa-circle-o"></i> <span>Tabs</span></a></li> -->

        
        </ul>
        </div>

        <!-- <div class="category">About</div>
        <div class="sidebar-block">
          <ul class="list-about">
            <li><i class="fa fa-map-marker"></i> Amsterdam, NL</li>
            <li><i class="fa fa-link"></i> <a href="#">www.mosaicpro.biz</a></li>
            <li><i class="fa fa-twitter"></i> <a href="#">/mosaicprobiz</a></li>
          </ul>
        </div>
         -->

<!--         <div class="category">Photos</div>
        <div class="sidebar-block">
          <div class="sidebar-photos">
            <ul>
              <li>
                <a href="#">
                  <img src="img/theme-img/place1.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/theme-img/place2.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/theme-img/place3.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/theme-img/food1.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/theme-img/food1.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/theme-img/place3.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/theme-img/place2.jpg" alt="people" />
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/theme-img/place1.jpg" alt="people" />
                </a>
              </li>
            </ul>
            <a href="#" class="btn btn-primary btn-xs">view all</a>
          </div>
        </div> -->


<!--         <div class="category">Activity</div>
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
        </div> -->


      </div>

<script>
    
    $().ready(function(){})
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-menu").toggleClass("toggled");
        
        alert(1);
    });
    </script>