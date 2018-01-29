
<?php $this->start(); ?>

<h4><small>TESTE MT4</small></h4>
<hr>
<h2>Hashes</h2>
<h5><span class="glyphicon glyphicon-time"></span> Post by Jane Dane, Sep 27, 2015.</h5>
<p>Texto texto</p>
<br><br>

<h4>Texto</h4>

<form role="form">
	<div class="form-group">
		<textarea class="form-control" rows="3" required></textarea>
	</div>
	<button type="submit" class="btn btn-success">Submit</button>
</form>

<?php $this->end('content'); ?>

<?php $this->extend('layout.master'); ?>