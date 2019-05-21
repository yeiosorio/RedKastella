<!-- Elemento que contiene todo lo necesario para las preferencias de busqueda de contratos -->
<div class="row">
	<!-- Categorias padre de tipos de contrato -->
	<div class="col-md-5">
		<label>Tipos de Contrato:</label>
			<select id="prefContractsParentCategory" class="form-control" ></select>
	</div>	
	<!-- Fin categorias padre de tipos de contrato -->
	
	<div class="col-md-5 col-md-offset-1">
		<label>Departamento:</label>	
		
		<select class="form-control departments" >
			
		</select>
	

	</div>	

</div>

<br/>

<!-- Elemento que contiene todo lo necesario para las preferencias de busqueda de contratos -->
<div class="row">


<!-- Categorias de tipos de contrato -->	
	<div class="col-md-5">
		<label>Categorias:</label>
			<select id="prefContractsCategory" class="form-control" size="5"></select>
	</div>
	<!-- Fin categorias de tipos de contrato -->

	<!-- Acción para guardar preferencias de categorias -->
	<div class="col-md-1">


		<label>&nbsp;</label>
	
		<!-- <button class="btn btn-primary" id="prefSelCategory">>></button> -->
		<button class="btn btn-primary btn-block" id="prefSelCategory">>></button>

		<br />

		<button class="btn btn-primary btn-block" id="remove-item"><i class="fa fa-remove"></i></button>
		<br />

	</div>
	<!-- Fin de acciones -->


	<!-- Categorias de tipos de contrato -->	
	<div class="col-md-5">
		<label>Selección:</label>
			<select  class="form-control selected-categories" size="5"></select>
	</div>
	<!-- Fin categorias de tipos de contrato -->

</div>

<!-- Elemento que contiene todo lo necesario para las preferencias de busqueda de contratos -->
<div class="row">


<!-- Categorias de tipos de contrato -->	
	<div class="col-md-12">
		<label style="color:#777777;" class="current-selected-category"></label>
	</div>
	<!-- Fin categorias de tipos de contrato -->
</div>


<!-- sección de categorias de preferencias y rango de valores -->
<div class="row">	

<!-- Rango de valores -->
	<div class="col-md-6 valuesContainer">
		<h4><strong>Rango de Valor para la categoria:</strong> <span class="category-to-set" ></span></h4><br />
		
		<!-- Campos escondidos  donde se ponen los valores guardados, si no hay valores estos tendran los valores por defecto -->
			<!-- Valor mínimo -->
			<input type="hidden" class="currencyField1" />
			<!-- Valor máximo  -->
			<input type="hidden" class="currencyField2" />
		<!-- Fin campos de valores -->

		<!-- Label del valor Mínimo -->
		<div style="width:100px; float:left;">
			<span class="currencyFieldLabel1" ></span>
		</div>
		<!-- fin label valor Mímino -->

		<!-- Slider de selección de valores -->
		<input type="text" class="slider-values sliderMoneyRangePreferences"  />	
		<!-- Fin slider -->
	
		<!-- Espacios necesarios para que no se sobreponga el boton del slide maximo sobre el valor a mostrar el en label -->
		&nbsp;&nbsp;
		<!-- Label de valor Máximo -->
		<span class="currencyFieldLabel2" ></span>
		<!-- Fin label valor Máximo -->

	</div>
<!-- Fin rango de valores -->

	<div class="col-md-2 col-md-offset-3">
		<label></label>
		<br/><br/><br/>
		<button type="button" id="savePreferences" class="btn btn-primary btn-block">
			<i class="btn-save-gls fa fa-search"></i>&nbsp;Buscar
		</button>
	</div>


</div>



<!-- Fin bloque Guardar -->








