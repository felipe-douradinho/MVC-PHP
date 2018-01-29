var App = App || {};

App.ssh_integration = (function () {
    "use strict";

    // -- local properties
    var bt_connect_txt = '.bt-connect',

        form_connect_txt = '#form-connect',
        form_command_txt = '#form-command',

        div_ssh_connect_txt = '#div-ssh-connect',
        div_ssh_command_txt = '#div-ssh-command',

        output_txt = '#output';


    /**
     * Setup function
     */
    function setup()
    {
        // -- connect to ssh
        bindConnect();
    }

    /**
     * Connect to ssh
     */
    function bindConnect()
    {
        $(bt_connect_txt).on('click', function(ev)
        {
            $.ajax({
                method: "POST",
                url: $(form_connect_txt).attr('action'),
                data: $(form_connect_txt).serialize(),
                beforeSend: function () {
                    showCommand('Enviando comando...');
                },
                error: function (request, error) {
                    console.log('dd');
                },
                success: function (response) {
                    closeDivConnect();
                    openDivCommand();
                    showCommand(response.output);
                }
            });
        });
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
        $(div_ssh_connect_txt).fadeOut();
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
        $(div_ssh_command_txt).fadeOut();
    }

    /**
     * Close
     */
    function showCommand(text)
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
    App.ssh_integration.init(); // is initialized by other places
});