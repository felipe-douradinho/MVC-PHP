var App = App || {};

App.hash = (function () {
    "use strict";

    // -- local properties
    var bt_action = '#bt-action',
        form_txt = '#form',
        status_txt = '#status',
        text_txt = '#text',
        output_txt = '#output';


    /**
     * Setup function
     */
    function setup()
    {
        // -- bind hashs
        bindHash();
    }

    /**
     * Bind hashs
     */
    function bindHash()
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
                    showOutput("Original: "+$(text_txt).val()+" \n\n-- Resultado\n "+response.output+"");
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
    App.hash.init(); // is initialized by other places
});