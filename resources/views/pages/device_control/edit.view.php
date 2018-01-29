
<?php $this->start(); ?>

<h4><small>TESTE MT4</small></h4>
<hr>
<h2>Controle de Dispositivos - Editar <?php echo $device->hostname; ?></h2>
<h5>Framework próprio por Felipe D. v<?php echo \Golden\Foundation\Application::VERSION; ?></h5>

<p>&nbsp;</p>

<form action="<?php echo route('devices.update', $device->id); ?>" method="post">

    <div class="row">
        <div class="col-md-8">

            <div class="row">
                <div class="col-md-6">
                    <label for="hostname">Nome*</label>
                    <input type="text" name="hostname" id="hostname" value="<?php echo $device->hostname; ?>"
                           class="form-control" />
                </div>
                <div class="col-md-3">
                    <label for="ip_address">Endereço IP*</label>
                    <input type="text" name="ip_address" id="ip_address" value="<?php echo $device->ip_address; ?>"
                           class="form-control" />
                </div>
            </div>

            <div class="row">&nbsp;</div>

            <div class="row">
                <div class="col-md-5">
                    <label for="type">Tipo*</label>
                    <input type="text" name="type" id="type" value="<?php echo $device->type; ?>" class="form-control" />
                </div>
                <div class="col-md-4">
                    <label for="manufacturer">Fabricante*</label>
                    <input type="text" name="manufacturer" id="manufacturer" value="<?php echo $device->manufacturer; ?>"
                           class="form-control" />
                </div>
            </div>

            <div class="row">&nbsp;</div>

            <div class="row">
                <div class="col-md-6">
                    <label for="model">Modelo*</label>
                    <input type="text" name="model" id="model" value="<?php echo $device->model; ?>" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label for="active">Status*</label>
                    <select name="active" id="active" class="form-control">
                        <option value="1" <?php echo $device->active == 1 ? 'selected' : ''; ?>>Ativo</option>
                        <option value="0" <?php echo $device->active == 0 ? 'selected' : ''; ?>>Inativo</option>
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