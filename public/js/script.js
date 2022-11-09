//Ouverture jquery
jQuery(document).ready(() => {

//     Activate & Deactivate partner using switch in jquery syntax
    window.onload = () => {
        let activationSwitches = $(".partner-switch[type=checkbox]")
        for (let activationSwitch of activationSwitches ){
            $(activationSwitch).click( function(){
                $.ajax({
                    method: "GET",
                    url: `/partner/activation/${this.dataset.id}`
                })
            })
        }
    }

    //     Activate & Deactivate structure using switch in jquery syntax
    // Controller si ça applique les 2 activation.switch en même temps vu qu'on ecoute tous les switch de type checkbox
    window.onload = () => {
        let activationSwitches = $(".structure-switch[type=checkbox]")
        for (let activationSwitch of activationSwitches ){
            $(activationSwitch).click( function(){
                $.ajax({
                    method: "GET",
                    url: `/structure/activation/${this.dataset.id}`
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