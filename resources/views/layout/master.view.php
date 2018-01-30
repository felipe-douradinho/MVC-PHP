<?php print $this->attach('layout.header'); ?>

<div class="container-fluid">
    <div class="row content">
        <div class="col-md-2 col-sm-3 sidenav">
            <h4>Teste MT4</h4>
            <ul class="nav nav-pills nav-stacked">
                <li <?=\Golden\Http\Request::get('uri') == '/devices' ? 'class="active"' : ''?>>
                    <a href="<?php echo route('devices.index'); ?>">​Controle​ ​de​ ​Dispositivos</a>
                </li>
                <li <?=\Golden\Http\Request::get('uri') == '/ssh-integration' ? 'class="active"' : ''?>>
                    <a href="<?php echo route('ssh_integration.index'); ?>">​Integração​ ​SSH</a>
                </li>
                <li <?=\Golden\Http\Request::get('uri') == '/cryptography' ? 'class="active"' : ''?>>
                    <a href="<?php echo route('cryptography.index'); ?>">​Criptografia</a>
                </li>
                <li <?=\Golden\Http\Request::get('uri') == '/hashes' ? 'class="active"' : ''?>>
                    <a href="<?php echo route('hashes.index'); ?>">​Comparação​ ​de​ ​Hashes</a>
                </li>
            </ul>
        </div>

        <div class="col-md-10 col-sm-9">

            <?php if(isset($errors)) { ?>
                <div class="row">&nbsp;</div>
                <div class="alert alert-danger" role="alert">
                    <?php foreach ($errors as $error) { echo $error; } ?>
                </div>
            <?php } ?>

	        <?php if(isset($infos)) { ?>
                <div class="row">&nbsp;</div>
                <div class="alert alert-success" role="alert">
			        <?php foreach ($infos as $info) { echo $info; } ?>
                </div>
	        <?php } ?>

	        <?php echo $this->block('content'); ?>

        </div>
    </div>
</div>

<?php print $this->attach('layout.footer'); ?>