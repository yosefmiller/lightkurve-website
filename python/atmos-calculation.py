# Load json data
import sys, json
form_data = json.loads(sys.argv[1])

# Store data into individual variables
calc_name = form_data["calc_name"]
planet_template = form_data["planet_template"]
surface_gravity = form_data["surface_gravity"]
planet_radius = form_data["planet_radius"]

# Prepare response
response = {}
response["input"] = form_data
response["status"] = "success"
response["message"] = "This is a message from python in response to '" + calc_name + "'"

# Return data
response = json.dumps(response)
print(response)