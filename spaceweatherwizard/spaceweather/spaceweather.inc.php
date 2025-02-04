<?php
//
// Printer Config Wizard
// Copyright (c) 2008-2024 Nagios Enterprises, LLC. All rights reserved.
//

include_once(dirname(__FILE__) . '/../configwizardhelper.inc.php');

spaceweather_configwizard_init();

function spaceweather_configwizard_init()
{
    $name = "spaceweather";
    $args = array(
        CONFIGWIZARD_NAME => $name,
        CONFIGWIZARD_VERSION => "1.0.0",
        CONFIGWIZARD_TYPE => CONFIGWIZARD_TYPE_MONITORING,
        CONFIGWIZARD_DESCRIPTION => _("Monitor various amounts of space weather data."),
        CONFIGWIZARD_DISPLAYTITLE => _("Space Weather"),
        CONFIGWIZARD_FUNCTION => "spaceweather_configwizard_func",
        CONFIGWIZARD_PREVIEWIMAGE => "sun1.png",
        CONFIGWIZARD_REQUIRES_VERSION => 60100
    );
    register_configwizard($name, $args);
}

/**
 * @param string $mode
 * @param null   $inargs
 * @param        $outargs
 * @param        $result
 *
 * @return string
 */
function spaceweather_configwizard_func($mode = "", $inargs = null, &$outargs = null, &$result = null)
{
    $wizard_name = "spaceweather";

    // initialize return code and output
    $result = 0;
    $output = "";

    // initialize output args - pass back the same data we got
    $outargs[CONFIGWIZARD_PASSBACK_DATA] = $inargs;

    switch ($mode) {
        case CONFIGWIZARD_MODE_GETSTAGE1HTML:
            // Provide a simple form for the first stage
            $output = '
            <h5>' . _('Space Weather Wizard') . '</h5>
            <p>' . _('This wizard will guide you through the configuration of space weather monitoring.') . '</p>
            <input type="hidden" name="ip_address" value="127.0.0.1">
            ';
            break;

        case CONFIGWIZARD_MODE_GETSTAGE2HTML:
        
            $hostname = grab_array_var($inargs, "hostname", @gethostbyaddr($address));
            $services_serial = grab_array_var($inargs, "services_serial", "");
            $serviceargs_serial = grab_array_var($inargs, "serviceargs_serial", "");
            $aurora_serial = grab_array_var($inargs, "aurora_serial", "");

            if ($services_serial != "") {
                $services = json_decode(base64_decode($services_serial), true);
            }
            if ($serviceargs_serial != "") {
                $serviceargs = json_decode(base64_decode($serviceargs_serial), true);
            }
            if ($aurora_serial != "") {
                $aurora = json_decode(base64_decode($aurora_serial), true);
            }

            ob_start();
            include __DIR__.'/steps/step2.php';
            $output = ob_get_clean();
            break;

        case CONFIGWIZARD_MODE_VALIDATESTAGE2DATA:
        
            $hostname = grab_array_var($inargs, "hostname");
            $hostname = nagiosccm_replace_user_macros($hostname);

            $services = grab_array_var($inargs, "services", array());
            $serviceargs = grab_array_var($inargs, "serviceargs", array());
            $aurora = grab_array_var($inargs, "aurora", array());

            $errors = 0;
            $errmsg = array();

            if (is_valid_host_name($hostname) == false) {
                $errmsg[$errors++] = _("Invalid host name.");
            }

            $required_services = [
                "windspeed" => ["warning" => "Warning threshold for Solar Wind Speed is required.", "critical" => "Critical threshold for Solar Wind Speed is required."],
                "density" => ["warning" => "Warning threshold for Solar Wind Density is required.", "critical" => "Critical threshold for Solar Wind Density is required."],
                "bt" => ["warning" => "Warning threshold for Bt is required.", "critical" => "Critical threshold for Bt is required."],
                "bz" => ["warning" => "Warning threshold for Bz is required.", "critical" => "Critical threshold for Bz is required."],
                "gms" => ["warning" => "Warning threshold for Geomagnetic Storm is required.", "critical" => "Critical threshold for Geomagnetic Storm is required."],
                "radio" => ["warning" => "Warning threshold for Radio Blackout is required.", "critical" => "Critical threshold for Radio Blackout is required."],
                "solarrad" => ["warning" => "Warning threshold for Solar Radiation is required.", "critical" => "Critical threshold for Solar Radiation is required."],
                "kp" => ["warning" => "Warning threshold for Kp index is required.", "critical" => "Critical threshold for Kp index is required."],
                "3day" => ["warning" => "Warning threshold for Three Day Forecast is required.", "critical" => "Critical threshold for Three Day Forecast is required."],
                "hpin" => ["warning" => "Warning threshold for Hemispheric Power Index North is required.", "critical" => "Critical threshold for Hemispheric Power Index North is required."],
                "hpis" => ["warning" => "Warning threshold for Hemispheric Power Index South is required.", "critical" => "Critical threshold for Hemispheric Power Index South is required."]
            ];

            foreach ($required_services as $service => $thresholds) {
                if (isset($services[$service])) {
                    foreach ($thresholds as $threshold => $message) {
                        if (empty($serviceargs[$service][$threshold])) {
                            $errmsg[$errors++] = _($message);
                        }
                    }
                }
            }

            if (!empty($aurora)) {
                foreach ($aurora as $location) {
                    if (empty($location["name"])) {
                        $errmsg[$errors++] = _("Service name for Aurora is required.");
                    }
                    if (empty($location["lat"]) || !is_numeric($location["lat"]) || $location["lat"] < -90 || $location["lat"] > 90) {
                        $errmsg[$errors++] = _("Valid latitude for Aurora is required.");
                    }
                    if (empty($location["lon"]) || !is_numeric($location["lon"]) || $location["lon"] < 0 || $location["lon"] > 359) {
                        $errmsg[$errors++] = _("Valid longitude for Aurora is required.");
                    }
                    if (empty($location["warning"]) || !is_numeric($location["warning"])) {
                        $errmsg[$errors++] = _("Warning threshold for Aurora is required and must be an integer.");
                    }
                    if (empty($location["critical"]) || !is_numeric($location["critical"])) {
                        $errmsg[$errors++] = _("Critical threshold for Aurora is required and must be an integer.");
                    }
                }
            }

            if ($errors > 0) {
                $outargs[CONFIGWIZARD_ERROR_MESSAGES] = $errmsg;
                $result = 1;
            } else {
                $outargs[CONFIGWIZARD_PASSBACK_DATA] = array(
                    "hostname" => filter_var($hostname, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                    "services" => $services,
                    "serviceargs" => $serviceargs,
                    "aurora" => $aurora
                );
                $outargs["services_serial"] = base64_encode(json_encode($services));
                $outargs["serviceargs_serial"] = base64_encode(json_encode($serviceargs));
                $outargs["aurora_serial"] = base64_encode(json_encode($aurora));
            }
            break;

        case CONFIGWIZARD_MODE_GETSTAGE3HTML:
            $hostname = grab_array_var($inargs, "hostname");
            $services = grab_array_var($inargs, "services", array());
            $serviceargs = grab_array_var($inargs, "serviceargs", array());
            $aurora = grab_array_var($inargs, "aurora", array());

            $services_serial = (!empty($services) ? base64_encode(json_encode($services)) : grab_array_var($inargs, "services_serial", ''));
            $serviceargs_serial = (!empty($serviceargs) ? base64_encode(json_encode($serviceargs)) : grab_array_var($inargs, "serviceargs_serial", ''));
            $aurora_serial = (!empty($aurora) ? base64_encode(json_encode($aurora)) : grab_array_var($inargs, "aurora_serial", ''));

            $output = '
            <input type="hidden" name="ip_address" value="' . encode_form_val($address) . '">
            <input type="hidden" name="hostname" value="' . encode_form_val($hostname) . '">
            <input type="hidden" name="services_serial" value="' . $services_serial . '">
            <input type="hidden" name="serviceargs_serial" value="' . $serviceargs_serial . '">
            <input type="hidden" name="aurora_serial" value="' . $aurora_serial . '">';
            break;

        case CONFIGWIZARD_MODE_VALIDATESTAGE3DATA:
            break;

        case CONFIGWIZARD_MODE_GETFINALSTAGEHTML:
            $output = '';
            break;

        case CONFIGWIZARD_MODE_GETOBJECTS:
            $hostname = grab_array_var($inargs, "hostname", "");
            $address = "127.0.0.1";
            $hostaddress = $address;

            $services_serial = grab_array_var($inargs, "services_serial", "");
            $serviceargs_serial = grab_array_var($inargs, "serviceargs_serial", "");
            $aurora_serial = grab_array_var($inargs, "aurora_serial", "");

            $services = json_decode(base64_decode($services_serial), true);
            $serviceargs = json_decode(base64_decode($serviceargs_serial), true);
            $aurora = json_decode(base64_decode($aurora_serial), true);

            // Sanitize inputs
            $hostname = filter_var($hostname, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $address = encode_form_val($address);
            foreach ($services as $key => $value) {
                $services[$key] = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            foreach ($serviceargs as $key => $args) {
                foreach ($args as $arg_key => $arg_value) {
                    $serviceargs[$key][$arg_key] = filter_var($arg_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }
            }
            foreach ($aurora as $key => $location) {
                $aurora[$key]['name'] = filter_var($location['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $aurora[$key]['lat'] = filter_var($location['lat'], FILTER_VALIDATE_INT);
                $aurora[$key]['lon'] = filter_var($location['lon'], FILTER_VALIDATE_INT);
                $aurora[$key]['warning'] = filter_var($location['warning'], FILTER_SANITIZE_NUMBER_INT);
                $aurora[$key]['critical'] = filter_var($location['critical'], FILTER_SANITIZE_NUMBER_INT);
            }

            // Debug: Print services and serviceargs
            error_log("Services: " . print_r($services, true));
            error_log("Service Args: " . print_r($serviceargs, true));
            error_log("Aurora: " . print_r($aurora, true));

            $meta_arr = array();
            $meta_arr["hostname"] = $hostname;
            $meta_arr["ip_address"] = $address;
            $meta_arr["services"] = $services;
            $meta_arr["serviceargs"] = $serviceargs;
            $meta_arr["aurora"] = $aurora;
            save_configwizard_object_meta($wizard_name, $hostname, "", $meta_arr);

            $objs = array();

            $objs[] = array(
                "type" => OBJECTTYPE_HOST,
                "use" => "xiwizard_spaceweather_host",
                "host_name" => $hostname,
                "address" => $address,
                "_xiwizard" => $wizard_name,
            );

            $service_definitions = [
                "windspeed" => ["description" => "Solar Wind Speed", "command" => "check_space_weather!-w"],
                "density" => ["description" => "Solar Wind Density", "command" => "check_space_weather!-d"],
                "bt" => ["description" => "Bt", "command" => "check_space_weather!-bt"],
                "bz" => ["description" => "Bz", "command" => "check_space_weather!-bz"],
                "gms" => ["description" => "Geomagnetic Storm", "command" => "check_space_weather!-g"],
                "radio" => ["description" => "Radio Blackout", "command" => "check_space_weather!-r"],
                "solarrad" => ["description" => "Solar Radiation", "command" => "check_space_weather!-s"],
                "kp" => ["description" => "Kp Index", "command" => "check_space_weather!-k"],
                "3day" => ["description" => "Three Day Forecast", "command" => "check_space_weather!-3d"],
                "hpin" => ["description" => "Hemispheric Power Index North", "command" => "check_space_weather!-hpiN"],
                "hpis" => ["description" => "Hemispheric Power Index South", "command" => "check_space_weather!-hpiS"]
            ];

            foreach ($services as $svc => $svcstate) {
                if ($svcstate && isset($service_definitions[$svc])) {
                    $objs[] = array(
                        "type" => OBJECTTYPE_SERVICE,
                        "host_name" => $hostname,
                        "service_description" => $service_definitions[$svc]["description"],
                        "use" => "xiwizard_generic_service",
                        "check_command" => $service_definitions[$svc]["command"] . " -W " . $serviceargs[$svc]["warning"] . " -C " . $serviceargs[$svc]["critical"],
                        "check_interval" => 2,
                        "_xiwizard" => $wizard_name,
                    );
                }
            }

            // Handle services without -W or -C options
            $special_services = [
                "coronalmass" => ["description" => "Coronal Mass Ejection", "command" => "check_space_weather!-c"],
                "solarflare" => ["description" => "Solar Flare", "command" => "check_space_weather!-f"]
            ];

            foreach ($services as $svc => $svcstate) {
                if ($svcstate && isset($special_services[$svc])) {
                    $objs[] = array(
                        "type" => OBJECTTYPE_SERVICE,
                        "host_name" => $hostname,
                        "service_description" => $special_services[$svc]["description"],
                        "use" => "xiwizard_generic_service",
                        "check_command" => $special_services[$svc]["command"],
                        "check_interval" => 2,
                        "_xiwizard" => $wizard_name,
                    );
                }
            }

            if (!empty($aurora)) {
                foreach ($aurora as $location) {
                    $objs[] = array(
                        "type" => OBJECTTYPE_SERVICE,
                        "host_name" => $hostname,
                        "service_description" => filter_var($location["name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        "use" => "xiwizard_generic_service",
                        "check_command" => "check_space_weather!-p -lat " . $location["lat"] . " -lon " . $location["lon"] . " -W " . $location["warning"] . " -C " . $location["critical"],
                        "check_interval" => 2,
                        "_xiwizard" => $wizard_name,
                    );
                }
            }

            $outargs[CONFIGWIZARD_NAGIOS_OBJECTS] = $objs;
            break;

        default:
            break;
    }

    return $output;
}
