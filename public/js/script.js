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


    // Modal de confirmation de suppression
    // var theHref;
    $('a.btn-delete').click(function(e) {
        e.preventDefault();
        theHREF = $(this).attr("href");
        $('#deleteConfirmModal').modal('show');
    });

    $("#confirmModalYes").click(function(e) {
        window.location.href = theHREF;
    });

    $("#confirmModalNo").click(function(e) {
        $("#deleteConfirmModal").modal("hide");
    });




// Fermeture jquery
})