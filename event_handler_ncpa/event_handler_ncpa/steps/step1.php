<input type="hidden" id="selectedhostconfig" name="selectedhostconfig" value="<?= encode_form_val($selectedhostconfig) ?>" />
<input type="hidden" id="services_serial" name="services_serial" value="<?= (!empty($services)) ? base64_encode(json_encode($services)) : "" ?>" />
<input type="hidden" id="serviceargs_serial" name="serviceargs_serial" value="<?= (!empty($serviceargs)) ? base64_encode(json_encode($serviceargs)) : "" ?>" />
<input type="hidden" id="config_serial" name="config_serial" value="<?= (!empty($config)) ? base64_encode(json_encode($config)) : "" ?>" />

<div class="container m-0 g-0">
	<?php
	#include_once __DIR__.'/../../../utils-xi2024-wizards.inc.php';
	// print_r($hostxml);
	?>
	<!--                         -->
	<!-- The configuration form. -->
	<!--                         -->
	<div id="configForm">
		<h2 class="mb-2"><?= _('Select Service For Event Handler') ?></h2>

		<div class="row mb-2">
			<div class="col-sm-6">
				<label for="hostname" class="form-label"><?= _('Host') ?> <?= xi6_info_tooltip(_('Select the host with the service you would like to add an event handler to.')) ?></label>
				<div class="input-group position-relative">
					<select name="hostname" id="hostname" class="form-control form-control-sm monitor rounded">
						<option value=""><?= _("Select Host") ?></option>
						<?php foreach ($hostxml->host as $host_entry): ?>
							<option value="<?= encode_form_val($host_entry->display_name) ?>"><?= encode_form_val($host_entry->display_name) ?></option>
						<?php endforeach; ?>
					</select>
					<div class="invalid-feedback">
						<?= _("Please select a host") ?>
					</div>
					<i id="host_name_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
				</div>
			</div>
		</div>


		<div class="row mb-2">
			<div class="col-sm-6">
				<label for="service_name" class="form-label"><?= _('Service') ?> <?= xi6_info_tooltip(_('Select the service you would like to add the event handler to. The event handler will be fired whenever the status of the service changes.')) ?></label>
				<div class="input-group position-relative">
					<select name="service_name" id="service_name" class="form-control form-control-sm monitor rounded">
						<?php foreach ($servicexml_min as $service_entry): ?>
							<option value="<?= encode_form_val($service_entry['name']) ?>"><?= encode_form_val($service_entry['host_name'] . ' - ' . $service_entry['name']) ?></option>
						<?php endforeach; ?>
					</select>
					<div class="invalid-feedback">
						<?= _("Please select a service") ?>
					</div>
					<i id="service_name_Alert" class="visually-hidden position-absolute top-0 start-100 translate-middle icon icon-circle color-ok icon-size-status"></i>
				</div>
			</div>
		</div>
	</div> <!-- config -->
</div> <!-- container -->
<script type="text/javascript" src="<?= get_base_url() ?>includes/js/wizards-bs5.js?<?= get_build_id(); ?>"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const hostnameSelect = document.getElementById('hostname');
        const serviceNameSelect = document.getElementById('service_name');
        const allServiceOptions = Array.from(serviceNameSelect.options);

        // Reset service_name options on page load
        serviceNameSelect.innerHTML = '';
        allServiceOptions.forEach(option => {
            if (option.value === '') {
                serviceNameSelect.appendChild(option.cloneNode(true));
            }
        });

        hostnameSelect.addEventListener('change', function() {
            const selectedHostname = this.value;

            // Reset service_name options
            serviceNameSelect.innerHTML = '';

            // Filter and add matching service options
            allServiceOptions.forEach(option => {
                if (option.value === '' || option.text.startsWith(selectedHostname + ' - ')) {
                    serviceNameSelect.appendChild(option.cloneNode(true));
                }
            });

            // Reset service_name selection
            serviceNameSelect.value = '';
        });
    });
</script>