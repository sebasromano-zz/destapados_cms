<?php
class NewslettersItem extends NewsterAppModel{
	
	var $name = 'NewslettersItem';
	var $useTable = 'newsletters_items';
    var $belongsTo = array('Newster.Newsletter');

}