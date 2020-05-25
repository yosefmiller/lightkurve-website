########## LIGHTKURVE SCRIPT BEGIN ##########

print ("Beginning script execution...", end=" ")

# Ignore numpy version warning
import warnings
warnings.filterwarnings("ignore", message="numpy.dtype size changed")
warnings.filterwarnings("ignore", message="Using a non-tuple sequence")

# Import scripts
import sys, json, response

# Load json data
form_data = json.loads(sys.argv[1])

# Prepare response
response.add("input", form_data)
response.add("status", "success")
print("initialized.\n")

########## CALCULATION BEGIN ##########
import archive, modify_lightcurve, write_files

# Find data file
lc = archive.find_data_file(form_data)

# Output flux plot data
lc_modified, p = modify_lightcurve.modify_lightcurve(form_data, lc)
response.add("lc_flux_file", write_files.lc_flux(form_data["tracking_id"], lc_modified))
if p:
    response.add("period_at_max_power", str(p.period_at_max_power), True)
    response.add("p_power_file", write_files.p_power(form_data["tracking_id"], p))

# Additional response data
response.add("cdpp_noise_metric",    str(lc.estimate_cdpp()) + " ppm", True)
response.add("mission", str(lc.mission),                  True)

########## CALCULATION END ##########

# Save data
output_file_name = "outputs/" + form_data["tracking_id"] + "_response.json"
with open(output_file_name, "w") as f:
    f.write(response.get())

print ("\nFinished execution!")

########## LIGHTKURVE SCRIPT END ##########