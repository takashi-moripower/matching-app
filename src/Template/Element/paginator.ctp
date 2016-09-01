<div class="paginator text-center">
	<?= $this->Paginator->numbers( ['prev'=>true , 'next'=>true] ) ?>
    <p><?= $this->Paginator->counter() ?></p>
</div>
