<?php
//
// Route Hops Config Wizard
// Copyright (c) 2025 Nagios Enterprises, LLC. All rights reserved.
//

include_once(dirname(__FILE__) . '/../configwizardhelper.inc.php');
// include_once __DIR__.'/../../../utils-xi2024-wizards.inc.php';

routehops_configwizard_init();

function routehops_configwizard_init() {
    $name = "routehops";
    $args = array(
        CONFIGWIZARD_NAME => $name,
        CONFIGWIZARD_VERSION => "1.0.0",
        CONFIGWIZARD_TYPE => CONFIGWIZARD_TYPE_MONITORING,
        CONFIGWIZARD_DESCRIPTION => _("Monitor route hops to a host using traceroute."),
        CONFIGWIZARD_DISPLAYTITLE => _("Route Hops"),
        CONFIGWIZARD_FUNCTION => "routehops_configwizard_func",
        CONFIGWIZARD_PREVIEWIMAGE => "routehops.png",
		CONFIGWIZARD_COPYRIGHT => "Copyright &copy; 2025 Nagios Enterprises, LLC.",
        CONFIGWIZARD_AUTHOR => "Nagios Enterprises, LLC",
		CONFIGWIZARD_FILTER_GROUPS => array('website', 'network'),
		CONFIGWIZARD_REQUIRES_VERSION => 500
    );
    register_configwizard($name, $args);
}

function routehops_configwizard_func($mode = "", $inargs = null, &$outargs = null, &$result = null) {
	$wizard_name = "routehops";
	
	// Initialize return code and output
	$result = 0;
	$output = "";
	
	// Initialize output args - pass back the received data
	$outargs[CONFIGWIZARD_PASSBACK_DATA] = $inargs;
	
    switch ($mode) {
        case CONFIGWIZARD_MODE_GETSTAGE1HTML:
			$address = grab_array_var($inargs, "address", "");
			
			# Get the existing host/node configurations
			$nodes = get_configwizard_hosts($wizard_name);
			
			# Load the HTML 
			ob_start();
			include __DIR__.'/steps/step1.php';
			$output = ob_get_clean();
			
            break;

        case CONFIGWIZARD_MODE_VALIDATESTAGE1DATA:
			// Get variables that were passed in 
			$address = grab_array_var($inargs, "address", "");
			$address = nagiosccm_replace_user_macros($address);
			
			// Check for errors
			$errors = 0;
			$errmsg = array();
			
			if (empty($address)) {
				$errmsg[$errors++] = _("No address specified.");
			}
			
			if ($errors > 0) {
				$outargs[CONFIG_WIZARD_MESSAGES] = $errmsg;
				$result = 1;
			}
		
            break;

		case CONFIGWIZARD_MODE_GETSTAGE2HTML:
			// Get variables that were passed to us
            $address = grab_array_var($inargs, "address", "");

			ob_start();
            include __DIR__.'/steps/step2.php';
            $output = ob_get_clean();

            break;

        case CONFIGWIZARD_MODE_VALIDATESTAGE2DATA:
            // Get variables that were passed to us
            $address = grab_array_var($inargs, "address", "");
            $address = nagiosccm_replace_user_macros($address);
            $warning = grab_array_var($inargs, "warning");
            $critical = grab_array_var($inargs, "critical");
            $protocol = grab_array_var($inargs, "protocol");
            $verbosity = grab_array_var($inargs, "verbosity");

            // Check for errors
            $errors = 0;
            $errmsg = array();

            if (empty($address)) {
                $errmsg[$errors++] = _("No address specified.");
            }

            if (!empty($warning) && !is_numeric($warning)) {
                $errmsg[$errors++] = _("Warning threshold must be an integer.");
            }

            if (!empty($critical) && !is_numeric($critical)) {
                $errmsg[$errors++] = _("Critical threshold must be an integer.");
            }

            if (!empty($protocol) && !in_array($protocol, array("TCP", "ICMP", "UDP"))) {
                $errmsg[$errors++] = _("Invalid protocol specified. Must be TCP, ICMP, or UDP.");
            }

            if (!in_array($verbosity, array(0, 1, 2))) {
                $errmsg[$errors++] = _("Invalid verbosity specified. Must be 0, 1, or 2.");
            }

            if ($errors > 0) {
                $outargs[CONFIGWIZARD_ERROR_MESSAGES] = $errmsg;
                $result = 1;
            }

            break;
		
		case CONFIGWIZARD_MODE_GETSTAGE3HTML:
			// Get variables that were passed to us
			$address = grab_array_var($inargs, "address");
			$warning = grab_array_var($inargs, "warning");
			$critical = grab_array_var($inargs, "critical");
            $protocol = grab_array_var($inargs, "protocol");
            $verbosity = grab_array_var($inargs, "verbosity");

			$output = '
				<input type="hidden" name="address" value="' . encode_form_val($address) . '" />
				<input type="hidden" name="warning" value="' . encode_form_val($warning) . '" />
				<input type="hidden" name="critical" value="' . encode_form_val($critical) . '" />
                <input type="hidden" name="protocol" value="' . encode_form_val($protocol) . '" />
                <input type="hidden" name="verbosity" value="' . encode_form_val($verbosity) . '" />
			';
			break;

		case CONFIGWIZARD_MODE_VALIDATESTAGE3DATA:
			break;

        case CONFIGWIZARD_MODE_GETFINALSTAGEHTML:
            $address = grab_array_var($inargs, "address");
            $warning = grab_array_var($inargs, "warning");
            $critical = grab_array_var($inargs, "critical");
            $protocol = grab_array_var($inargs, "protocol");
            $verbosity = grab_array_var($inargs, "verbosity");

            $output = ' ';

            break;

        case CONFIGWIZARD_MODE_GETOBJECTS:
            $address = grab_array_var($inargs, "address", "");
            $warning = grab_array_var($inargs, "warning");
            $critical = grab_array_var($inargs, "critical");
            $protocol = grab_array_var($inargs, "protocol");
            $verbosity = grab_array_var($inargs, "verbosity");

			// Save data for later use in re-entrance
            $meta_arr = array();
            $meta_arr["address"] = $address;
            $meta_arr["warning"] = $warning;
            $meta_arr["critical"] = $critical;
            $meta_arr["protocol"] = $protocol;
            $meta_arr["verbosity"] = $verbosity;
            save_configwizard_object_meta($wizard_name, $address, "", $meta_arr);
			
			$objs = array();

			if (!host_exists($address)) {
                $objs[] = array(
                    "type" => OBJECTTYPE_HOST,
                    "use" => "xiwizard_routehops_host",
                    "host_name" => $address,
					"address" => $address,
                    "icon_image" => "routehops.png",
                    "statusmap_image" => "routehops.png",
                    "_xiwizard" => $wizard_name,
                );
            }
			
			$objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $address,
                "service_description" => "Route Hops",
                "use" => "xiwizard_routehops_service",
                "check_command" => "check_route_hops!-H " . $address . " !-w " . $warning . " !-c " . $critical . " !-p " . $protocol . " !-v " . $verbosity,
                "check_interval" => 1440,
                "_xiwizard" => $wizard_name,
            );

			// Debugging output
            // print("Objects: " . print_r($objs, true));

			// Return the object definitions to the wizard
            $outargs[CONFIGWIZARD_NAGIOS_OBJECTS] = $objs;

            break;
			
		default:
			break;
    }

    return $output;
}
