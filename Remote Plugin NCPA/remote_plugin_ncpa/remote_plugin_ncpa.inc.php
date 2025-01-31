<?php
//
// Remote Plugin NCPA Config Wizard
// Copyright (c) 2025 Nagios Enterprises, LLC. All rights reserved.
//

include_once(dirname(__FILE__) . '/../configwizardhelper.inc.php');

remote_plugin_ncpa_configwizard_init();

function remote_plugin_ncpa_configwizard_init()
{
    $name = "remote_plugin_ncpa";
    $args = array(
        CONFIGWIZARD_NAME => $name,
        CONFIGWIZARD_VERSION => "1.0.0",
        CONFIGWIZARD_TYPE => CONFIGWIZARD_TYPE_MONITORING,
        CONFIGWIZARD_DESCRIPTION => _("Execute a plugin on a remote host via NCPA."),
        CONFIGWIZARD_DISPLAYTITLE => "Remote Plugin NCPA",
        CONFIGWIZARD_FUNCTION => "remote_plugin_ncpa_configwizard_func",
        CONFIGWIZARD_PREVIEWIMAGE => "remote_plugin_ncpa.png",
        CONFIGWIZARD_FILTER_GROUPS => array('network', 'server'),
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
function remote_plugin_ncpa_configwizard_func($mode = "", $inargs = null, &$outargs = null, &$result = null)
{
    $wizard_name = "remote_plugin_ncpa";

    // Initialize return code and output
    $result = 0;
    $output = "";

    // Initialize output args - pass back the same data we got
    $outargs[CONFIGWIZARD_PASSBACK_DATA] = $inargs;

    switch ($mode) {
        case CONFIGWIZARD_MODE_GETSTAGE1HTML:

            $address = grab_array_var($inargs, "ip_address", "");

            # Get the existing host/node configurations.
            # TODO: Include passwords/secrets?
            $nodes = get_configwizard_hosts($wizard_name);

            ########################################################################################
            # Load the html
            # - The html needs to end up in the $output string, so use ob_start() and ob_get_clean()
            #   to load the PHP from the Step1 file into the $output string.
            ########################################################################################
            ob_start();
            include __DIR__.'/steps/step1.php';
            $output = ob_get_clean();

            break;

        case CONFIGWIZARD_MODE_VALIDATESTAGE1DATA:

            // Get variables that were passed to us
            $address = grab_array_var($inargs, "ip_address", "");
            $address = nagiosccm_replace_user_macros($address);

            // Check for errors
            $errors = 0;
            $errmsg = array();

            if (have_value($address) == false) {
                $errmsg[$errors++] = _("No address specified.");
            }

            if ($errors > 0) {
                $outargs[CONFIGWIZARD_ERROR_MESSAGES] = $errmsg;
                $result = 1;
            }

            break;

        case CONFIGWIZARD_MODE_GETSTAGE2HTML:

            // Get variables that were passed to us
            $address = grab_array_var($inargs, "ip_address");

            $ha = @gethostbyaddr($address);

            if ($ha == "") {
                $ha = $address;
            }

            $hostname = grab_array_var($inargs, "hostname", $ha);

            $plugin = grab_array_var($inargs, "plugin");
            $token = grab_array_var($inargs, "token");
            $arguments = grab_array_var($inargs, "arguments");
            $port = grab_array_var($inargs, "port", "5693");


            ########################################################################################
            # Load the html
            # - The html needs to end up in the $output string, so use ob_start() and ob_get_clean()
            #   to load the PHP from the Step2 file into the $output string.
            ########################################################################################
            ob_start();
            include __DIR__.'/steps/step2.php';
            $output = ob_get_clean();

            break;

        case CONFIGWIZARD_MODE_VALIDATESTAGE2DATA:

            // Get variables that were passed to us
            $address = grab_array_var($inargs, "ip_address");
            $hostname = grab_array_var($inargs, "hostname");
            $hostname = nagiosccm_replace_user_macros($hostname);

            $services = grab_array_var($inargs, "services", array());
            $serviceargs = grab_array_var($inargs, "serviceargs", array());

            $plugin = grab_array_var($inargs, "plugin");
            $token = grab_array_var($inargs, "token");
            $arguments = grab_array_var($inargs, "arguments");
            $port = grab_array_var($inargs, "port", "5693");

            // Check for errors
            $errors = 0;
            $errmsg = array();
            if (is_valid_host_name($hostname) == false)
                $errmsg[$errors++] = "Invalid host name.";

            if (have_value($plugin) == false) {
                $errmsg[$errors++] = _("No plugin specified.");
            }
            if (have_value($port) == false) {
                $errmsg[$errors++] = _("No port specified.");
            }
            if (have_value($token) == false) {
                $errmsg[$errors++] = _("No token specified.");
            }

                
            if ($errors > 0) {
                $outargs[CONFIGWIZARD_ERROR_MESSAGES] = $errmsg;
                $result = 1;
            }

            break;


        case CONFIGWIZARD_MODE_GETSTAGE3HTML:

            // get variables that were passed to us
            $address = grab_array_var($inargs, "ip_address");
            $hostname = grab_array_var($inargs, "hostname");

            $services = grab_array_var($inargs, "services");
            $serviceargs = grab_array_var($inargs, "serviceargs");
            $plugin = grab_array_var($inargs, "plugin");
            $token = grab_array_var($inargs, "token");
            $arguments = grab_array_var($inargs, "arguments");
            $port = grab_array_var($inargs, "port", "5693");

            $output = '

        <input type="hidden" name="ip_address" value="' . encode_form_val($address) . '">
        <input type="hidden" name="hostname" value="' . encode_form_val($hostname) . '">
        <input type="hidden" name="plugin" value="' . encode_form_val($plugin) . '">
        <input type="hidden" name="token" value="' . encode_form_val($token) . '">
        <input type="hidden" name="arguments" value="' . encode_form_val($arguments) . '">
        <input type="hidden" name="port" value="' . encode_form_val($port) . '">
        <input type="hidden" name="services_serial" value="' . base64_encode(json_encode($services)) . '">
        <input type="hidden" name="serviceargs_serial" value="' . base64_encode(json_encode($serviceargs)) . '">
';
            break;

        case CONFIGWIZARD_MODE_VALIDATESTAGE3DATA:
            $address = grab_array_var($inargs, "ip_address");
            $hostname = grab_array_var($inargs, "hostname");

            $services = grab_array_var($inargs, "services");
            $serviceargs = grab_array_var($inargs, "serviceargs");
            $plugin = grab_array_var($inargs, "plugin");
            $token = grab_array_var($inargs, "token");
            $arguments = grab_array_var($inargs, "arguments");
            $port = grab_array_var($inargs, "port", "5693");
            break;

        case CONFIGWIZARD_MODE_GETFINALSTAGEHTML:
            $address = grab_array_var($inargs, "ip_address");
            $hostname = grab_array_var($inargs, "hostname");

            $services = grab_array_var($inargs, "services");
            $serviceargs = grab_array_var($inargs, "serviceargs");
            $plugin = grab_array_var($inargs, "plugin");
            $token = grab_array_var($inargs, "token");
            $arguments = grab_array_var($inargs, "arguments");
            $port = grab_array_var($inargs, "port", "5693");
            $output = '
            ';
            break;

        case CONFIGWIZARD_MODE_GETOBJECTS:

            $hostname = grab_array_var($inargs, "hostname", "");
            $address = grab_array_var($inargs, "ip_address", "");
            $hostaddress = $address;

            $services_serial = grab_array_var($inargs, "services_serial", "");
            $serviceargs_serial = grab_array_var($inargs, "serviceargs_serial", "");

            $services = json_decode(base64_decode($services_serial), true);
            $serviceargs = json_decode(base64_decode($serviceargs_serial), true);
            
            $plugin = grab_array_var($inargs, "plugin");
            $token = grab_array_var($inargs, "token");
            $arguments = grab_array_var($inargs, "arguments");
            $port = grab_array_var($inargs, "port", "5693");

            // save data for later use in re-entrance
            $meta_arr = array();
            $meta_arr["hostname"] = $hostname;
            $meta_arr["ip_address"] = $address;
            $meta_arr["services"] = $services;
            $meta_arr["serivceargs"] = $serviceargs;
            $meta_arr["plugin"] = $plugin;
            $meta_arr["token"] = $token;
            $meta_arr["arguments"] = $arguments;
            $meta_arr["port"] = $port;
            save_configwizard_object_meta($wizard_name, $hostname, "", $meta_arr);
            
            $objs = array();

            if (!host_exists($hostname)) {
                $objs[] = array(
                    "type" => OBJECTTYPE_HOST,
                    "use" => "xiwizard_remote_plugin_ncpa_host",
                    "host_name" => $hostname,
                    "address" => $hostaddress,
                    "check_command" => "check_ncpa! -M 'plugins/" . $plugin . "'!-t '" . $token . "'!-P '" . $port . "'!-a '" . $arguments . "'",
                    "icon_image" => "remote_plugin_ncpa.png",
                    "statusmap_image" => "remote_plugin_ncpa.png",
                    "_xiwizard" => $wizard_name,
                );
            }

            $pluginopts = "";
            $objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $hostname,
                "service_description" => "NCPA Remote Plugin",
                "use" => "xiwizard_remote_plugin_ncpa_service",
                "check_command" => "check_ncpa! -M 'plugins/" . $plugin . "'! -t '" . $token . "'! -p '" . $port . "'! -a '" . $arguments . "'",
                "_xiwizard" => $wizard_name,
                "icon_image" => "remote_plugin_ncpa.png",
            );


            // Return the object definitions to the wizard
            $outargs[CONFIGWIZARD_NAGIOS_OBJECTS] = $objs;

            break;

        default:
            break;
    }

    return $output;
}
