var App = App || {};

App.cryptography = (function () {
    "use strict";

    // -- local properties
    var bt_action = '#bt-action',
        form_txt = '#form',
        status_txt = '#status',
        text_txt = '#text',
        input_action = 'input[name="action"]',
        output_txt = '#output';


    /**
     * Setup function
     */
    function setup()
    {
        // -- bind cryptography
        bindCryptography();
    }

    /**
     * Bind cryptography
     */
    function bindCryptography()
    {
        $(bt_action).on('click', function(ev)
        {
            $.ajax({
                method: "POST",
                url: $(form_txt).attr('action'),
                data: $(form_txt).serialize(),
                beforeSend: function () {
                    showOutput('Aguarde...');
                },
                error: function (request, error)
                {
                    var msg = request.responseJSON == undefined ? request.responseText : request.responseJSON.output;
                    showOutput( msg);
                },
                success: function (response)
                {
                    showOutput("Original: "+$(text_txt).val()+" \n\n-- Resultado\n "+response.details+"");

                    $(text_txt).val(response.final);

                    $(bt_action).html( $(input_action).val() == 'encrypt' ? 'Desencriptar' : 'Criptografar' );
                    $(input_action).val( $(input_action).val() == 'encrypt' ? 'decrypt' : 'encrypt' );
                }
            });
        });
    }

    /**
     * Set status
     */
    function setStatus(text) {
        $(status_txt).html( text );
    }

    /**
     * Open
     */
    function openDivConnect()
    {
        $(div_ssh_connect_txt).fadeIn();
    }

    /**
     * Close
     */
    function closeDivConnect()
    {
        $(div_ssh_connect_txt).fadeOut(100);
    }

    /**
     * Open
     */
    function openDivCommand()
    {
        $(div_ssh_command_txt).fadeIn();
    }

    /**
     * Close
     */
    function closeDivCommand()
    {
        $(div_ssh_command_txt).fadeOut(100);
    }

    /**
     * Close
     */
    function showOutput(text)
    {
        $(output_txt).hide();
        $(output_txt).text();
        $(output_txt).text(text);
        $(output_txt).fadeIn();
    }

    /**
     * Constructor
     */
    function init() {
        setup();
    }

    // -- set public methods
    return {
        init: init
    }

}());

$(document).ready(function(){
    App.cryptography.init(); // is initialized by other places
});