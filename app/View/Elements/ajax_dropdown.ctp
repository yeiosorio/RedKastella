<!-- 
    ELEMENTO SIN USO, 
    podria emplearse para refrescar contenido de select dependientes
    http://blog.jandorsman.com/blog/using-ajax-and-cakephp-to-dynamically-populate-select-form-fields
-->
<?php foreach($options as $key => $val) { ?>
<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
<?php } ?>