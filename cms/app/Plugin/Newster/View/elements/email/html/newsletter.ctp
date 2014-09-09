
			<?php $i=1; foreach ($items as $item): ?>

				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<?php if($i>1): ?>
				<tr>
					<td height="2" style="background-color: #EBE7DA; background-image: url(<?php echo Router::url('/newster/img/separator.png', true) ?>)"></td>
				</tr>
				<?php endif; ?>
				<tr>
					<td style="vertical-align:top;">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" >
						<tr>
							<td style="padding: 20px; vertical-align:top;">
								<?php  //Router::url('/news/view/'.$item['permalink'], true) ?>
								<h2 style="font-family: Georgia, Times, serif; font-weight: normal; padding: 0; font-size: 17px; line-height: 100%; color: #BE5C2D; margin: 0 0 10px;"><?php echo $item['title_'.$lng]; //$html->link($item['title_'.$lng], '#', array('target' => '_blank', 'style' => 'padding: 0; text-decoration: none; color: #BE5C2D !important; margin: 0;')) ?></h2>
								<p style="font-family: Georgia, Times, serif; padding: 0; font-size: 12px; line-height: 160%; margin: 0;"><?php echo strip_tags($item['content_'.$lng]) //$text->truncate(strip_tags($item['content_'.$lng]), 255) ?></p>
								<?php //echo $html->link('more >', Router::url('/news/view/'.$item['permalink'], true), array('style' => 'font-size: 12px; padding: 0; text-decoration: none; color: #BE5C2D !important; margin: 0;', 'target' => '_blank')) ?>
							</td>
						</tr>
						<?php if(!empty($item['Newsimage'][0])): ?>
						<tr>
							<td style="padding: 0 20px 20px 20px; vertical-align:top;">
								<?php echo $html->image(Router::url('/files/newsimage/full_'.$item['Newsimage'][0]['image'], true), array('style' => 'vertical-align: top; padding: 0; margin: 0; border: 1px solid #fff;')); ?>							
							</td>
						</tr>
						<?php endif ?>						
						</table>
					</td>	
				</tr>
				</table>

			<?php $i++; endforeach; ?>
