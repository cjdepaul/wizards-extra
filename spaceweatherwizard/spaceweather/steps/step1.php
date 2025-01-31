<input type="hidden" id="selectedhostconfig" name="selectedhostconfig" value="<?= encode_form_val($selectedhostconfig) ?>" />
<input type="hidden" id="config_serial" name="config_serial" value="<?= (!empty($config)) ? base64_encode(json_encode($config)) : "" ?>" />

<div class="container m-0 g-0">
<?php
    #include_once __DIR__.'/../../../utils-xi2024-wizards.inc.php';
?>
    <!--                         -->
    <!-- The configuration form. -->
    <!--                         -->
    <div id="configForm">
        <h2 class="mb-2"><?= _('Host Name') ?></h2>

        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="ip_address" class="form-label form-item-required"><?= _('Local Host Address:') ?> <?= xi6_info_tooltip(_('Local host that will be running this plugin')) ?></label>
                <div class="input-group position-relative">
                    <input type="text" name="ip_address" id="ip_address" value="<?= encode_form_val($address) ?>" class="form-control monitor rounded" placeholder="<?= _("Enter Printer Address:") ?>" disabled="on">
                </div>
            </div>
        </div>

    </div> <!-- config -->
</div> <!-- container -->

<script type="text/javascript" src="<?= get_base_url() ?>includes/js/wizards-bs5.js?<?= get_build_id(); ?>"></script>
