<?php

include_once(dirname(__FILE__).'/../configwizardhelper.inc.php');
include_once __DIR__.'/../../../utils-xi2024-wizards.inc.php';


responsetime_configwizard_init();

function responsetime_configwizard_init(){
	
	//name / ID for config wizard 
	$name="responsetime";
	
	//relevant info for wizard  
	$args=array(
		CONFIGWIZARD_NAME => $name,
		CONFIGWIZARD_TYPE => CONFIGWIZARD_TYPE_MONITORING,
		CONFIGWIZARD_DESCRIPTION => "This is a developer demo wizard that creates checks for response time.", 
		CONFIGWIZARD_DISPLAYTITLE => "Response Time",
		CONFIGWIZARD_FUNCTION => "responsetime_configwizard_func",
		CONFIGWIZARD_PREVIEWIMAGE => "responsetime.jpg",
		CONFIGWIZARD_VERSION => "1.0",
		CONFIGWIZARD_DATE => "2025-01-14",
		CONFIGWIZARD_COPYRIGHT => "Copyright &copy; 2008-2010 Nagios Enterprises, LLC.",
		CONFIGWIZARD_AUTHOR => "Nagios Enterprises, LLC",
		);
	//register wizard with XI 	
	register_configwizard($name,$args);
	}

/**
 * @param string $mode
 * @param null   $inargs
 * @param        $outargs
 * @param        $result
 *
 * @return string
 */

function responsetime_configwizard_func($mode="",$inargs=null,&$outargs,&$result){

	$wizard_name="responsetime";

	// initialize return code and output
	$result=0;
	$output="";
	
	// initialize output args - pass back the same data we got -> used by XI framework, don't change
	$outargs[CONFIGWIZARD_PASSBACK_DATA]=$inargs;
	
	//main wizard stage switch 	
	switch($mode){
		case CONFIGWIZARD_MODE_GETSTAGE1HTML:
			
			$address = grab_array_var($inargs, "ip_address", "");
			$nodes = get_configwizard_hosts($wizard_name);

			ob_start();
			include __DIR__.'/steps/step1.php';
            $output = ob_get_clean();
			
			

			break;
		
		//FORM VALIDATION FOR STAGE 1 
		case CONFIGWIZARD_MODE_VALIDATESTAGE1DATA:		
		
			$address = grab_array_var($inargs, "ip_address", "");
						
			$errors=0;
			$errmsg=array();
			if(have_value($address)==false){
				$errmsg[$errors++]="No host address specified.";
			 }
			
				
			if($errors>0){
				$outargs[CONFIGWIZARD_ERROR_MESSAGES]=$errmsg;
				$result=1;
				}
			//proceeed to next stage if there are no errors, or show stage 1 if there are errors  	
			break;
			
		case CONFIGWIZARD_MODE_GETSTAGE2HTML:
			//get variables that were passed to us 
			$address = grab_array_var($inargs, "ip_address");

            $ha = @gethostbyaddr($address);

            if ($ha == "") {
                $ha = $address;
            }

            $hostname = grab_array_var($inargs, "hostname", $ha);

			$warns = grab_array_var($inargs, "warns"); 
			$crits = grab_array_var($inargs, "crits");

			ob_start();
            include __DIR__.'/steps/step2.php';
            $output = ob_get_clean();

			
			break;
		
		//form validation stage 2 
		case CONFIGWIZARD_MODE_VALIDATESTAGE2DATA:
			
				
			// get variables that were passed to us
			$address=grab_array_var($inargs, "ip_address");
			$hostname = grab_array_var($inargs, "hostname");
            $hostname = nagiosccm_replace_user_macros($hostname);
			$warns = grab_array_var($inargs, "warns"); 
			$crits = grab_array_var($inargs, "crits");
			
			
			// check for errors
			$errors=0;
			$errmsg=array();
			if(have_value($address)==false){
				$errmsg[$errors++]="No host address specified.";
			}else{
				if (!preg_match("#^https?://#", $address)) {
					$address = "http://" . $address;
				}
				// check if it's a valid url or ip address
				if(!filter_var($address,FILTER_VALIDATE_URL)){
					if(!filter_var($address,FILTER_VALIDATE_IP)){
						$errmsg[$errors++]="Invalid Host address. Must enter a valid URL or IP address";
					}

				}
			}
			if (is_valid_host_name($hostname) == false) {
                $errmsg[$errors++] = _("Invalid host name.");
            }
		
					
			if($errors>0){
				$outargs[CONFIGWIZARD_ERROR_MESSAGES]=$errmsg;
				$result=1;
				}
				
			break;
			

						
		case CONFIGWIZARD_MODE_GETSTAGE3HTML:
		
			$address = grab_array_var($inargs, "ip_address");
            $hostname = grab_array_var($inargs, "hostname");

            $warns = grab_array_var($inargs, "warns");
            $crits = grab_array_var($inargs, "crits");


            $output = '

        <input type="hidden" name="ip_address" value="' . encode_form_val($address) . '">
        <input type="hidden" name="hostname" value="' . encode_form_val($hostname) . '">
        <input type="hidden" name="warns" value="' .  encode_form_val($warns) . '">
        <input type="hidden" name="crits" value="' .  encode_form_val($crits) . '">
';
		
			break;
			
		case CONFIGWIZARD_MODE_VALIDATESTAGE3DATA:
			$address = grab_array_var($inargs, "ip_address");
            $hostname = grab_array_var($inargs, "hostname");
            $warns = grab_array_var($inargs, "warns");
            $crits = grab_array_var($inargs, "crits");

			
			break;
					
		 		 
		 		
		case CONFIGWIZARD_MODE_GETFINALSTAGEHTML:
			$address = grab_array_var($inargs, "ip_address");
            $hostname = grab_array_var($inargs, "hostname");
            $warns = grab_array_var($inargs, "warns");
            $crits = grab_array_var($inargs, "crits");

			$output = '

			<input type="hidden" name="ip_address" value="' . encode_form_val($address) . '">
			<input type="hidden" name="hostname" value="' . encode_form_val($hostname) . '">
			<input type="hidden" name="warns" value="' .  encode_form_val($warns) . '">
			<input type="hidden" name="crits" value="' .  encode_form_val($crits) . '">
	';
			
			break;
		
		//commit the wizard data into objects definitions to be imported 
		case CONFIGWIZARD_MODE_GETOBJECTS:
		
			//get the session data to turn into object configs 
			$hostname = grab_array_var($inargs, "hostname", "");
            $address = grab_array_var($inargs, "ip_address", "");
			$hostaddress = $address;
			$warns = grab_array_var($inargs, "warns", "");
            $crits = grab_array_var($inargs, "crits", "");


			//initialize objects array 
			$meta_arr = array();
            $meta_arr["hostname"] = $hostname;
            $meta_arr["ip_address"] = $address;
			$meta_arr["warns"] = $warns;
            $meta_arr["crits"] = $crits;
			save_configwizard_object_meta($wizard_name, $hostname, "", $meta_arr);

			$objs = array();
			//make sure it's not a duplicate hostname 
			if(!host_exists($hostname))
			{
			
				$objs[]=array(
					"type" 			=> OBJECTTYPE_HOST,
					"use" 			=> "xiwizard_responsetime_host",
					"host_name"		=> $hostname,
					"address" 		=> $address,
					"icon_image" => "responsetime.jpg",
                    "statusmap_image" => "responsetime.jpg",
					"_xiwizard" 	=> $wizard_name,
					);
			}
	
			$objs[]=array(
				"type" 					=> OBJECTTYPE_SERVICE,
				"host_name" 			=> $hostname,
				"address" 		   		=> $address,
				"service_description" 	=> "Get Response Time",
				"use" 					=> "xiwizard_responsetime_service",
				"check_command" 		=> "check_response_time!-H " . $address . " !-w " . $warns. " !-c " . $crits,
				"_xiwizard" 			=> $wizard_name,
				);					
			
			//print("Objects: " . print_r($objs, true));
	
					
			// return the object definitions to the wizard
			$outargs[CONFIGWIZARD_NAGIOS_OBJECTS]=$objs;
			
			//clear the session variables for this wizard run
			unset($_SESSION['wizarddemo']); 
		
			break;
			
		default:
			break;			
		}
		
	return $output;
	}
	

?>