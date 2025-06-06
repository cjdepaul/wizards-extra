    <input type="hidden" id="services_serial" name="services_serial" value="<?= (!empty($services)) ? base64_encode(json_encode($services)) : "" ?>" />
    <input type="hidden" id="serviceargs_serial" name="serviceargs_serial" value="<?= (!empty($serviceargs)) ? base64_encode(json_encode($serviceargs)) : "" ?>" />
    <input type="hidden" id="config_serial" name="config_serial" value="<?= (!empty($config)) ? base64_encode(json_encode($config)) : "" ?>" />
    <input type="hidden" id="selectedhostconfig" name="selectedhostconfig" value="<?= encode_form_val($selectedhostconfig) ?>" />
    
<?php?>
    <div class="container m-0 g-0">
        <div id="configForm">
            <h2 class="mb-2"><?= _('Host Information') ?></h2>

            <div class="row mb-2">
                <div class="col-sm-6">
                    <label for="address" class="form-label form-item-required"><?= _('Address') ?> <?= xi6_info_tooltip(_('Add the domain or IP that will be monitored here')) ?> </label>
                    <div class="input-group position-relative">
                        <input type="text" name="address" id="address" value="<?= encode_form_val($address) ?>" 
                            class="form-control monitor rounded" placeholder="<?= _("Enter Host Address") ?>" required>
                        <i id="address_Alert" class="visually-hidden position-absolute top-0 start-100 
                        translate-middle icon icon-circle color-ok icon-size-status">
                        </i>
                    </div>
                </div>
            </div>

        </div> <!-- config -->
    </div> <!-- container -->

    <script type="text/javascript" src="<?= get_base_url() ?>includes/js/wizards-bs5.js?<?= get_build_id(); ?>"></script>


