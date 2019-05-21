<!-- Elemento que contiene todo lo necesario para mostrar la busqueda de tipos de contrato -->


<div class="row">
	<!-- Categorias padre de tipos de contrato -->
	<div class="col-md-4">
		<label>Tipos de Contrato:</label>
			<select id="contractsParentCategory" class="form-control" ></select>
	</div>	
	<!-- Fin categorias padre de tipos de contrato -->

	<!-- Categorias de tipos de contrato -->	
	<div class="col-md-4">
		<label>Categorias:</label>
			<select id="contractsCategory" class="form-control" ></select>
	</div>
	<!-- Fin categorias de tipos de contrato -->

</div>

<br />
<div class="row">
	<!-- traemos el elemento de departments que se encuentra en el directorio localization -->
		<div class="col-md-4">
		    <?php echo $this->element('localization/departments'); ?>
		</div>
    <!-- Fin elemento de departamentos -->

	<!-- traemos el elemento ciudades que se encuentra en el directorio localization -->
		<div class="col-md-4">
		    <?php echo $this->element('localization/municipalities'); ?>
		</div>
	<!-- Fin elemento de ciudades -->    

	<!-- Acción de ver Resultados -->
		<div class="col-md-4">
			<label>&nbsp;</label><br />
			<button class="btn btn-primary" id="seeResults"><i class="btn-search-gls fa fa-search"></i> Buscar</button>
		</div>
	<!-- Fin acción de ver resultados -->
</div>

	<!-- Elemento en el que se insertan los resutlados de la busqueda -->
	<div class="row">
		<div class="col-md-12" id="contentResults"></div>	
	</div>
	<!-- Fin Resultados de Búsqueda  -->


