
<?php $this->start(); ?>

<h4><small>TESTE MT4</small></h4>
<hr>
<h2>Comparação de Hashes</h2>
<h5>Framework próprio por Felipe D. v<?php echo \Golden\Foundation\Application::VERSION; ?></h5>

<p>&nbsp;</p>

<form id="form" action="<?php echo route('hashes.store'); ?>" method="post">

    <div class="row">
        <div class="col-md-8">

            <div class="row">
                <div class="col-md-4">
                    <label for="text">Texto*</label>
                    <input type="text" name="text" id="text" value="" class="form-control" />
                </div>
                <div class="col-md-4">
                    <label for="hash">Hash (p/ comparação c/ SALT fixos) (opcional)</label>
                    <input type="text" name="hash" id="hash" value="" class="form-control" />
                </div>
            </div>

            <div class="row">&nbsp;</div>

            <figure class="highlight">
                    <pre>
                        <code class="language-html" id="output" data-lang="html" style="float: left; padding-top: 10px;"><?php if(!isset($output)) { ?>Digite um texto acima e clique no botão abaixo...<?php } else { echo $output; } ?></code>
                    </pre>
            </figure>

            <div class="row">&nbsp;</div>

            <div class="row">
                <div class="col-md-6">
                    <button type="button" class="btn btn-success" id="bt-action">Hash All</button>
                </div>
            </div>

        </div>
    </div>
</form>

<p>&nbsp;</p>

<span id="status"></span>

<script src="<?php echo asset('assets/app/hash.js'); ?>"></script>

<?php $this->end('content'); ?>

<?php $this->extend('layout.master'); ?>