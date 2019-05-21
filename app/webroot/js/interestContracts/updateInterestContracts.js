function InterestContractsClass (){
    
    // Propiedades del objeto
    var properties = new Object();
    var _this = this;
    
    // edita una fila de la tabla
    this.editInterestContract = function( id ){
        var fila = document.getElementById( id );
        properties.id        = fila.getAttribute( 'data-info' );
        properties.nombre    = fila.children[1].innerText;
        properties.title     = fila.children[2].innerText;
        properties.contenido = fila.children[3].innerText;
        properties.valor     = fila.children[4].innerText;

        $.post(
            $('#url-app').val() + "/InterestContracts/editInterestContracts",
            properties,
            function( data, status, xhr ){

            }

        );
    }

}

var InterestContracts = new InterestContractsClass();