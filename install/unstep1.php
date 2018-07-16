<?use \Bitrix\Main\Localization\Loc;?>
<form action="<?=$APPLICATION->GetCurPage();?>">
    <?=bitrix_sessid_post();?>
    <input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
    <input type="hidden" name="id" value="askron.unicon">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <input type="submit" name="" value="<?=Loc::getMessage('ASKRON_UNICON_DELETE');?>">
</form>