import sys, json

# Load json data
data = json.loads(sys.argv[1])

def init ():
    data = json.loads(sys.argv[1])

def get (name, default='', type=None, force=True):
    value = data.get(name, '')

    if type == 'float' and isFloat(value):
        return float(value)
    elif type == 'int' and value.isdigit():
        return int(value)
    elif type == 'list':
        return list() if not value else value.split(",")
    elif type == 'boolean':
        return bool(value)
    elif type == 'str':
        return value
    elif force:
        return default
    else:
        return value

def id ():
    return data.get("tracking_id")

def all ():
    return data

def isFloat(string):
    try:
        float(string)
        return True
    except (ValueError, TypeError):
        return False