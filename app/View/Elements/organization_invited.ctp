<?php
    // Aqui se visualizará si el usuario ha recibido la invitacion de una entidad
    // Esta vista no será empleada por el momento, ya que una vez la entidad envía una invitación,
    // dicha invitación es aceptada automáticamente y anexada entonces al perfil del usuario
    if (1)
    {
        $organization= "Alcaldía de Armenia";
        echo "Usted ha recibido invitación de contacto por parte de la entidad ".$organization.".";
        echo "Por favor ingrese <a href=\"#\">aquí</a> para añadir está entidad a su perfil";
        
    }
    else
    {
        echo "<p>No tiene invitaciones.</p>";
    }
?>