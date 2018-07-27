print ("Beginning script execution...\n")

# Load json data
import sys, json
form_data = json.loads(sys.argv[1])

# Prepare response
response = {}
response["input"] = form_data
response["status"] = "success"

# Store data into individual variables
calc_name = form_data["calc_name"]
planet_template = form_data["planet_template"]
surface_gravity = form_data["surface_gravity"]
planet_radius = form_data["planet_radius"]

# Run calculations...
import time
time.sleep(3)
print("This is some verbose information.")

# Output example plot results
import shutil
example_plot_file = "examples/python/atmos-plot-data.pt"
output_file_name = "outputs/" + form_data["tracking_id"] + "_vmr_plotdata.pt"
shutil.copy(example_plot_file, output_file_name)
response["vmr_file"] = output_file_name
response["tp_file"] = output_file_name

# Save data
response = json.dumps(response)
output_file_name = "outputs/" + form_data["tracking_id"] + "_response.json"
with open(output_file_name, "w") as f:
    f.write(response)

print ("\nFinished execution!")