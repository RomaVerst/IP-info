<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<form method="post" data-get-ip-info>
    <?= bitrix_sessid_post() ?>
    <div class="form-group mb-3">
        <label for="inputIp"><?= Loc::getMessage('GEOIP_DEF_CMP_LABEL_IP') ?></label>
        <input type="text" class="form-control" name="inputIp" id="inputIp" >
    </div>

    <button type="submit"
            class="btn btn-primary"><?= Loc::getMessage('GEOIP_DEF_CMP_SUBMIT_TEXT') ?></button>

</form>
<div class="mt-3" data-result-info></div>
<script>
    let geoIpObj =  new GeoIpJSObject({
        signedParameters: '<?= $this->getComponent()->getSignedParameters() ?>',
        componentName: '<?= $this->getComponent()->getName() ?>',
    });
</script>
