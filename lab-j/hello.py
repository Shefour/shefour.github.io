import platform
import sys

from flask import Flask
from flask import request
from flask import g
from markupsafe import escape
import sqlite3

nralbumu = 57834
name = 'Kornel'
executable = sys.executable
pythonver = platform.python_version()

print(f'Hello {name} ({nralbumu}). This environment is using Python version {pythonver} at location {executable}.')



app = Flask(__name__)

DATABASE = 'db.sqlite'

def get_db():
    db = getattr(g, '_database', None)
    if db is None:
        db = g._database = sqlite3.connect(DATABASE)
    return db

def query_db(query, args=(), one=False):
    cur = get_db().execute(query, args)
    rv = cur.fetchall()
    cur.close()
    return (rv[0] if rv else None) if one else rv

@app.teardown_appcontext
def close_connection(exception):
    db = getattr(g, '_database', None)
    if db is not None:
        db.close()

@app.route("/")
def index():
    cur = get_db().cursor()
    for tea in query_db('select * from teas'):
        print(tea)
    return 'Index Page'

@app.route('/hello')
def hello():
    return 'Hello, World'

if __name__ == '__main__':
    app.run(debug=True, port=57834)