<?php

$page->layout = 'admin';
$page->template = 'admin/base';

if (! User::require_admin ()) {
	header ('Location: /admin');
	exit;
}

$f = new Form ('post', 'blocks/add');
if ($f->submit ()) {
	$b = new Block ($_POST);
	$b->put ();
	Versions::add ($b);
	if (! $b->error) {
		$page->title = i18n_get ('Block Added');
		echo '<p><a href="/blocks/admin">' . i18n_get ('Continue') . '</a></p>';
		$_POST['id'] = $_POST['id'];
		$this->hook ('blocks/add', $_POST);
		return;
	}
	$page->title = 'An Error Occurred';
	echo 'Error Message: ' . $wp->error;
} else {
	$b = new Block;
	$b->access = 'public';
	$b->yes_no = array ('yes', 'no');

	$b->failed = $f->failed;
	$b = $f->merge_values ($b);
	$page->title = 'Add Block';
	$page->head = $tpl->render ('blocks/add/head', $b)
				. $tpl->render ('admin/wysiwyg');
	echo $tpl->render ('blocks/add', $b);
}

?>