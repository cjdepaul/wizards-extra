    <!--                                   -->
    <!-- The initial data set from Step 1. -->
    <!--                                   -->
    <input type="hidden" id="hostname" name="hostname" value="<?= encode_form_val($hostname) ?>">
    <input type="hidden" id="service_name" name="service_name" value="<?= encode_form_val($service_name) ?>">
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
        <h2 class="mb-2"><?= _('Add desired event handler to NCPA host') ?></h2>
        <p><?= _('For a Windows NCPA Host, add the desired event handler to \'C:\Program Files\Nagios\NCPA\plugins\'') ?></p>
        <p><?= _('For a Linux NCPA Host, add the desired event handler to \'/usr/local/ncpa/plugins/\'') ?></p>
        <p><?= _('To install NCPA on a remote host, visit https://www.nagios.org/ncpa/') ?></p>


        <!--                         -->
        <!-- The metrics to monitor. -->
        <!--                         -->
        <h2 class="mt-4"><?= _('NCPA Metrics') ?></h2>
        <p><?= _('Provide the IP address of the NCPA host, the event handler to execute, the host\'s token, command line arguments for the event handler, and the NCPA port.') ?></p>
        
        
        <div class="row mb-2">
            <div class="col-sm-5">
                <label for="ip_address" class="form-label form-item-required"><?= _('NCPA Host Address') ?> <?= xi6_info_tooltip(_('The IP address of the NCPA host machine')) ?></label>
                <div class="input-group position-relative">
                    <input type="text" name="ip_address" id="ip_address" value="<?= encode_form_val($address) ?>" class="form-control form-control-sm monitor rounded" placeholder="<?= _("Enter Address") ?>">
                    <div class="invalid-feedback">
                        Please enter the Address
                    </div>
                    <i id="ip_address_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <label for="event_handler" class="form-label form-item-required"><?= _('Event Handler') ?> <?= xi6_info_tooltip(_('The name of the event handler contained in your plugins folder. Ex. "eventhandler.sh"')) ?></label>
                <div class="input-group position-relative">
                    <div class="input-group input-group-sm">
                        <input type="text" id="event_handler" name="event_handler" value="<?= encode_form_val($event_handler) ?>" placeholder="<?= _("Enter event handler") ?>" class="form-control form-control-sm monitor">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <label for="token" class="form-label form-item-required"><?= _('Token') ?> <?= xi6_info_tooltip(_('Enter the NCPA host\'s token.')) ?></label>
                <div class="input-group">
                    <input type="password" name="token" id="token" value="<?= encode_form_val($token) ?>" class="usermacro-detection form-control rounded-start usermacro-detection" autocomplete="off" placeholder="<?= _("Enter Token") ?>">
                    <button type="button" class="btn btn-outline-secondary btn-show-secret rounded-end tt-bind" id="password-secret" title="<?= _("Show") ?>">
                        <span class="material-symbols-outlined md-22 md-pointer">Visibility</span>
                        <i id="token_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <label for="arguments" class="form-label"><?= _('Arguments') ?> <?= xi6_info_tooltip(_('Enter any arguments for to be run by your event handler. Ex. -servicename Service -a restart')) ?></label>
                <div class="input-group input-group-sm">
                    <input type="text" id="arguments" name="arguments" value="<?= encode_form_val($arguments) ?>" class="form-control form-control-sm monitor rounded-end" placeholder="<?= _("Enter arguments") ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <label for="port" class="form-label form-item-required"><?= _('Port') ?> <?= xi6_info_tooltip(_('Enter the NCPA host\'s port.')) ?></label>
                <div class="input-group input-group-sm">
                    <input type="text" id="port" name="port" value="<?= encode_form_val($port) ?>" class="form-control form-control-sm monitor rounded-end">
                </div>
            </div>
        </div>
    <script type="text/javascript" src="<?= get_base_url() ?>includes/js/wizards-bs5.js?<?= get_build_id(); ?>"></script>
