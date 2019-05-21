

<?php
  $styles = array(
      'bootstrap.min.css',
      'styles.min.css',
  );

  $scripts = array(
      'jquery.min.js',
      'bootstrap.min.js',
      'script.min.js'

  );

  /**
   * imprimimos los estilos
   */
  echo $this->Html->css($styles, null, array('block' => 'css'));

  /**
   * Imprimimos los scripts
   */
  echo $this->Html->script($scripts, array('block' => 'scriptBottom'));
?>

