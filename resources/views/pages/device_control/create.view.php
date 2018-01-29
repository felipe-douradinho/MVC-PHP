
<?php $this->start(); ?>

<h4><small>TESTE MT4</small></h4>
<hr>
<h2>Controle de Dispositivos - Cadastro</h2>
<h5>Framework próprio por Felipe D. v<?php echo \Golden\Foundation\Application::VERSION; ?></h5>

<p>&nbsp;</p>

<form action="<?php echo route('devices.store'); ?>" method="post">

    <div class="row">
        <div class="col-md-8">

            <div class="row">
                <div class="col-md-6">
                    <label for="hostname">Hostname*</label>
                    <input type="text" name="hostname" id="hostname" value="<?php echo old('hostname'); ?>"
                           class="form-control" />
                </div>
                <div class="col-md-3">
                    <label for="ip_address">Endereço IP*</label>
                    <input type="text" name="ip_address" id="ip_address" value="<?php echo old('ip_address'); ?>"
                           class="form-control" />
                </div>
            </div>

            <div class="row">&nbsp;</div>

            <div class="row">
                <div class="col-md-5">
                    <label for="type">Tipo*</label>
                    <input type="text" name="type" id="type" value="<?php echo old('type'); ?>" class="form-control" />
                </div>
                <div class="col-md-4">
                    <label for="manufacturer">Fabricante*</label>
                    <input type="text" name="manufacturer" id="manufacturer" value="<?php echo old('manufacturer'); ?>"
                           class="form-control" />
                </div>
            </div>

            <div class="row">&nbsp;</div>

            <div class="row">
                <div class="col-md-6">
                    <label for="model">Modelo*</label>
                    <input type="text" name="model" id="model" value="<?php echo old('model'); ?>" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label for="active">Status*</label>
                    <select name="active" id="active" class="form-control">
                        <option value="1" <?php echo old('active') == 1 ?: 'selected'; ?>>Ativo</option>
                        <option value="0" <?php echo old('active') == 0 ?: 'selected'; ?>>Inativo</option>
                    </select>
                </div>
            </div>

            <div class="row">&nbsp;</div>

            <div class="row">
                <div class="col-md-6">
                   <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </div>

        </div>
    </div>

</form>



<?php $this->end('content'); ?>

<?php $this->extend('layout.master'); ?>