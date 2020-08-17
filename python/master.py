########## LIGHTKURVE SCRIPT BEGIN ##########

print ("Beginning script execution...", end=" ")

# Ignore numpy version warning
import warnings
warnings.filterwarnings("ignore", message="numpy.dtype size changed")
warnings.filterwarnings("ignore", message="Using a non-tuple sequence")

# Disable progress bar output
import tqdm
def nop(it, *a, **k):
    print("[progress bar disabled]")
    return it
tqdm.tqdm = nop

# Import scripts
from helpers import form_data, response

# Load json data
form_data.init()

# Prepare response
response.add("input", form_data.all())
response.add("status", "success")
print("initialized.\n")

########## CALCULATION BEGIN ##########
import archive, get_lightcurve, modify_lightcurve, write_files

# Find data file
search_results = archive.search()

# Retrieve lightcurve
if not form_data.get("is_search_only", type="boolean"):
    lightcurve          = get_lightcurve.get(search_results)
    lightcurve_modified = modify_lightcurve.modify(lightcurve)

########## CALCULATION END ##########

# Save data
output_file_name = "outputs/" + form_data.id() + "_response.json"
with open(output_file_name, "w") as f:
    f.write(response.get())

print ("\nFinished execution!")

########## LIGHTKURVE SCRIPT END ##########