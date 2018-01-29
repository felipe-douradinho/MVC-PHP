
<?php $this->start(); ?>

<h4><small>TESTE MT4</small></h4>
<hr>
<h2>Integração SSH (conectado)</h2>
<h5>Framework próprio por Felipe D. v<?php echo \Golden\Foundation\Application::VERSION; ?></h5>

<p>&nbsp;</p>

<form action="<?php echo route('ssh_integration.command'); ?>" method="post">

    <div class="row">
        <div class="col-md-8">

            <div class="row">
                <div class="col-md-3">
                    Status: <strong style="color: green;">Conectado</strong>
                </div>
            </div>

            <div class="row">&nbsp;</div>

            <div class="row">
                <div class="col-md-6">
                    <label for="command">Executar comando:</label>
                    <input type="text" name="command" id="command" class="form-control" />
                </div>
            </div>

            <div class="row">&nbsp;</div>

            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-success">Enviar Comando</button>
                </div>
            </div>

        </div>
    </div>

</form>

<?php $this->end('content'); ?>

<?php $this->extend('layout.master'); ?>