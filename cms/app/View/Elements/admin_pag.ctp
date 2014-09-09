	<div class="clearfix">

		<?php if(!isset($no_show_records)): ?>
		<div class="pagination-counters pull-left">
			<?php if ( count( $items ) > 0 ) : ?>
			<p><?php echo $this->Paginator->counter(__("Page <strong>{:page}</strong> / <strong>{:pages}</strong> - Showing <strong>{:current}</strong> / <strong>{:count}</strong>", true)); ?></p>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if ( $this->Paginator->hasPrev() || $this->Paginator->hasNext() ): ?>
		<div class="pull-right">
			<div class="pagination clearfix">
				<ul>
				<?php
					$this->Paginator->options(array('update' => '#items', 'evalScripts' => true, 'url' => $this->passedArgs));
					echo $this->Paginator->prev('«', array('tag' => 'li', 'rel' => 'items', 'class' => ($this->Paginator->hasPrev() ? 'prev' : 'prev disabled')));
					echo $this->Paginator->numbers( array('separator' => null, 'tag' => 'li', 'rel' => 'items', 'currentClass' => 'active') );
					echo $this->Paginator->next('»', array('tag' => 'li', 'rel' => 'items', 'class' => ($this->Paginator->hasNext() ? 'next' : 'next disabled' )));
				?>
				</ul>
			</div>
		</div>
		<?php endif; ?>

	</div>
