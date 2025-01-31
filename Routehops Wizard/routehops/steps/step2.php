    <!--                                   -->
    <!-- The initial data set from Step 1. -->
    <!--                                   -->
    <input type="hidden" id="services_serial" name="services_serial" value="<?= (!empty($services)) ? base64_encode(json_encode($services)) : "" ?>" />
    <input type="hidden" id="serviceargs_serial" name="serviceargs_serial" value="<?= (!empty($serviceargs)) ? base64_encode(json_encode($serviceargs)) : "" ?>" />
    <input type="hidden" id="config_serial" name="config_serial" value="<?= (!empty($config)) ? base64_encode(json_encode($config)) : "" ?>" />
    
    <input type="hidden" name="address" value="<?= encode_form_val($address) ?>">
<?php
    #include_once __DIR__.'/../../../utils-xi2024-wizards.inc.php';
?>
    <div class="container m-0 g-0">
        <h2 class="mb-2"><?= _('Set Thresholds and Arguments') ?></h2>

        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="address" class="form-label"><?= _('Address') ?> </label>
                <div class="input-group position-relative">
                    <input type="text" name="address" id="address" value="<?= encode_form_val($address) ?>" class="form-control form-control-sm rounded" placeholder="<?= _("Enter address") ?>" disabled="on">
                    <i id="address_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="warning" class="form-label"><?= _('Warning') ?> </label>
                <div class="input-group input-group-sm position-relative">
                    <input type="text" name="warning" id="warning" value="<?= encode_form_val($warning) ?>" class="form-control form-control-sm monitor rounded" placeholder="<?= _("Enter warning hop count") ?>">
                    <i id="warning_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="critical" class="form-label"><?= _('Critical') ?> </label>
                <div class="input-group input-group-sm position-relative">
                    <input type="text" name="critical" id="critical" value="<?= encode_form_val($critical) ?>" class="form-control form-control-sm monitor rounded" placeholder="<?= _("Enter critical hop count") ?>" >
                    <i id="critical_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="protocol" class="form-label">
                    <?= _('Default Protocol') ?> 
                    <?= xi6_info_tooltip(_("UDP, TCP, and ICMP are the protocols that can be used to trace the route to the host. If the default fails, the others will also be attempted.")) ?>
                </label>
                <div class="input-group input-group-sm position-relative">
                    <input type="text" name="protocol" id="protocol" value="<?= encode_form_val($protocol) ?>" class="form-control form-control-sm monitor rounded" placeholder="<?= _("Enter first protocol to use") ?>" >
                    <i id="protocol_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="verbosity" class="form-label"><?= _('Verbosity') ?> 
                    <?= xi6_info_tooltip(_("The amount of output for the status message. Options: 0, 1, 2")) ?>
                </label>
                <div class="input-group input-group-sm position-relative">
                    <input type="text" name="verbosity" id="verbosity" value="<?= encode_form_val($verbosity) ?>" class="form-control form-control-sm monitor rounded" placeholder="<?= _("Enter output verbosity") ?>" >
                    <i id="verbosity_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                </div>
            </div>
        </div>

    </div> <!-- container -->

    <script type="text/javascript" src="<?= get_base_url() ?>includes/js/wizards-bs5.js?<?= get_build_id(); ?>"></script>
