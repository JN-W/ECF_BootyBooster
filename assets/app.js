/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import $ from 'jquery';
import 'bootstrap';
console.log ('le fichier asset/app.js est lu')

// uncomment to support legacy code (ou si je galère à la fin hein....)
// global.$ = $;


// start the Stimulus application
import './bootstrap';

// J'essaye d'ajouter mon js qui était dans les partial
$(() => {

    // Ajout évènement sur barre de recherche
    $('#search-partner').keyup(function () {
        let userSearchText = $(this).val();
        userSearchText = encodeURIComponent(userSearchText).toLowerCase();

        if (userSearchText !== "")
        {
            $.ajax
            (
                {
                    type: 'POST',
                    url: '/partner/recherche',
                    data: {userSearchText: userSearchText},
                    success: function (data){
                        $('.search-result').remove();
                        if (data !== "")
                        {
                            // TO DO : ne pas lancer la requête AJAX pour l'appui de touches type MAJ ou CTRL

                            for (let i = 0; i < data.length; i++) {
                                let current = JSON.parse(data[i]);
                                $('#search-partner-result').append(`<a class="search-result list-group-item list-group-item-action" href="#" >${current['name']}</a>`);
                            }
                        }
                    }

                }
            )
        }
    })

    //Focus on search field while modal pop up
    $("#exampleModal").on("shown.bs.modal", function(){
        $(this).find("input").first().focus();
    })

    // Clean old results and input field when closed
    $("#exampleModal").on("hidden.bs.modal", function(){
        $('#search-partner').val('');
        $('.search-result').remove();
    })

    // Code à essayer de la doc bootstrap our le focus
    // $('#myModal').on('shown.bs.modal', function () {
    //     $('#myInput').trigger('focus')
    // })

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


    // Modal de confirmation
    var theHref;
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




    //Fermeture jquery
});