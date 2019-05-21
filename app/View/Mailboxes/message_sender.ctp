

<div class="col-md-10 col-lg-8" >
    <h3>Mensaje para todos los usuarios</h3>
        <div class="panel panel-default">
            <div class="panel-body">

				<form action="<?php echo Router::url('/', true); ?>Mailboxes/messageSender" method="post">
						
					<label>Asunto</label>
					<input name="asunto" class="form-control" required/>


					<label>Mensaje</label>

					<textarea name="message" required class="form-control" rows="5"></textarea>

					<br />
					<input type="submit" value="Enviar" class="btn btn-primary btn-block">

				</form>
			</div>
		</div>
</div>