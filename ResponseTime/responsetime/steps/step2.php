    <!--                                   -->
    <!-- The initial data set from Step 1. -->
    <!--                                   -->
    <input type="hidden" id="hostname" name="hostname" value="<?= encode_form_val($hostname) ?>">
    <input type="hidden" id="selectedhostconfig" name="selectedhostconfig" value="<?= encode_form_val($selectedhostconfig) ?>">
    <input type="hidden" id="services_serial" name="services_serial" value="<?= (!empty($services)) ? base64_encode(json_encode($services)) : "" ?>" />
    <input type="hidden" id="serviceargs_serial" name="serviceargs_serial" value="<?= (!empty($serviceargs)) ? base64_encode(json_encode($serviceargs)) : "" ?>" />
    <input type="hidden" id="config_serial" name="config_serial" value="<?= (!empty($config)) ? base64_encode(json_encode($config)) : "" ?>" />

    <input type="hidden" name="ip_address" value="<?= encode_form_val($address) ?>">
<?php
    #include_once __DIR__.'/../../../utils-xi2024-wizards.inc.php';
?>
    <div class="container m-0 g-0">
        <h2 class="mb-2"><?= _('Response Time') ?></h2>

        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="ip_address" class="form-label"><?= _('Address') ?> </label>
                <div class="input-group position-relative">
                    <input type="text" name="ip_address" id="ip_address" value="<?= encode_form_val($address) ?>" class="form-control form-control-sm monitor rounded" placeholder="<?= _("Enter IP Address or URL") ?>" disabled="on">
                    <i id="ip_address_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="hostname" class="form-label"><?= _('Host Name') ?> <?= xi6_info_tooltip(_('The name you would like to have associated with this wizard')) ?></label>
                <div class="input-group position-relative">
                    <input type="text" name="hostname" id="hostname" value="<?= encode_form_val($hostname) ?>" class="form-control form-control-sm monitor rounded" placeholder="<?= _("Enter Host Name") ?>" >
                    <div class="invalid-feedback">
                        <?= _("Please enter the Host Name") ?>
                    </div>
                    <i id="hostname_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                </div>
            </div>
        </div>

         <!-- Warning Threshold -->
        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="warns" class="form-label"><?= _('Warning Threshold') ?></label>
                <div class="input-group position-relative">
                    <input type="text" name="warns" id="warns" value="<?= encode_form_val($warns) ?>" class="form-control form-control-sm monitor rounded" placeholder="<?= _("Enter warning threshold (e.g., 100ms)") ?>" />
                    <div class="invalid-feedback">
                        <?= _("Please enter a valid warning threshold.") ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Critical Threshold -->
        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="crits" class="form-label"><?= _('Critical Threshold') ?></label>
                <div class="input-group position-relative">
                    <input type="text" name="crits" id="crits" value="<?= encode_form_val($crits) ?>" class="form-control form-control-sm monitor rounded" placeholder="<?= _("Enter critical threshold (e.g., 200ms)") ?>" />
                    <div class="invalid-feedback">
                        <?= _("Please enter a valid critical threshold.") ?>
                    </div>
                </div>
            </div>
        </div>

        

    </div> <!-- container -->

    <script type="text/javascript" src="<?= get_base_url() ?>includes/js/wizards-bs5.js?<?= get_build_id(); ?>"></script>
