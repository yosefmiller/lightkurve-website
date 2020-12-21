import json
response = dict(output=dict())

def add (name, val, is_output = False, description = None):
    # Specify whether to display in output table
    if is_output:
        # Add description/help text, if provided
        if description:
            response["output"][name] = [val, description]
        else:
            response["output"][name] = val
    else:
        response[name] = val

def get ():
    return json.dumps(response, indent=2)