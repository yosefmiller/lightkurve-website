# Load json data
import sys, json
form_data = json.loads(sys.argv[1])

# Store data into individual variables
calc_name = form_data["calc_name"]
planet_template = form_data["planet_template"]
surface_gravity = form_data["surface_gravity"]
planet_radius = form_data["planet_radius"]

# Run calculations...
import time
time.sleep(3)

# Prepare response
response = {}
response["input"] = form_data
response["status"] = "success"
response["vmr_file"] = "python/outputs/profile2.pt"
response["tp_file"] = "python/outputs/profile2.pt"
response = json.dumps(response)

# Save data
output_file_name = "python/outputs/" + form_data["tracking_id"] + "_response.json"
with open(output_file_name, "w") as f:
    f.write(response)