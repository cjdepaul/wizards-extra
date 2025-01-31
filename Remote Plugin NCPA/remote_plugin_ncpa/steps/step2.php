    <!--                                   -->
    <!-- The initial data set from Step 1. -->
    <!--                                   -->
    <input type="hidden" id="hostname" name="hostname" value="<?= encode_form_val($hostname) ?>">
    <input type="hidden" id="operation" name="operation" value="<?= encode_form_val($operation) ?>">
    <input type="hidden" id="selectedhostconfig" name="selectedhostconfig" value="<?= encode_form_val($selectedhostconfig) ?>">
    <input type="hidden" id="services_serial" name="services_serial" value="<?= (!empty($services)) ? base64_encode(json_encode($services)) : "" ?>" />
    <input type="hidden" id="serviceargs_serial" name="serviceargs_serial" value="<?= (!empty($serviceargs)) ? base64_encode(json_encode($serviceargs)) : "" ?>" />
    <input type="hidden" id="config_serial" name="config_serial" value="<?= (!empty($config)) ? base64_encode(json_encode($config)) : "" ?>" />

    <input type="hidden" name="ip_address" value="<?= encode_form_val($address) ?>">
<?php
    #include_once __DIR__.'/../../../utils-xi2024-wizards.inc.php';
?>
    <div class="container m-0 g-0">
        <h2 class="mb-2"><?= _('Remote Plugin Host') ?></h2>

        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="ip_address" class="form-label"><?= _('Address') ?> </label>
                <div class="input-group position-relative">
                    <input type="text" name="ip_address" id="ip_address" value="<?= encode_form_val($address) ?>" class="form-control form-control-sm monitor rounded" placeholder="<?= _("Enter Address") ?>" disabled="on">
                    <i id="ip_address_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="hostname" class="form-label"><?= _('Host Name') ?> <?= xi6_info_tooltip(_('The name you would like to have associated with this plugin')) ?></label>
                <div class="input-group position-relative">
                    <input type="text" name="hostname" id="hostname" value="<?= encode_form_val($hostname) ?>" class="form-control form-control-sm monitor rounded" placeholder="<?= _("Enter Host Name") ?>" >
                    <div class="invalid-feedback">
                        <?= _("Please enter the ".$labelText) ?>
                    </div>
                    <i id="hostname_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                </div>
            </div>
        </div>

        <!--                         -->
        <!-- The metrics to monitor. -->
        <!--                         -->
        <h2 class="mt-4"><?= _('Remote Plugin Metrics') ?></h2>
        <p><?= _('Choose the plugin to execute, token, command-line arguments, and port') ?></p>

        <div class="row">
            <div class="col-sm-8">
                <fieldset class="row g-2 mb-1 wz-fieldset">
                    <div class="col-sm-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">
                                <span class="form-label m-0 ms-1 align-middle"><?= _('Plugin') ?></span>
                            </span>
                            <input type="text" id="plugin" name="plugin" value="<?= encode_form_val($plugin) ?>" placeholder="<?= _("Enter Plugin") ?>" class="form-control form-control-sm monitor">
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-sm-8">
                <fieldset class="row g-2 mb-1 wz-fieldset">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <span class="form-label m-0 ms-1 align-middle"><?= _('Token') ?></span>
                            </span>
                            <input type="password" name="token" id="token" value="<?= encode_form_val($token) ?>" class="usermacro-detection form-control rounded-start usermacro-detection" autocomplete="off" placeholder="<?= _("Enter Token") ?>">
                            <button type="button" class="btn btn-outline-secondary btn-show-secret rounded-end tt-bind" id="password-secret" title="<?= _("Show") ?>">
                                <span class="material-symbols-outlined md-22 md-pointer">Visibility</span>
                                <i id="token_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                            </button>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="row g-2 mb-1 wz-fieldset">
                    <div class="col-sm-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">
                                <span class="form-label m-0 ms-1 align-middle"><?= _('Arguments') ?></span>
                            </span>
                            <input type="text" id="arguments" name="arguments" value="<?= encode_form_val($arguments) ?>" class="form-control form-control-sm monitor rounded-end" placeholder="<?= _("Ex. -w 80 -c 90") ?>">
                        </div>
                    </div>
                </fieldset>
                <fieldset class="row g-2 mb-1 wz-fieldset">
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">
                                    <span class="form-label m-0 ms-1 align-middle"><?= _('Port') ?></span>
                                </span>
                                <input type="text" id="port" name="port" value="<?= encode_form_val($port) ?>" placeholder="<?= _("Enter Port") ?>" class="form-control form-control-sm monitor rounded-end">
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div> <!-- container -->

    <script type="text/javascript" src="<?= get_base_url() ?>includes/js/wizards-bs5.js?<?= get_build_id(); ?>"></script>
