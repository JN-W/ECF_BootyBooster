//Ouverture jquery
jQuery(document).ready(() => {

// Activation & Désactivation via les switch EN SYNTAXE JQUERY
    window.onload = () => {
        let activationSwitches = $("[type=checkbox]")
        for (let activationSwitch of activationSwitches ){
            $(activationSwitch).click( function(){
                $.ajax({
                    method: "GET",
                    url: `/partner/activation/${this.dataset.id}`
                })
            })
        }
    }


// Fermeture jquery
})