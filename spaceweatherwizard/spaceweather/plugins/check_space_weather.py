#!/usr/bin/env python3
import sys
import time
import argparse
import requests #pip install requests



#gets current date and yesterday's date
current_time = time.time()
current_year =  time.strftime("%Y", time.gmtime(current_time))
current_month = time.strftime("%m", time.gmtime(current_time))
current_day =   time.strftime("%d", time.gmtime(current_time))

yesterday_time = current_time - 86400  # Subtract 24 hours in seconds
yesterday_year = time.strftime("%Y", time.gmtime(yesterday_time))
yesterday_month = time.strftime("%m", time.gmtime(yesterday_time))
yesterday_day = time.strftime("%d", time.gmtime(yesterday_time))


def get_density():
    url = "https://services.swpc.noaa.gov/products/solar-wind/plasma-2-hour.json"  
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        data = response.json()
        if not data or len(data) < 2:
            return None

        latest_entry = data[-1]
        density_value = latest_entry[1]  
        return density_value
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None

def check_wind_speed():
    url = "https://services.swpc.noaa.gov/products/solar-wind/plasma-2-hour.json"  
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        data = response.json()
        if not data or len(data) < 2:
            return None

        latest_entry = data[-1]
        wind_speed_value = latest_entry[2] 
        return wind_speed_value
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return wind_speed_value

def get_solar_flare_M(): #locates the strongest m-class flare in the last 24-48 hours
    url = f"https://kauai.ccmc.gsfc.nasa.gov/DONKI/WS/get/FLR?startDate={yesterday_year}-{yesterday_month}-{yesterday_day}&endDate={current_year}-{current_month}-{current_day}"
    response = requests.get(url)
    response.raise_for_status()
    flares = response.json()
    m_class_flares = [float(flare['classType'][1:]) for flare in flares if flare['classType'].startswith('M')]
    return m_class_flares

def get_solar_flare_X(): #locates the strongest x-class flare in the last 24-48 hours
    url = f"https://kauai.ccmc.gsfc.nasa.gov/DONKI/WS/get/FLR?startDate={yesterday_year}-{yesterday_month}-{yesterday_day}&endDate={current_year}-{current_month}-{current_day}"
    response = requests.get(url)
    response.raise_for_status()
    flares = response.json()
    x_class_flares = [float(flare['classType'][1:]) for flare in flares if flare['classType'].startswith('X')]
    return x_class_flares

def get_geo_mag_storm():
    url = "https://services.swpc.noaa.gov/products/noaa-scales.json" 
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        data = response.json()
        if not data or "0" not in data:
            return None

        geo_mag_storm_value = data["0"]["G"]["Scale"] 
        return geo_mag_storm_value
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None

def get_bz():
    url = "https://services.swpc.noaa.gov/products/solar-wind/mag-2-hour.json"  
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        data = response.json()
        if not data or len(data) < 2:
            return None

        latest_entry = data[-1]
        bz_value = latest_entry[3]  
        return bz_value
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None

def get_bt():
    url = "https://services.swpc.noaa.gov/products/solar-wind/mag-2-hour.json"  
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        data = response.json()
        if not data or len(data) < 2:
            return None

        latest_entry = data[-1]
        bt_value = latest_entry[6] 
        return bt_value
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None

def get_radio_outage():
    url = "https://services.swpc.noaa.gov/products/noaa-scales.json" 
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        data = response.json()
        if not data or "0" not in data:
            return None

        radio_outage_value = data["0"]["R"]["Scale"]  
        return radio_outage_value
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None

def get_solar_radiation():
    url = "https://services.swpc.noaa.gov/products/noaa-scales.json" 
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        data = response.json()
        if not data or "0" not in data:
            return None

        solar_radiation_value = data["0"]["S"]["Scale"] 
        return solar_radiation_value
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None

def get_kp():
    url = "https://services.swpc.noaa.gov/products/noaa-planetary-k-index.json"  
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        data = response.json()
        if not data or len(data) < 2:
            return None

        latest_entry = data[-1]
        kp_value = latest_entry[1]  
        return kp_value
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None

def get_3day_kp():
    url = "https://services.swpc.noaa.gov/text/3-day-forecast.txt"
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        lines = response.text.splitlines()
        
        #lines 13-21 are the 3-day forecast which will be made into 2d array
        forecast = [[0 for i in range(2)] for j in range(9)]
        for i in range(13, 22):
            forecast[i-13] = lines[i].split()
        
        time = None
        date = None
        # finds where the highest kp is in the 3-day forecast

        #Fixes bug when there is G1-5 in the forcast to prevent it from affecting the 2d array searching algorithm
        for i in range(1, len(forecast)):
            forecast[i] = [value for value in forecast[i] if not value.startswith("(G")]
      
        highest_kp = 0
        for i in range(1, 4):
            for j in range(1, 9):
                current_kp = float(forecast[j][i])
                if current_kp > highest_kp:
                    highest_kp = current_kp
                    time = forecast[j][0]
                    date = f"{forecast[0][i*2-2]} {forecast[0][i*2-1]}"

        return [highest_kp, date, time]
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None

def get_cme():
    url = f"https://kauai.ccmc.gsfc.nasa.gov/DONKI/WS/get/CME?startDate={yesterday_year}-{yesterday_month}-{yesterday_day}&endDate={current_year}-{current_month}-{current_day}"
    
    try:
        cmes = []
        response = requests.get(url)
        response.raise_for_status()
        cmes.extend(response.json())
        
        if not cmes:
            return None
        
        most_severe_cme = None
        severity = -1  # -1: None, 0: Not severe, 1: EarthGB, 2: Direct hit
        estimated_shock_arrival_time = None
        
        for cme in cmes: #All the logic for determining and getting the most servere cme
            for analysis in cme.get('cmeAnalyses', []):
                lat = analysis['latitude']
                lon = analysis['longitude']
                half_angle = analysis['halfAngle']
                enlil_list = analysis.get('enlilList')
                earth_gb = any(enlil['isEarthGB'] for enlil in enlil_list) if enlil_list else False
                
                if lat is None or lon is None or half_angle is None:
                    continue
                
                current_severity = 0
                if earth_gb:
                    current_severity = 1
                if (lat == 0 and lon == 0) or \
                   ((lat - half_angle <= 0 <= lat + half_angle) and \
                   (lon - half_angle <= 0 <= lon + half_angle)):
                    current_severity = 2
                
                if current_severity > severity:
                    severity = current_severity
                    most_severe_cme = analysis
                    if enlil_list:
                        estimated_shock_arrival_time = enlil_list[0].get('estimatedShockArrivalTime')
        
        return [severity, estimated_shock_arrival_time] if most_severe_cme else None
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None

def get_hemispheric_power_index_north(): 
    url = "https://services.swpc.noaa.gov/text/aurora-nowcast-hemi-power.txt"
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        lines = response.text.splitlines()
        for line in reversed(lines):
            if not line.startswith("#") and line.strip():
                parts = line.split()
                if len(parts) == 4:
                    return float(parts[2])  # Northern HPI in GW
        print("Northern HPI not found.")
        return None
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None

def get_hemispheric_power_index_south():
    url = "https://services.swpc.noaa.gov/text/aurora-nowcast-hemi-power.txt"
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        lines = response.text.splitlines()
        for line in reversed(lines):
            if not line.startswith("#") and line.strip():
                parts = line.split()
                if len(parts) == 4:
                    return float(parts[3])  # Southern HPI in GW
        print("Southern HPI not found.")
        return None
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None
    


def get_aurora_chance(longitude, latitude):
    url = "https://services.swpc.noaa.gov/json/ovation_aurora_latest.json"
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        data = response.json()
        coordinates = data.get("coordinates", [])
        
        for coord in coordinates:
            if coord[0] == longitude and coord[1] == latitude:
                return coord[2]  # Aurora chance value
        
        print("Aurora chance not found for the given coordinates.")
        return None
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None

def get_mev_flux():
    url = "https://services.swpc.noaa.gov/json/goes/primary/integral-protons-6-hour.json"
    try:
        response = requests.get(url)
        response.raise_for_status()  # Check if the request was successful

        data = response.json()
        if not data or len(data) < 2:
            return None
        mev_flux_value = [0,0,0,0]
        mev_flux_value[0] = round(float(data[-7]['flux']), 3)
        mev_flux_value[1] = round(float(data[-3]['flux']), 3)
        mev_flux_value[2] = round(float(data[-6]['flux']), 3)
        mev_flux_value[3] = round(float(data[-2]['flux']), 3)
        return mev_flux_value
    except requests.exceptions.HTTPError as e:
        print(f"HTTP error occurred: {e}")
    except Exception as e:
        print(f"An error occurred: {e}")
    return None



def main():
    parser = argparse.ArgumentParser(description="Check space weather conditions.") #Creates all arguments for the script
    parser.add_argument('-f', '--flares', action='store_true', help='Check for solar flares.') 
    parser.add_argument('-g', '--geo-mag-storm', action='store_true', help='Check for geomagnetic storms.') 
    parser.add_argument('-r', '--radio-outage', action='store_true', help='Check for radio outages.') 
    parser.add_argument('-s', '--solar-radiation', action='store_true', help='Check for solar radiation.') 
    parser.add_argument('-c', '--cme', action='store_true', help='Check for coronal mass ejections.') 
    parser.add_argument('-w', '--wind-speed', action='store_true', help='Check for solar wind speed.') 
    parser.add_argument('-d', '--density', action='store_true', help='Check for solar wind density.') 
    parser.add_argument('-bt', '--bt', action='store_true', help='Check for Bt.') 
    parser.add_argument('-bz', '--bz', action='store_true', help='Check for Bz.') 
    parser.add_argument('-k', '--kp', action='store_true', help='Check for Kp.') 
    parser.add_argument('-3d', '--three_day', action='store_true', help='Check for the highest kp in the 3-day space weather forecast.') 
    parser.add_argument('-hpiN', '--hemispheric-power-index-north', action='store_true', help='Check for the hemispheric power index for the northern hemisphere.') 
    parser.add_argument('-hpiS', '--hemispheric-power-index-south', action='store_true', help='Check for the hemispheric power index for the southern hemisphere.') 
    parser.add_argument('-p', '--aurora-chance', action='store_true', help='Check for aurora chance in a specific area.')
    parser.add_argument('-lon', '--longitude', type=int, help='Longitude for aurora chance check. (Int: 0-359)')
    parser.add_argument('-lat', '--latitude', type=int, help='Latitude for aurora chance check. (Int: -90-90)')
    parser.add_argument('-v', '--version', action='version', version='Check Space Weather V1.0.0') 
    parser.add_argument('-W', '--warning', type=int, help='Custom warning threshold') 
    parser.add_argument('-C', '--critical', type=int, help='Custom critical threshold')
    parser.add_argument('-MeV', '--MeV', action='store_true', help='Check for MeV flux.')     

    if len(sys.argv) == 1 or sys.argv[1] in {'-h', '--help'}:
        parser.print_help()
        exit(3)

    args = parser.parse_args()
    #Ensures all arguments required are provided for auroras
    if args.aurora_chance and (args.longitude is None or args.latitude is None):
        raise SystemExit("Error: Longitude and latitude must be provided with the -p argument.")
    
    #All the rest of the code is checking which argument is passed and then determing the severity of the space weather condition
    if args.density:
        warning_default = 20
        critical_default = 50
        if args.warning:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        density = get_density()
        split_density = density.split()
        if density == None:
            print("Density not found.")
            exit(3)
        elif float(split_density[0]) > critical_default:
            print(f"CRITICAL - Solar wind density is HIGH: {density} p/cm3 | WindDensity={split_density[0]};;{warning_default};{critical_default};")
            exit(2)
        elif float(split_density[0]) > warning_default:
            print(f"WARNING - Solar wind density is MEDIUM: {density} p/cm3 | WindDensity={split_density[0]};{warning_default};{critical_default};;")
            exit(1)
        else:
            print(f"OK - Solar wind density is LOW: {density} p/cm3 | WindDensity={split_density[0]};{warning_default};{critical_default};;")
            exit(0)

    if args.wind_speed:
        warning_default = 400
        critical_default = 700
        if args.warning:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        wind_speed = check_wind_speed()
        if wind_speed == "--":
            print("Wind speed data is not available.")
            exit(3)
        elif wind_speed == None:
            print("Wind speed not found.")
            exit(3)
        elif float(wind_speed) > critical_default:
            print(f"CRITICAL - Solar wind speed is HIGH: {wind_speed} km/s | WindSpeed={wind_speed};{warning_default};{critical_default};;")
            exit(2)
        elif float(wind_speed) > warning_default: 
            print(f"WARNING - Solar wind speed is MEDIUM: {wind_speed} km/s | WindSpeed={wind_speed};{warning_default};{critical_default};;")
            exit(1) 
        else:
            print(f"OK - Solar wind speed is LOW: {wind_speed} km/s | WindSpeed={wind_speed};{warning_default};{critical_default};;")
            exit(0)

    if args.flares:
        m_class_flares = get_solar_flare_M()
        x_class_flares = get_solar_flare_X()
        if len(x_class_flares) > 0:
            x_class_flares.sort()
            print(f"CRITICAL - X-class solar flare detected, top flare: {x_class_flares[-1]}X | solarFlareSeverity=2;1;2;;")
            exit(2)
        elif len(m_class_flares) > 0:
            m_class_flares.sort()
            print(f"WARNING - M-class solar flare detectedd, top flare: {m_class_flares[-1]}M | solarFlareSeverity=1;1;2;;")
            exit(1)
        else:
            print("OK - No moderate to severe solar flares detected | solarFlareSeverity=0;1;2;;")
            exit(0)

    if args.geo_mag_storm:
        warning_default = 1
        critical_default = 4
        if args.warning or args.warning == 0:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        glvl = get_geo_mag_storm()
        if glvl == "null":
            print("Geomagnetic storm value not found.")
            exit(3)
        glvl = int(glvl)
        if glvl >= critical_default:
            print(f"CRITICAL - G{glvl}-class geomagnetic storm detected | GeoMagStormLvl={glvl};{warning_default};{critical_default};;")
            exit(2)
        elif glvl >= warning_default:
            print(f"WARNING - G{glvl}-class geomagnetic storm detected | GeoMagStormLvl={glvl};{warning_default};{critical_default};;")
            exit(1)
        else:
            print(f"OK - No geomagnetic storm detected | GeoMagStormLvl={glvl};{warning_default};{critical_default};;")
            exit(0)
    
    if args.bz:
        bz = get_bz()
        warning_default = -5
        critical_default = -15
        if args.warning:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        if bz == "--":
            print("Bz data is not available.")
            exit(3)
        elif bz == None:
            print("Bz not found.")
            exit(3)
        elif float(bz) < critical_default:
            print(f"CRITICAL - Bz is strongly South: {bz} nT | Bz={bz}{warning_default};{critical_default};;")
            exit(2)
        elif float(bz) < warning_default:
            print(f"WARNING - Bz is moderately South: {bz} nT | Bz={bz}{warning_default};{critical_default};;")
            exit(1)
        elif float(bz) < 0:
            print(f"OK - Bz is slightly South: {bz} nT | Bz={bz};{warning_default};{critical_default};;")
            exit(0)
        else:
            print(f"OK - Bz is North: {bz} nT | Bz={bz};{warning_default};{critical_default};;")
            exit(0)

    if args.bt:
        warning_default = 10
        critical_default = 20
        if args.warning:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        bt = get_bt()
        if bt == "--":
            print("Bt data is not available.")
            exit(3)
        elif bt == None:
            print("Bt not found.")
            exit(3)
        elif float(bt) > critical_default:
            print(f"CRITICAL - IMF is STRONG: {bt} nT | Bt={bt};{warning_default};{critical_default};;")
            exit(2)
        elif float(bt) > warning_default:
            print(f"WARNING - IMF is MILD: {bt} nT | Bt={bt};{warning_default};{critical_default};;")
            exit(1)
        else:
            print(f"OK - IMF is WEAK: {bt} nT | Bt={bt};{warning_default};{critical_default};;")
            exit(0)

    if args.radio_outage:
        radioOutLvl = get_radio_outage()
        warning_default = 1
        critical_default = 4
        if args.warning:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        if radioOutLvl == "null":
            print("Radio outage value not found.")
            exit(3)
        radioOutLvl = int(radioOutLvl)
        if radioOutLvl >= critical_default:
            print(f"CRITICAL - Radio blackout level R{radioOutLvl} detected | RadioOutageLvl={radioOutLvl};{warning_default};{critical_default};;")
            exit(2)
        elif radioOutLvl >= warning_default:
            print(f"WARNING - Radio blackout level R{radioOutLvl} detected | RadioOutageLvl={radioOutLvl};{warning_default};{critical_default};;")
            exit(1)
        else:
            print(f"OK - No radio outages detected | RadioOutageLvl={radioOutLvl};{warning_default};{critical_default};;")
            exit(0)

    if args.solar_radiation:
        warning_default = 1
        critical_default = 4
        if args.warning:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        solarRadLvl = get_solar_radiation()
        if solarRadLvl == "null":
            print("Solar radiation not found.")
            exit(3)
        solarRadLvl = int(solarRadLvl)
        if solarRadLvl >= critical_default:
            print(f"CRITICAL - Solar radiation level S{solarRadLvl} detected | SolarRadiationLvl={solarRadLvl};{warning_default};{critical_default};;")
            exit(2)
        elif solarRadLvl >= warning_default:
            print(f"WARNING - Solar radiation level S{solarRadLvl} detected | SolarRadiationLvl={solarRadLvl};{warning_default};{critical_default};;")  
            exit(1)
        else:
            print(f"OK - No solar radiation detected | SolarRadiationLvl={solarRadLvl};{warning_default};{critical_default};;")
            exit(0)

    if args.kp:
        warning_default = 5
        critical_default = 8
        if args.warning:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        kp = float(get_kp())
        if kp == None:
            print("Kp index not found.")
            exit(3)
        elif kp >= critical_default:
            print(f"CRITICAL - Kp index is HIGH: {kp} | Kp={kp};{warning_default};{critical_default};;")
            exit(2)
        elif kp >= warning_default:
            print(f"WARNING - Kp index is MILD: {kp} | Kp={kp};{warning_default};{critical_default};;")
            exit(1)
        else:
            print(f"OK - Kp index is LOW: {kp} | Kp={kp};{warning_default};{critical_default};;")
            exit(0)
        
    if args.three_day:
        warning_default = 5
        critical_default = 8
        if args.warning:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        highestkp = get_3day_kp()
        if highestkp == None:
            print("3-day Kp index not found.")
            exit(3)
        elif highestkp[0] >= critical_default:
            print(f"CRITICAL - Highest Kp index in the 3-day forecast is HIGH: {highestkp[0]} on {highestkp[1]} at {highestkp[2]} | Kp={highestkp[0]};;{warning_default};{critical_default};")
            exit(2)
        elif highestkp[0] >= warning_default: 
            print(f"WARNING - Highest Kp index in the 3-day forecast is MILD: {highestkp[0]} on {highestkp[1]} at {highestkp[2]} | Kp={highestkp[0]};{warning_default};{critical_default};;")
            exit(1)
        else:
            print(f"OK - Highest Kp index in the 3-day forecast is LOW: {highestkp[0]} on {highestkp[1]} at {highestkp[2]} | Kp={highestkp[0]};{warning_default};{critical_default};;")
            exit(0)
        
    if args.cme:
        isDirectHit = get_cme()
        if isDirectHit == None:
            print("OK - No CME's detected.")
            exit(0)
        elif isDirectHit[0] == 2:
            print(f"CRITICAL - Direct hit from a CME detected Estimated shock arrival time: {isDirectHit[1]} | CMESeverity=2;1;2;;")
            exit(2)
        elif isDirectHit[0] == 1:
            print(f"WARNING - Glancing blow from a CME detected Estimated shock arrival time: {isDirectHit[1]} | CMESeverity=1;1;2;;")
            exit(1)
        else:
            print("OK - No direct hit from any CMEs detected | CMESeverity=0;1;2;;")
            exit(0)


    if args.hemispheric_power_index_north:
        warning_default = 50
        critical_default = 100
        if args.warning:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        HPI_N = get_hemispheric_power_index_north()
        if HPI_N == None:
            print("Hemispheric power index for the northern hemisphere not found.")
            exit(3)
        if HPI_N > critical_default:
            print(f"CRITICAL - Hemispheric power index for the northern hemisphere is HIGH: {HPI_N} | HPI_N={HPI_N};{warning_default};{critical_default};;")
            exit(2)
        if HPI_N > warning_default:
            print(f"WARNING - Hemispheric power index for the northern hemisphere is MEDIUM: {HPI_N} | HPI_N={HPI_N};{warning_default};{critical_default};;")    
            exit(1)
        else:
            print(f"OK - Hemispheric power index for the northern hemisphere is LOW: {HPI_N} | HPI_N={HPI_N};{warning_default};{critical_default};;")
            exit(0)

    if args.hemispheric_power_index_south:
        warning_default = 50
        critical_default = 100
        if args.warning:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        HPI_S = get_hemispheric_power_index_south()
        if HPI_S == None:
            print("Hemispheric power index for the southern hemisphere not found.")
            exit(3)
        if HPI_S > critical_default:
            print(f"CRITICAL - Hemispheric power index for the southern hemisphere is HIGH: {HPI_S} | HPI_S={HPI_S};{warning_default};{critical_default};;")
            exit(2)
        if HPI_S > warning_default:
            print(f"WARNING - Hemispheric power index for the southern hemisphere is MEDIUM: {HPI_S} | HPI_S={HPI_S};{warning_default};{critical_default};;")
            exit(1)
        else:
            print(f"OK - Hemispheric power index for the southern hemisphere is LOW: {HPI_S} | HPI_S={HPI_S};{warning_default};{critical_default};;")
            exit(0)


    if args.aurora_chance:
        if args.longitude is None or args.latitude is None:
            raise SystemExit("Error: Longitude and latitude must be provided with the -p argument.")
        if type(args.longitude) != int or type(args.latitude) != int:
            raise SystemExit("Error: Longitude and latitude must be integers.")
        warning_default = 20
        critical_default = 50
        if args.warning:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        aurora_chance = get_aurora_chance(args.longitude, args.latitude)
        if aurora_chance is None:
            print("Aurora chance data not found.")
            exit(3)
        if aurora_chance > critical_default:
            print(f"CRITICAL - Aurora chance at longitude {args.longitude}, latitude {args.latitude} is HIGH: {aurora_chance}% | AuroraChance={int(aurora_chance)};{warning_default};{critical_default};;")
            exit(2)
        if aurora_chance > warning_default:
            print(f"WARNING - Aurora chance at longitude {args.longitude}, latitude {args.latitude} is MEDIUM: {aurora_chance}% | AuroraChance={int(aurora_chance)};{warning_default};{critical_default};;")  
            exit(1)
        else:
            print(f"OK - Aurora chance at longitude {args.longitude}, latitude {args.latitude} is LOW: {aurora_chance}% | AuroraChance={int(aurora_chance)};{warning_default};{critical_default};;")
            exit(0)


    if args.MeV:
        flux = get_mev_flux()
        if flux == None:
            print("MeV flux not found.")
            exit(3)
        warning_default = 10
        critical_default = 10000
        if args.warning:
            warning_default = args.warning
        if args.critical:
            critical_default = args.critical
        highest_index = 0
        highest_value = flux[0]
        for i in range(1, len(flux)):
            if flux[i] > highest_value:
                highest_value = flux[i]
                highest_index = i
        tag = ""
        if highest_index == 0:
            tag = "10MeV"
        elif highest_index == 1:
            tag = "50MeV"
        elif highest_index == 2:
            tag = "100MeV"
        elif highest_index == 3:
            tag = "500MeV"
        if flux[highest_index] > critical_default:
            print(f"CRITICAL - High proton flux detected. Peak flux: {flux[highest_index]} {tag} | Flux10MeV={flux[0]};{warning_default};{critical_default};; Flux50MeV={flux[1]};{warning_default};{critical_default};; Flux100MeV={flux[2]};{warning_default};{critical_default};; Flux500MeV={flux[3]};{warning_default};{critical_default};;")
            exit(2)
        if flux[highest_index] > warning_default:
            print(f"WARNING - Elevated proton flux detected. Peak flux: {flux[highest_index]} {tag} | Flux10MeV={flux[0]};{warning_default};{critical_default};; Flux50MeV={flux[1]};{warning_default};{critical_default};; Flux100MeV={flux[2]};{warning_default};{critical_default};; Flux500MeV={flux[3]};{warning_default};{critical_default};;")
            exit(1)
        else:   
            print(f"OK - All proton flux values are within normal range. Peak flux: {flux[highest_index]} {tag} | Flux10MeV={flux[0]};{warning_default};{critical_default};; Flux50MeV={flux[1]};{warning_default};{critical_default};; Flux100MeV={flux[2]};{warning_default};{critical_default};; Flux500MeV={flux[3]};{warning_default};{critical_default};;")
            exit(0)

    if args.version:
        print("Version 1.1.0")
        exit(0)

    



main()
