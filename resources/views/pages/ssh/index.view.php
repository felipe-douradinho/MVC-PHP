
<?php $this->start(); ?>

<h4><small>TESTE MT4</small></h4>
<hr>
<h2>Integração SSH </h2>
<h5>Framework próprio por Felipe D. v<?php echo \Golden\Foundation\Application::VERSION; ?></h5>

<p>&nbsp;</p>

<form id="form-connect" action="<?php echo route('ssh_integration.shell'); ?>" method="post">
    <div id="div-ssh-connect">
        <div class="row">
            <div class="col-md-8">

                <div class="row">
                    <div class="col-md-6">
                        <label for="device_id">Selecione o dispositivo*</label>
                        <?php if(isset($devices)) { ?>
                            <select name="device_id" id="device_id" class="form-control">
                                <?php foreach ($devices->getItems() as $key => $device) { ?>
                                    <option value="<?=$device['id']?>"><?=$device['hostname']?> (<?=$device['ip_address']?>)</option>
                                <?php } ?>
                            </select>
                        <?php } else { ?>
                            <br> <i>Desculpe, nenhum dispositivo cadastrado</i>
                        <?php } ?>
                    </div>
                </div>

                <?php if(isset($devices)) { ?>
                    <div class="row">&nbsp;</div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="username">Username*</label>
                            <input type="text" name="username" id="username" value="" class="form-control" />
                        </div>
                        <div class="col-md-3">
                            <label for="password">Senha*</label>
                            <input type="password" name="password" id="password" value="" class="form-control" />
                        </div>
                    </div>

                    <div class="row">&nbsp;</div>

                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-success bt-connect">Conectar</button>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>

    <div id="div-ssh-command" style="display: none;">

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
                        <button type="button" class="btn btn-success bt-connect">Enviar Comando</button>
                    </div>
                </div>

                <div class="row">&nbsp;</div>

                <figure class="highlight">
                    <pre>
                        <code class="language-html" id="output" data-lang="html"
                            style="float: left; padding-top: 10px;"></code>
                    </pre>
                </figure>

            </div>
        </div>
    </div>

</form>

<p>&nbsp;</p>
<span id="status"></span>


<script src="<?php echo asset('assets/app/ssh_integration.js'); ?>"></script>


<?php $this->end('content'); ?>

<?php $this->extend('layout.master'); ?>