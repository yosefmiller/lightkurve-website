import sys, json

# Load json data
form_data = json.loads(sys.argv[1])

def init ():
    form_data = json.loads(sys.argv[1])

def get (name, default='', type=None, force=True):
    value = form_data[name] or default

    if type == 'float' and (force or isFloat(value)):
        return float(value)
    elif type == 'int' and (force or value.isdigit()):
        return int(value)
    else:
        return value

def id ():
    return form_data["tracking_id"]

def all ():
    return form_data

def isFloat(string):
    try:
        float(string)
        return True
    except (ValueError, TypeError):
        return False