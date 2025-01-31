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
        CONFIGWIZARD_FILTER_GROUPS => array('network'),
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
            $address = "127.0.0.1";
            $nodes = get_configwizard_hosts($wizard_name);

            ob_start();
            include __DIR__.'/steps/step1.php';
            $output = ob_get_clean();
            break;

        case CONFIGWIZARD_MODE_VALIDATESTAGE1DATA:
            $address = "127.0.0.1";
            $address = nagiosccm_replace_user_macros($address);

            $errors = 0;
            $errmsg = array();

            if (have_value($address) == false) {
                $errmsg[$errors++] = _("No address specified.");
            } else if (!valid_ip($address)) {
                $errmsg[$errors++] = _("Invalid IP address.");
            }

            if ($errors > 0) {
                $outargs[CONFIGWIZARD_ERROR_MESSAGES] = $errmsg;
                $result = 1;
            }
            break;

        case CONFIGWIZARD_MODE_GETSTAGE2HTML:
            $address = "127.0.0.1";
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
            $address = "127.0.0.1";
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

            if (isset($services["windspeed"])) {
                if (empty($serviceargs["windspeed"]["warning"])) {
                    $errmsg[$errors++] = _("Warning threshold for Solar Wind Speed is required.");
                }
                if (empty($serviceargs["windspeed"]["critical"])) {
                    $errmsg[$errors++] = _("Critical threshold for Solar Wind Speed is required.");
                }
            }

            if (isset($services["density"])) {
                if (empty($serviceargs["density"]["warning"])) {
                    $errmsg[$errors++] = _("Warning threshold for Solar Wind Density is required.");
                }
                if (empty($serviceargs["density"]["critical"])) {
                    $errmsg[$errors++] = _("Critical threshold for Solar Wind Density is required.");
                }
            }

            if (isset($services["bt"])) {
                if (empty($serviceargs["bt"]["warning"])) {
                    $errmsg[$errors++] = _("Warning threshold for Bt is required.");
                }
                if (empty($serviceargs["bt"]["critical"])) {
                    $errmsg[$errors++] = _("Critical threshold for Bt is required.");
                }
            }

            if (isset($services["bz"])) {
                if (empty($serviceargs["bz"]["warning"])) {
                    $errmsg[$errors++] = _("Warning threshold for Bz is required.");
                }
                if (empty($serviceargs["bz"]["critical"])) {
                    $errmsg[$errors++] = _("Critical threshold for Bz is required.");
                }
            }

            if (isset($services["gms"])) {
                if (empty($serviceargs["gms"]["warning"])) {
                    $errmsg[$errors++] = _("Warning threshold for Geomagnetic Storm is required.");
                }
                if (empty($serviceargs["gms"]["critical"])) {
                    $errmsg[$errors++] = _("Critical threshold for Geomagnetic Storm is required.");
                }
            }

            if (isset($services["radio"])) {
                if (empty($serviceargs["radio"]["warning"])) {
                    $errmsg[$errors++] = _("Warning threshold for Radio Blackout is required.");
                }
                if (empty($serviceargs["radio"]["critical"])) {
                    $errmsg[$errors++] = _("Critical threshold for Radio Blackout is required.");
                }
            }

            if (isset($services["solarrad"])) {
                if (empty($serviceargs["solarrad"]["warning"])) {
                    $errmsg[$errors++] = _("Warning threshold for Solar Radiation is required.");
                }
                if (empty($serviceargs["solarrad"]["critical"])) {
                    $errmsg[$errors++] = _("Critical threshold for Solar Radiation is required.");
                }
            }

            if (isset($services["kp"])) {
                if (empty($serviceargs["kp"]["warning"])) {
                    $errmsg[$errors++] = _("Warning threshold for Kp index is required.");
                }
                if (empty($serviceargs["kp"]["critical"])) {
                    $errmsg[$errors++] = _("Critical threshold for Kp index is required.");
                }
            }

            if (isset($services["3day"])) {
                if (empty($serviceargs["3day"]["warning"])) {
                    $errmsg[$errors++] = _("Warning threshold for Three Day Forecast is required.");
                }
                if (empty($serviceargs["3day"]["critical"])) {
                    $errmsg[$errors++] = _("Critical threshold for Three Day Forecast is required.");
                }
            }

            if (isset($services["hpin"])) {
                if (empty($serviceargs["hpin"]["warning"])) {
                    $errmsg[$errors++] = _("Warning threshold for Hemispheric Power Index North is required.");
                }
                if (empty($serviceargs["hpin"]["critical"])) {
                    $errmsg[$errors++] = _("Critical threshold for Hemispheric Power Index North is required.");
                }
            }

            if (isset($services["hpis"])) {
                if (empty($serviceargs["hpis"]["warning"])) {
                    $errmsg[$errors++] = _("Warning threshold for Hemispheric Power Index South is required.");
                }
                if (empty($serviceargs["hpis"]["critical"])) {
                    $errmsg[$errors++] = _("Critical threshold for Hemispheric Power Index South is required.");
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
                    if (empty($location["warning"])) {
                        $errmsg[$errors++] = _("Warning threshold for Aurora is required.");
                    }
                    if (empty($location["critical"])) {
                        $errmsg[$errors++] = _("Critical threshold for Aurora is required.");
                    }
                }
            }

            if ($errors > 0) {
                $outargs[CONFIGWIZARD_ERROR_MESSAGES] = $errmsg;
                $result = 1;
            } else {
                $outargs[CONFIGWIZARD_PASSBACK_DATA] = array(
                    "hostname" => $hostname,
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
            $address = "127.0.0.1";
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

            foreach ($services as $svc => $svcstate) {
                if ($svcstate) {
                    switch ($svc) {
                        case "windspeed":
                            $objs[] = array(
                                "type" => OBJECTTYPE_SERVICE,
                                "host_name" => $hostname,
                                "service_description" => "Solar Wind Speed",
                                "use" => "xiwizard_solar_windspeed_service",
                                "check_command" => "check_space_weather!-w " . " !-W " . $serviceargs["windspeed"]["warning"] . "!-C " . $serviceargs["windspeed"]["critical"], 
                                "check_interval" => 2,
                                "_xiwizard" => $wizard_name,
                            );
                            break;
                        case "density":
                            $objs[] = array(
                                "type" => OBJECTTYPE_SERVICE,
                                "host_name" => $hostname,
                                "service_description" => "Solar Wind Density",
                                "use" => "xiwizard_generic_service",
                                "check_command" => "check_space_weather!-d " . " !-W " . $serviceargs["density"]["warning"] . "!-C " . $serviceargs["density"]["critical"], 
                                "check_interval" => 2,
                                "_xiwizard" => $wizard_name,
                            );
                            break;
                        case "bt":
                            $objs[] = array(
                                "type" => OBJECTTYPE_SERVICE,
                                "host_name" => $hostname,
                                "service_description" => "Bt",
                                "use" => "xiwizard_generic_service",
                                "check_command" => "check_space_weather!-bt " . " !-W " . $serviceargs["bt"]["warning"] . "!-C " . $serviceargs["bt"]["critical"], 
                                "check_interval" => 2,
                                "_xiwizard" => $wizard_name,
                            );
                            break;
                        case "bz":
                            $objs[] = array(
                                "type" => OBJECTTYPE_SERVICE,
                                "host_name" => $hostname,
                                "service_description" => "Bz",
                                "use" => "xiwizard_generic_service",
                                "check_command" => "check_space_weather!-bz " . " !-W " . $serviceargs["bz"]["warning"] . "!-C " . $serviceargs["bz"]["critical"], 
                                "check_interval" => 2,
                                "_xiwizard" => $wizard_name,
                            );
                            break;
                        case "coronalmass":
                            $objs[] = array(
                                "type" => OBJECTTYPE_SERVICE,
                                "host_name" => $hostname,
                                "service_description" => "Coronal Mass Ejection",
                                "use" => "xiwizard_generic_service",
                                "check_command" => "check_space_weather!-c",
                                "check_interval" => 2,
                                "_xiwizard" => $wizard_name,
                            );
                            break;
                        case "solarflare":
                            $objs[] = array(
                                "type" => OBJECTTYPE_SERVICE,
                                "host_name" => $hostname,
                                "service_description" => "Solar Flare",
                                "use" => "xiwizard_generic_service",
                                "check_command" => "check_space_weather!-f",
                                "check_interval" => 2,
                                "_xiwizard" => $wizard_name,
                            );
                            break;
                        
                        case "gms":
                        $objs[] = array(
                            "type" => OBJECTTYPE_SERVICE,
                            "host_name" => $hostname,
                            "service_description" => "Geomagnetic Storm",
                            "use" => "xiwizard_generic_service",
                            "check_command" => "check_space_weather!-g " . " !-W " . $serviceargs["gms"]["warning"] . "!-C " . $serviceargs["gms"]["critical"], 
                            "check_interval" => 2,
                            "_xiwizard" => $wizard_name,
                        );
                        break;


                        case "radio":
                            $objs[] = array(
                                "type" => OBJECTTYPE_SERVICE,
                                "host_name" => $hostname,
                                "service_description" => "Radio Blackout",
                                "use" => "xiwizard_generic_service",
                                "check_command" => "check_space_weather!-r " . " !-W " . $serviceargs["radio"]["warning"] . "!-C " . $serviceargs["radio"]["critical"], 
                                "check_interval" => 2,
                                "_xiwizard" => $wizard_name,
                            );
                            break;

                        case "solarrad":
                        $objs[] = array(
                            "type" => OBJECTTYPE_SERVICE,
                            "host_name" => $hostname,
                            "service_description" => "Solar Radiation",
                            "use" => "xiwizard_generic_service",
                            "check_command" => "check_space_weather!-s " . " !-W " . $serviceargs["solarrad"]["warning"] . "!-C " . $serviceargs["solarrad"]["critical"], 
                            "check_interval" => 2,
                            "_xiwizard" => $wizard_name,
                        );
                        break;

                        case "kp":
                            $objs[] = array(
                                "type" => OBJECTTYPE_SERVICE,
                                "host_name" => $hostname,
                                "service_description" => "Kp Index",
                                "use" => "xiwizard_generic_service",
                                "check_command" => "check_space_weather!-k " . " !-W " . $serviceargs["kp"]["warning"] . "!-C " . $serviceargs["kp"]["critical"], 
                                "check_interval" => 2,
                                "_xiwizard" => $wizard_name,
                            );
                            break;

                        case "3day":
                        $objs[] = array(
                            "type" => OBJECTTYPE_SERVICE,
                            "host_name" => $hostname,
                            "service_description" => "Three Day Forecast",
                            "use" => "xiwizard_generic_service",
                            "check_command" => "check_space_weather!-3d " . " !-W " . $serviceargs["3day"]["warning"] . "!-C " . $serviceargs["3day"]["critical"], 
                            "check_interval" => 2,
                            "_xiwizard" => $wizard_name,
                        );
                        break;

                        case "hpin":
                            $objs[] = array(
                                "type" => OBJECTTYPE_SERVICE,
                                "host_name" => $hostname,
                                "service_description" => "Hemispheric Power Index North",
                                "use" => "xiwizard_generic_service",
                                "check_command" => "check_space_weather!-hpiN " . " !-W " . $serviceargs["hpin"]["warning"] . "!-C " . $serviceargs["hpin"]["critical"], 
                                "check_interval" => 2,
                                "_xiwizard" => $wizard_name,
                            );
                            break;
                        
                        case "hpis":
                            $objs[] = array(
                                "type" => OBJECTTYPE_SERVICE,
                                "host_name" => $hostname,
                                "service_description" => "Hemispheric Power Index South",
                                "use" => "xiwizard_generic_service",
                                "check_command" => "check_space_weather!-hpiS " . " !-W " . $serviceargs["hpis"]["warning"] . "!-C " . $serviceargs["hpis"]["critical"], 
                                "check_interval" => 2,
                                "_xiwizard" => $wizard_name,
                            );
                            break;

                    }
                }
            }

            if (!empty($aurora)) {
                foreach ($aurora as $location) {
                    $objs[] = array(
                        "type" => OBJECTTYPE_SERVICE,
                        "host_name" => $hostname,
                        "service_description" => $location["name"],
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
