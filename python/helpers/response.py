import json
response = dict(output=dict())

def add (name, val, is_output = False):
    if is_output:
        response["output"][name] = val
    else:
        response[name] = val

def get ():
    return json.dumps(response, indent=2)