<?php
echo $this->Html->script('interestContracts/updateInterestContracts.js');
?>
<input id="url-app" type="hidden" value="<?= $this->html->url('/', true); ?>" />
<h3>Actualizar contratos</h3>

<table class="table table-hover">

    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Título</th>
            <th>Contenido</th>
            <th>valor</th>
            <th>Opción</th>
        </tr>
    </thead>

    <?php foreach( $contracts as $valor ): ?>
        <tr id="fila-<?= $valor['interest_contracts']['id']; ?>" data-info="<?= $valor['interest_contracts']['id']; ?>" >
            <td contenteditable='true' > <?= $valor['interest_contracts']['id']; ?></td>
            <td contenteditable='true' ><?= $valor['interest_contracts']['nombre']; ?></td>
            <td contenteditable='true' ><?= $valor['interest_contracts']['title']; ?></td>
            <td contenteditable='true' ><?= $valor['interest_contracts']['contenido']; ?></td>
            <td contenteditable='true' ><?= $valor['interest_contracts']['valor']; ?></td>
            <td>
                <button class="btn btn-primary" onclick="InterestContracts.editInterestContract( 'fila-<?= $valor['interest_contracts']['id']; ?>' )" >
                    Actualizar
                </button>
            </td>
        </tr>
    <?php endforeach; ?>

</table>
