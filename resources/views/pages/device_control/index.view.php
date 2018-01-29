
<?php $this->start(); ?>

<h4><small>TESTE MT4</small></h4>
<hr>
<h2>Controle de Dispositivos - Listagem</h2>
<h5>Framework próprio por Felipe D. v<?php echo \Golden\Foundation\Application::VERSION; ?></h5>

<p>&nbsp;</p>
<h4><a href="<?php echo route('devices.create'); ?>" class="btn btn-success">Cadastrar</a></h4>
<p>&nbsp;</p>

<table class="table table-striped table-condensed table-bordered table-rounded">
    <thead>
        <tr>
            <th>ID</th>
            <th>Hostname</th>
            <th>Endereço IP</th>
            <th>Tipo</th>
            <th>Fabricante</th>
            <th>Modelo</th>
            <th>Ativo</th>
            <th width="12%">Criação</th>
            <th width="12%">Ação</th>
        </tr>
    </thead>
    <tbody>

        <?php if(!$devices || $devices->getItems()->isEmpty()) { ?>

            <tr>
                <th colspan="9" width="5%">
                    <div style="text-align: center; padding: 10px;">
                        Nenhum registro encontrado
                    </div>
                </th>
            </tr>

        <?php } else { ?>

            <?php foreach ($devices->getItems() as $device) { ?>
                <tr>
                    <td><?php echo $device['id']; ?></td>
                    <td><?php echo $device['hostname']; ?></td>
                    <td><?php echo $device['ip_address']; ?></td>
                    <td><?php echo $device['type']; ?></td>
                    <td><?php echo $device['manufacturer']; ?></td>
                    <td><?php echo $device['model']; ?></td>
                    <td><?php echo $device['active']; ?></td>
                    <td><?php echo $device['created_at']; ?></td>
                    <td>
                        <a href="<?php echo route('devices.edit', $device['id']); ?>">Editar</a> |
                        <a href="<?php echo route('devices.destroy_get', $device['id']); ?>">Deletar</a>
                    </td>
                </tr>
            <?php } ?>

        <?php } ?>

    </tbody>
</table>

<?php echo isset($devices) ? $devices->getPaginator() : ''; ?>


<?php $this->end('content'); ?>

<?php $this->extend('layout.master'); ?>