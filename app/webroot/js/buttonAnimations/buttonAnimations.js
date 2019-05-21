//Objeto que anima un contenedor con unas clases definidas
var animateButton = {
    selector: "",
    iconClass: "",
    
    /**
     * Función de animación de cargar
     */
    loading: function (state) {
    
        /**
         * Si es true animara con la clase del método addClass, en false, restaura la clase
         */
        if (state){

            $(this.selector).removeClass(this.iconClass);
            $(this.selector).addClass("fa fa-spinner fa-pulse");

        }else{
            $(document).find(this.selector).removeClass("fa fa-spinner fa-pulse");
            $(document).find(this.selector).addClass(this.iconClass);

        }

    }
}

// Uso del objeto
// 
// selector al que buscará
// animateButton.selector = '.btn-publish-gls';
// 
// clase que removera y agregara   
// animateButton.iconClass = 'fa fa-cloud-upload';
// 
// Uso del método loading quita la clase iconClass si esta en true, la agrega de nuevo en false 
// animateButton.loading(true);
// animateButton.loading(false);
// 
// 
// 




//Objeto que anima un contenedor con unas clases definidas
var animateButtonObj = {
    selector: undefined,
    iconClass: "",
    
    /**
     * Función de animación de cargar
     */
    loading: function (state) {
    
        /**
         * Si es true animara con la clase del método addClass, en false, restaura la clase
         */
        if (state){

            this.selector.removeClass(this.iconClass);
            this.selector.addClass("fa fa-spinner fa-pulse");

        }else{
            this.selector.removeClass("fa fa-spinner fa-pulse");
            this.selector.addClass(this.iconClass);

        }

    }
}