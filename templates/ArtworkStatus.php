<?
$artwork = $artwork ?? null;
?>
<? if($artwork !== null){ ?>
<? if($artwork->Status == ArtworkStatus::Approved){ ?>Approved<? } ?>
<? if($artwork->Status == ArtworkStatus::Declined){ ?>Declined<? } ?>
<? if($artwork->Status == ArtworkStatus::Unverified){ ?>Unverified<? } ?>
<? if($artwork->EbookUrl !== null){ ?> — in use<? if($artwork->EbookUrl !== null){ ?> by <? if($artwork->Ebook !== null && $artwork->Ebook->Url !== null){ ?><i><a href="<?= $artwork->Ebook->Url ?>"><?= Formatter::EscapeHtml($artwork->Ebook->Title) ?></a></i><? }else{ ?><code><?= Formatter::EscapeHtml($artwork->EbookUrl) ?></code> (unreleased)<? } ?><? } ?><? } ?>
<? } ?>
