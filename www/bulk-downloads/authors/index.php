<?
require_once('Core.php');

use function Safe\apcu_fetch;

$forbiddenException = null;

if(isset($_SERVER['PHP_AUTH_USER'])){
	// We get here if the user entered an invalid HTTP Basic Auth username,
	// and this page was served as the 401 page.
	$forbiddenException = new Exceptions\InvalidPatronException();
}

$authors = [];

try{
	$authors = apcu_fetch('bulk-downloads-authors');
}
catch(Safe\Exceptions\ApcuException $ex){
	$result = Library::RebuildBulkDownloadsCache();
	$authors = $result['authors'];
}

?><?= Template::Header(['title' => 'Downloads by Author', 'highlight' => '', 'description' => 'Download zip files containing all of the Standard Ebooks by a given author.']) ?>
<main>
	<section class="bulk-downloads">
		<h1>Downloads by Author</h1>
		<? if($forbiddenException !== null){ ?>
		<?= Template::Error(['exception' => $forbiddenException]) ?>
		<? } ?>
		<p><a href="/about#patrons-circle">Patrons circle members</a> can download zip files containing all of the ebooks that were released in a given month of Standard Ebooks history. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.</p>
		<p>These zip files contain each ebook in every format we offer, and are updated once daily with the latest versions of each ebook.</p>
		<p>If you’re a Patrons Circle member, when prompted enter your email address and leave the password field blank to download these files.</p>
		<?= Template::BulkDownloadTable(['label' => 'Author', 'collections' => $authors]); ?>
	</section>
</main>
<?= Template::Footer() ?>