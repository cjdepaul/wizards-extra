<!--                                   -->
    <!-- The initial data set from Step 1. -->
    <!--                                   -->
    <input type="hidden" id="hostname" name="hostname" value="<?= encode_form_val($hostname) ?>">
    <input type="hidden" id="operation" name="operation" value="<?= encode_form_val($operation) ?>">
    <input type="hidden" id="selectedhostconfig" name="selectedhostconfig" value="<?= encode_form_val($selectedhostconfig) ?>">
    <input type="hidden" id="config_serial" name="config_serial" value="<?= (!empty($config)) ? base64_encode(json_encode($config)) : "" ?>" />
    <input type="hidden" name="ip_address" value="<?= encode_form_val($address) ?>">
    <input type="hidden" name="services_serial" value="<?= encode_form_val($services_serial) ?>">
    <input type="hidden" name="serviceargs_serial" value="<?= encode_form_val($serviceargs_serial) ?>">
    <input type="hidden" name="aurora_serial" value="<?= encode_form_val($aurora_serial) ?>">
<?php
    #include_once __DIR__.'/../../../utils-xi2024-wizards.inc.php';
?>
    <div class="container m-0 g-0">
        <h2 class="mb-2"><?= _('Host Details') ?></h2>

        <div class="row mb-2">
            <div class="col-sm-6">
                <label for="hostname" class="form-label form-item-required"><?= _('Host Name:') ?> <?= xi6_info_tooltip(_('Name you would like to associate with this host')) ?></label>
                <div class="input-group position-relative">
                    <input type="text" name="hostname" id="hostname" value="<?= encode_form_val($hostname) ?>" class="form-control form-control-sm monitor rounded" placeholder="<?= _("Enter Host Name:") ?>" >
                    <div class="invalid-feedback">
                        <?= _("Please enter the Host Name") ?>
                    </div>
                    <i id="hostname_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                </div>
            </div>
        </div>

        <h2 class="mt-4"><?= _('Space Weather Metrics') ?></h2>
        <p><?= _('Specify which space weather metrics you would like to monitor') ?></p>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="select_all_metrics" class="form-check-input me-2" onclick="selectAllInSection('metrics', this.checked)">
                        <label for="select_all_metrics" class="form-check-label bold me-2 text-nowrap"><?= _('Select All Metrics') ?></label>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center metrics">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="solarwindspeed" class="form-check-input me-2" name="services[windspeed]" <?= isset($services["windspeed"]) && $services["windspeed"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('metrics')">
                        <label for="solarwindspeed" class="form-check-label bold me-2 text-nowrap"><?= _('Solar Wind Speed') ?> <?= xi6_info_tooltip(_("Monitors the current solar wind speed")) ?></label>
                    </div>
                    <div class="col-sm-6 offset-sm-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Warning Threshold (default=400)')) ?> class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                                    </span>
                                    <input type="text" name="serviceargs[windspeed][warning]" id="windspeed_warning" value="<?= encode_form_val($serviceargs["windspeed"]["warning"] ?? 400) ?>" class="form-control form-control-sm">
                                    <i id="services_windspeed_warning_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Critical Threshold (default=700)')) ?> class="material-symbols-outlined md-critical md-18 md-400">error</i>
                                    </span>
                                    <input type="text" name="serviceargs[windspeed][critical]" id="windspeed_critical" value="<?= encode_form_val($serviceargs["windspeed"]["critical"] ?? 700) ?>" class="form-control form-control-sm">
                                    <i id="services_windspeed_critical_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center metrics">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="solardensity" class="form-check-input me-2" name="services[density]" <?= isset($services["density"]) && $services["density"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('metrics')">
                        <label for="solardensity" class="form-check-label bold me-2 text-nowrap"><?= _('Solar Wind Density') ?> <?= xi6_info_tooltip(_("Monitors the current solar wind density")) ?></label>
                    </div>
                    <div class="col-sm-6 offset-sm-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Warning Threshold (default=20)')) ?> class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                                    </span>
                                    <input type="text" name="serviceargs[density][warning]" id="density_warning" value="<?= encode_form_val($serviceargs["density"]["warning"] ?? 20) ?>" class="form-control form-control-sm">
                                    <i id="services_density_warning_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Critical Threshold (default=50)')) ?> class="material-symbols-outlined md-critical md-18 md-400">error</i>
                                    </span>
                                    <input type="text" name="serviceargs[density][critical]" id="density_critical" value="<?= encode_form_val($serviceargs["density"]["critical"] ?? 50) ?>" class="form-control form-control-sm">
                                    <i id="services_density_critical_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center metrics">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="bt" class="form-check-input me-2" name="services[bt]" <?= isset($services["bt"]) && $services["bt"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('metrics')">
                        <label for="bt" class="form-check-label bold me-2 text-nowrap"><?= _('Bt') ?> <?= xi6_info_tooltip(_("Monitors the current Interplanetary Magnetic Field strength")) ?></label>
                    </div>
                    <div class="col-sm-6 offset-sm-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Warning Threshold (default=10)')) ?> class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                                    </span>
                                    <input type="text" name="serviceargs[bt][warning]" id="bt_warning" value="<?= encode_form_val($serviceargs["bt"]["warning"] ?? 10) ?>" class="form-control form-control-sm">
                                    <i id="services_bt_warning_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Critical Threshold (default=20)')) ?> class="material-symbols-outlined md-critical md-18 md-400">error</i>
                                    </span>
                                    <input type="text" name="serviceargs[bt][critical]" id="bt_critical" value="<?= encode_form_val($serviceargs["bt"]["critical"] ?? 20) ?>" class="form-control form-control-sm">
                                    <i id="services_bt_critical_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center metrics">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="bz" class="form-check-input me-2" name="services[bz]" <?= isset($services["bz"]) && $services["bz"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('metrics')">
                        <label for="bz" class="form-check-label bold me-2 text-nowrap"><?= _('Bz') ?> <?= xi6_info_tooltip(_("Monitors the north-south component of the interplanetary magnetic field")) ?></label>
                    </div>
                    <div class="col-sm-6 offset-sm-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Warning Threshold (default=-5)')) ?> class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                                    </span>
                                    <input type="text" name="serviceargs[bz][warning]" id="bz_warning" value="<?= encode_form_val($serviceargs["bz"]["warning"] ?? -5) ?>" class="form-control form-control-sm">
                                    <i id="services_bz_warning_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Critical Threshold (default=-15)')) ?> class="material-symbols-outlined md-critical md-18 md-400">error</i>
                                    </span>
                                    <input type="text" name="serviceargs[bz][critical]" id="bz_critical" value="<?= encode_form_val($serviceargs["bz"]["critical"] ?? -15) ?>" class="form-control form-control-sm">
                                    <i id="services_bz_critical_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center metrics">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="kp" class="form-check-input me-2" name="services[kp]" <?= isset($services["kp"]) && $services["kp"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('metrics')">
                        <label for="kp" class="form-check-label bold me-2 text-nowrap"><?= _('Kp Index') ?> <?= xi6_info_tooltip(_("Monitors the current Kp Index")) ?></label>
                    </div>
                    <div class="col-sm-6 offset-sm-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Warning Threshold (default=5)')) ?> class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                                    </span>
                                    <input type="text" name="serviceargs[kp][warning]" id="kp_warning" value="<?= encode_form_val($serviceargs["kp"]["warning"] ?? 5) ?>" class="form-control form-control-sm">
                                    <i id="services_kp_warning_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Critical Threshold (default=8)')) ?> class="material-symbols-outlined md-critical md-18 md-400">error</i>
                                    </span>
                                    <input type="text" name="serviceargs[kp][critical]" id="kp_critical" value="<?= encode_form_val($serviceargs["kp"]["critical"] ?? 8) ?>" class="form-control form-control-sm">
                                    <i id="services_kp_critical_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center metrics">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="3day" class="form-check-input me-2" name="services[3day]" <?= isset($services["3day"]) && $services["3day"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('metrics')">
                        <label for="3day" class="form-check-label bold me-2 text-nowrap"><?= _('Three Day Forecast') ?> <?= xi6_info_tooltip(_("Monitors the highest projected Kp index in the next three days")) ?></label>
                    </div>
                    <div class="col-sm-6 offset-sm-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Warning Threshold (default=5)')) ?> class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                                    </span>
                                    <input type="text" name="serviceargs[3day][warning]" id="3day_warning" value="<?= encode_form_val($serviceargs["3day"]["warning"] ?? 5) ?>" class="form-control form-control-sm">
                                    <i id="services_3day_warning_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Critical Threshold (default=8)')) ?> class="material-symbols-outlined md-critical md-18 md-400">error</i>
                                    </span>
                                    <input type="text" name="serviceargs[3day][critical]" id="3day_critical" value="<?= encode_form_val($serviceargs["3day"]["critical"] ?? 8) ?>" class="form-control form-control-sm">
                                    <i id="services_3day_critical_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center metrics">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="hpin" class="form-check-input me-2" name="services[hpin]" <?= isset($services["hpin"]) && $services["hpin"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('metrics')">
                        <label for="hpin" class="form-check-label bold me-2 text-nowrap"><?= _('Hemispheric Power Index North') ?> <?= xi6_info_tooltip(_("Monitors the Hemispheric Power Index in the Northern Hemisphere")) ?></label>
                    </div>
                    <div class="col-sm-6 offset-sm-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Warning Threshold (default=50)')) ?> class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                                    </span>
                                    <input type="text" name="serviceargs[hpin][warning]" id="hpin_warning" value="<?= encode_form_val($serviceargs["hpin"]["warning"] ?? 50) ?>" class="form-control form-control-sm">
                                    <i id="services_hpin_warning_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Critical Threshold (default=100)')) ?> class="material-symbols-outlined md-critical md-18 md-400">error</i>
                                    </span>
                                    <input type="text" name="serviceargs[hpin][critical]" id="hpin_critical" value="<?= encode_form_val($serviceargs["hpin"]["critical"] ?? 100) ?>" class="form-control form-control-sm">
                                    <i id="services_hpin_critical_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center metrics">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="hpis" class="form-check-input me-2" name="services[hpis]" <?= isset($services["hpis"]) && $services["hpis"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('metrics')">
                        <label for="hpis" class="form-check-label bold me-2 text-nowrap"><?= _('Hemispheric Power Index North') ?> <?= xi6_info_tooltip(_("Monitors the Hemispheric Power Index in the Southern Hemisphere")) ?></label>
                    </div>
                    <div class="col-sm-6 offset-sm-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Warning Threshold (default=50)')) ?> class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                                    </span>
                                    <input type="text" name="serviceargs[hpis][warning]" id="hpis_warning" value="<?= encode_form_val($serviceargs["hpis"]["warning"] ?? 50) ?>" class="form-control form-control-sm">
                                    <i id="services_hpis_warning_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Critical Threshold (default=100)')) ?> class="material-symbols-outlined md-critical md-18 md-400">error</i>
                                    </span>
                                    <input type="text" name="serviceargs[hpis][critical]" id="hpis_critical" value="<?= encode_form_val($serviceargs["hpis"]["critical"] ?? 100) ?>" class="form-control form-control-sm">
                                    <i id="services_hpis_critical_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>


        <h2 class="mt-4"><?= _('Space Weather Detection Alerts') ?></h2>
        <p><?= _('Specify which detection services you would like to monitor') ?></p>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="select_all_detection" class="form-check-input me-2" onclick="selectAllInSection('detection', this.checked)">
                        <label for="select_all_detection" class="form-check-label bold me-2 text-nowrap"><?= _('Select All Detection Alerts') ?></label>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center detection">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="coronalmass" class="form-check-input me-2" name="services[coronalmass]" <?= isset($services["coronalmass"]) && $services["coronalmass"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('detection')">
                        <label for="coronalmass" class="form-check-label bold me-2 text-nowrap"><?= _('Coronal Mass Ejection') ?> <?= xi6_info_tooltip(_("Monitors for coronal mass ejectionsn\nEarthBound=critical\nGlancingBlow=warning")) ?></label>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center detection">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="solarflare" class="form-check-input me-2" name="services[solarflare]" <?= isset($services["solarflare"]) && $services["solarflare"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('detection')">
                        <label for="solarflare" class="form-check-label bold me-2 text-nowrap"><?= _('Solar Flare') ?> <?= xi6_info_tooltip(_("Monitors for solar flares\nXclass=critical\nMclass=warning")) ?></label>
                    </div>
                </fieldset>
            </div>
        </div>




        <h2 class="mt-4"><?= _('NOAA Alerts') ?></h2>
        <p><?= _('Specify which NOAA alerts you would like to monitor') ?></p>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="select_all_noaa" class="form-check-input me-2" onclick="selectAllInSection('noaa', this.checked)">
                        <label for="select_all_noaa" class="form-check-label bold me-2 text-nowrap"><?= _('Select All NOAA Alerts') ?></label>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center noaa">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="gms" class="form-check-input me-2" name="services[gms]" <?= isset($services["gms"]) && $services["gms"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('noaa')">
                        <label for="gms" class="form-check-label bold me-2 text-nowrap"><?= _('Geomagnetic Storm') ?> <?= xi6_info_tooltip(_("Monitors the current Geomagnetic Storm level")) ?></label>
                    </div>
                    <div class="col-sm-6 offset-sm-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Warning Threshold (default=1)')) ?> class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                                    </span>
                                    <input type="text" name="serviceargs[gms][warning]" id="gms_warning" value="<?= encode_form_val($serviceargs["gms"]["warning"] ?? 1) ?>" class="form-control form-control-sm">
                                    <i id="services_gms_warning_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Critical Threshold (default=4)')) ?> class="material-symbols-outlined md-critical md-18 md-400">error</i>
                                    </span>
                                    <input type="text" name="serviceargs[gms][critical]" id="gms_critical" value="<?= encode_form_val($serviceargs["gms"]["critical"] ?? 4) ?>" class="form-control form-control-sm">
                                    <i id="services_gms_critical_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center noaa">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="radio" class="form-check-input me-2" name="services[radio]" <?= isset($services["radio"]) && $services["radio"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('noaa')">
                        <label for="radio" class="form-check-label bold me-2 text-nowrap"><?= _('Radio Blackout') ?> <?= xi6_info_tooltip(_("Monitors the current Radio Blackout level")) ?></label>
                    </div>
                    <div class="col-sm-6 offset-sm-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Warning Threshold (default=1)')) ?> class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                                    </span>
                                    <input type="text" name="serviceargs[radio][warning]" id="radio_warning" value="<?= encode_form_val($serviceargs["radio"]["warning"] ?? 1) ?>" class="form-control form-control-sm">
                                    <i id="services_radio_warning_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Critical Threshold (default=4)')) ?> class="material-symbols-outlined md-critical md-18 md-400">error</i>
                                    </span>
                                    <input type="text" name="serviceargs[radio][critical]" id="radio_critical" value="<?= encode_form_val($serviceargs["radio"]["critical"] ?? 4) ?>" class="form-control form-control-sm">
                                    <i id="services_radio_critical_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <fieldset class="row g-2 mb-1 wz-fieldset align-items-center noaa">
                    <div class="form-check col-sm-2 d-flex align-items-center">
                        <input type="checkbox" id="solarrad" class="form-check-input me-2" name="services[solarrad]" <?= isset($services["solarrad"]) && $services["solarrad"] ? 'checked="checked"' : '' ?> onchange="updateSelectAll('noaa')">
                        <label for="solarrad" class="form-check-label bold me-2 text-nowrap"><?= _('Solar Radiation') ?> <?= xi6_info_tooltip(_("Monitors the current Solar Radiation level")) ?></label>
                    </div>
                    <div class="col-sm-6 offset-sm-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Warning Threshold (default=1)')) ?> class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                                    </span>
                                    <input type="text" name="serviceargs[solarrad][warning]" id="solarrad_warning" value="<?= encode_form_val($serviceargs["solarrad"]["warning"] ?? 1) ?>" class="form-control form-control-sm">
                                    <i id="services_solarrad_warning_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i <?= xi6_title_tooltip(_('Critical Threshold (default=1)')) ?> class="material-symbols-outlined md-critical md-18 md-400">error</i>
                                    </span>
                                    <input type="text" name="serviceargs[solarrad][critical]" id="solarrad_critical" value="<?= encode_form_val($serviceargs["solarrad"]["critical"] ?? 4) ?>" class="form-control form-control-sm">
                                    <i id="services_solarrad_critical_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>



        <h2 class="mt-4"><?= _('Aurora Alerts') ?></h2>
        <p><?= _('Select where you would like to track auroras or add a custom location') ?></p>
        <ul class="list-group w-75">
                <li class="list-group-item"><?= _('Latitude goes from -90 to 90 North coordinates are positive. South coordinates are negative.') ?></li>
                <li class="list-group-item"><?= _('Longitude goes from 0 to 359 East coordinates do not need to be modified. West coordinates subtract the coordinate from 359.') ?></li>
                <li class="list-group-item"><?= _('Ex: Lat: 67 South and Lon: 137 West will become Lat: -67 and Lon: 222') ?></li>
        </ul>
        <p><?=_(' ')?></p>
        <div id="aurora-locations">
<?php
    if (!empty($aurora)) {
        foreach ($aurora as $index => $location) {
?>
            <div class="row mb-2 aurora-location">
                <div class="col-sm-4">
                    <label for="aurora_name_<?= $index ?>" class="form-check-label bold me-2 text-nowrap"><?= _('Service Name:') ?> </label>
                    <input type="text" name="aurora[<?= $index ?>][name]" id="aurora_name_<?= $index ?>" class="form-control form-control-sm" value="<?= encode_form_val($location['name']) ?>" required>
                </div>
                <div class="col-sm-2">
                    <label for="aurora_lat_<?= $index ?>" class="form-check-label bold me-2 text-nowrap"><?= _('Latitude:') ?> </label>
                    <input type="number" name="aurora[<?= $index ?>][lat]" id="aurora_lat_<?= $index ?>" class="form-control form-control-sm" value="<?= encode_form_val($location['lat']) ?>" min="-90" max="90" required>
                </div>
                <div class="col-sm-2">
                    <label for="aurora_lon_<?= $index ?>" class="form-check-label bold me-2 text-nowrap"><?= _('Longitude:') ?> </label>
                    <input type="number" name="aurora[<?= $index ?>][lon]" id="aurora_lon_<?= $index ?>" class="form-control form-control-sm" value="<?= encode_form_val($location['lon']) ?>" min="0" max="359" required>
                </div>
                <div class="col-sm-2">
                    <label for="aurora_warning_<?= $index ?>" class="form-label"><?= _('Warning:') ?></label>
                    <div class="input-group input-group-sm me-2">
                        <span class="input-group-text">
                            <i class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                        </span>
                        <input type="number" name="aurora[<?= $index ?>][warning]" id="aurora_warning_<?= $index ?>" class="form-control form-control-sm" value="<?= encode_form_val($location['warning']) ?>" min="0" max="100">
                        <i id="aurora_warning_Alert_<?= $index ?>" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="aurora_critical_<?= $index ?>" class="form-label"><?= _('Critical:') ?></label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">
                            <i class="material-symbols-outlined md-critical md-18 md-400">error</i>
                        </span>
                        <input type="number" name="aurora[<?= $index ?>][critical]" id="aurora_critical_<?= $index ?>" class="form-control form-control-sm" value="<?= encode_form_val($location['critical']) ?>" min="0" max="100" >
                        <i id="aurora_critical_Alert_<?= $index ?>" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                    </div>
                </div>
                <div class="col-sm-2 mt-4">
                    <button type="button" class="btn btn-primary btn-sm" onclick="removeAuroraLocation(<?= $index ?>)"><?= _('Remove') ?></button>
                </div>
            </div>
<?php
        }
    }
?>
        </div>

        <button type="button" class="btn btn-primary" onclick="addAuroraLocation()"><?= _('Add Location') ?></button>

        <div class="row mt-4">
            <div class="col-sm-4">
                <label for="preset_locations" class="form-label"><?= _('Preset Locations:') ?></label>
                <select id="preset_locations" class="form-select form-select-sm" onchange="fillAuroraLocation()">
                    <option value=""><?= _('Select a location') ?></option>
                    <!-- Add your preset locations here -->
                    <!-- States -->
                    <option value='{"name": "Anchorage AK Aurora Chance", "lat": 61, "lon": 209}'>Anchorage AK</option>
                    <option value='{"name": "Billings MT Aurora Chance", "lat": 46, "lon": 251}'>Billings MT</option>
                    <option value='{"name": "Bismark ND Aurora Chance", "lat": 47, "lon": 258}'>Bismark ND</option>
                    <option value='{"name": "Boise ID Aurora Chance", "lat": 44, "lon": 243}'>Boise ID</option>
                    <option value='{"name": "Chicago IL Aurora Chance", "lat": 42, "lon": 271}'>Chicago IL</option>
                    <option value='{"name": "Cheyenne WY Aurora Chance", "lat": 41, "lon": 254}'>Cheyenne WY</option>
                    <option value='{"name": "Columbus OH Aurora Chance", "lat": 40, "lon": 276}'>Columbus OH</option>
                    <option value='{"name": "Denver CO Aurora Chance", "lat": 40, "lon": 254}'>Denver CO</option>
                    <option value='{"name": "Des Moines IA Aurora Chance", "lat": 42, "lon": 265}'>Des Moines IA</option>
                    <option value='{"name": "Detroit MI Aurora Chance", "lat": 42, "lon": 276}'>Detroit MI</option>
                    <option value='{"name": "Duluth MN Aurora Chance", "lat": 47, "lon": 267}'>Duluth MN</option>
                    <option value='{"name": "Fargo ND Aurora Chance", "lat": 47, "lon": 262}'>Fargo ND</option>
                    <option value='{"name": "Indianapolis IN Aurora Chance", "lat": 40, "lon": 273}'>Indianapolis IN</option>
                    <option value='{"name": "Kansas City MO Aurora Chance", "lat": 39, "lon": 264}'>Kansas City MO</option>
                    <option value='{"name": "Las Vegas NV Aurora Chance", "lat": 36, "lon": 244}'>Las Vegas NV</option>
                    <option value='{"name": "Madison WI Aurora Chance", "lat": 43, "lon": 270}'>Madison WI</option>
                    <option value='{"name": "Minneapolis MN Aurora Chance", "lat": 45, "lon": 266}'>Minneapolis MN</option>
                    <option value='{"name": "Omaha NE Aurora Chance", "lat": 41, "lon": 263}'>Omaha NE</option>
                    <option value='{"name": "Philadelphia PA Aurora Chance", "lat": 40, "lon": 284}'>Philadelphia PA</option>
                    <option value='{"name": "Pittsburgh PA Aurora Chance", "lat": 40, "lon": 279}'>Pittsburgh PA</option>
                    <option value='{"name": "Portland OR Aurora Chance", "lat": 46, "lon": 236}'>Portland OR</option>
                    <option value='{"name": "Rapid City SD Aurora Chance", "lat": 44, "lon": 256}'>Rapid City SD</option>
                    <option value='{"name": "Rochester NY Aurora Chance", "lat": 43, "lon": 281}'>Rochester NY</option>
                    <option value='{"name": "Sacramento CA Aurora Chance", "lat": 39, "lon": 238}'>Sacramento CA</option>
                    <option value='{"name": "Seattle WA Aurora Chance", "lat": 48, "lon": 238}'>Seattle WA</option>
                    <option value='{"name": "Sioux Falls SD Aurora Chance", "lat": 44, "lon": 262}'>Sioux Falls SD</option>
                    <option value='{"name": "Spokane WA Aurora Chance", "lat": 48, "lon": 241}'>Spokane WA</option>
                    <option value='{"name": "New York City NY Aurora Chance", "lat": 41, "lon": 285}'>New York City NY</option>

                    <!-- Provinces -->
                    <option value='{"name": "Agusta ME Aurora Chance", "lat": 44, "lon": 289}'>Agusta ME</option>
                    <option value='{"name": "Calgary AB Aurora Chance", "lat": 51, "lon": 245}'>Calgary AB</option>
                    <option value='{"name": "Montréal QC Aurora Chance", "lat": 46, "lon": 286}'>Montréal QC</option>
                    <option value='{"name": "Regina SK Aurora Chance", "lat": 50, "lon": 254}'>Regina SK</option>
                    <option value='{"name": "Thunder Bay ON Aurora Chance", "lat": 48, "lon": 270}'>Thunder Bay ON</option>
                    <option value='{"name": "Toronto ON Aurora Chance", "lat": 44, "lon": 280}'>Toronto ON</option>
                    <option value='{"name": "Vancouver BC Aurora Chance", "lat": 49, "lon": 236}'>Vancouver BC</option>
                    <option value='{"name": "Winnipeg MB Aurora Chance", "lat": 50, "lon": 262}'>Winnipeg MB</option>
                    <!-- Add more locations as needed -->
                </select>
            </div>
        </div>

    </div> <!-- container -->

    <script type="text/javascript" src="<?= get_base_url() ?>includes/js/wizards-bs5.js?<?= get_build_id(); ?>"></script>
    <script type="text/javascript">
        let auroraLocationCount = 0;

        function addAuroraLocation() {
            auroraLocationCount++;
            const auroraLocations = document.getElementById('aurora-locations');
            const newLocation = document.createElement('div');
            newLocation.className = 'row mb-2 aurora-location';
            newLocation.innerHTML = `
                <div class="col-sm-4">
                    <label for="aurora_name_${auroraLocationCount}" class="form-label"><?= _('Service Name:') ?> </label>
                    <input type="text" name="aurora[${auroraLocationCount}][name]" id="aurora_name_${auroraLocationCount}" class="form-control form-control-sm" placeholder="<?= _('Enter Service Name') ?>" >
                </div>
                <div class="col-sm-2">
                    <label for="aurora_lat_${auroraLocationCount}" class="form-label"><?= _('Latitude:') ?> </label>
                    <input type="number" name="aurora[${auroraLocationCount}][lat]" id="aurora_lat_${auroraLocationCount}" class="form-control form-control-sm" min="-90" max="90" placeholder="<?= _('Latitude') ?>" >
                </div>
                <div class="col-sm-2">
                    <label for="aurora_lon_${auroraLocationCount}" class="form-label"><?= _('Longitude:') ?> </label>
                    <input type="number" name="aurora[${auroraLocationCount}][lon]" id="aurora_lon_${auroraLocationCount}" class="form-control form-control-sm" min="0" max="359" placeholder="<?= _('Longitude') ?>" >
                </div>
                <div class="col-sm-2">
                    <label for="aurora_warning_${auroraLocationCount}" class="form-label"><?= _('Warning:') ?></label>
                    <div class="input-group input-group-sm me-2">
                        <span class="input-group-text">
                            <i class="material-symbols-outlined md-warning md-18 md-400">warning</i>
                        </span>
                        <input type="number" name="aurora[${auroraLocationCount}][warning]" id="aurora_warning_${auroraLocationCount}" class="form-control form-control-sm" min="0" max="100" value="20" placeholder="<?= _('Warning') ?>" >
                        <i id="aurora_warning_Alert_${auroraLocationCount}" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="aurora_critical_${auroraLocationCount}" class="form-label"><?= _('Critical:') ?></label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">
                            <i class="material-symbols-outlined md-critical md-18 md-400">error</i>
                        </span>
                        <input type="number" name="aurora[${auroraLocationCount}][critical]" id="aurora_critical_${auroraLocationCount}" class="form-control form-control-sm" min="0" max="100" value="50" placeholder="<?= _('Critical') ?>" >
                        <i id="aurora_critical_Alert_${auroraLocationCount}" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
                    </div>
                </div>
                <div class="col-sm-2 mt-4">
                    <button type="button" class="btn btn-primary btn-sm" onclick="removeAuroraLocation(${auroraLocationCount})"><?= _('Remove') ?></button>
                </div>
            `;
            auroraLocations.appendChild(newLocation);
        }

        function removeAuroraLocation(id) {
            const location = document.getElementById(`aurora_name_${id}`).closest('.aurora-location');
            location.remove();
        }

        function fillAuroraLocation() {
            const selectedLocation = document.getElementById('preset_locations').value;
            if (selectedLocation) {
                const locationData = JSON.parse(selectedLocation);
                addAuroraLocation();
                document.getElementById(`aurora_name_${auroraLocationCount}`).value = locationData.name;
                document.getElementById(`aurora_lat_${auroraLocationCount}`).value = locationData.lat;
                document.getElementById(`aurora_lon_${auroraLocationCount}`).value = locationData.lon;
                document.getElementById('preset_locations').value = ''; // Reset the dropdown to "Select a location"
            }
        }

        function selectAllInSection(sectionClass, select) {
            const checkboxes = document.querySelectorAll(`.${sectionClass} input[type="checkbox"]`);
            checkboxes.forEach(checkbox => {
                checkbox.checked = select;
            });
        }

        function updateSelectAll(sectionClass) {
            const checkboxes = document.querySelectorAll(`.${sectionClass} input[type="checkbox"]`);
            const selectAllCheckbox = document.getElementById(`select_all_${sectionClass}`);
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            selectAllCheckbox.checked = allChecked;
        }
    </script>
