import platform
import sys

# wniosek: flask zawiera jinje w sobie nie trzeba importowac
from flask import Flask, request, g, render_template, redirect, url_for, abort
from markupsafe import escape
import sqlite3

nr_albumu = 57834
name = 'Kornel'
venv_executable = sys.executable
python_ver = platform.python_version()

print(f'Hello {name} ({nr_albumu}). This environment is using Python version {python_ver} at location {venv_executable}.')

app = Flask(__name__, static_url_path='/assets/dist', static_folder='assets/dist')
DATABASE = 'db.sqlite'

## db itp
def get_db():
    db = getattr(g, '_database', None)
    if db is None:
        db = g._database = sqlite3.connect(DATABASE)
        db.row_factory = sqlite3.Row
    return db

@app.teardown_appcontext
def close_connection(exception):
    db = getattr(g, '_database', None)
    if db is not None:
        db.close()

# def query_db(query, args=(), one=False): ## z dokumentacji flaska
#     cur = get_db().execute(query, args)
#     rv = cur.fetchall()
#     cur.close()
#     return (rv[0] if rv else None) if one else rv

# @app.route('/hello')
# def hello():
#     return 'Hello, World'


@app.route('/')
def index():
    db = get_db()
    teas = db.execute('SELECT * FROM teas').fetchall()
    return render_template('index.html', teas=teas)


@app.route('/create', methods=('GET', 'POST'))
def create():
    if request.method == 'POST':
        name = request.form['post[name]']
        type = request.form['teas[type]']
        description = request.form['teas[description]']
        db = get_db()
        db.execute('INSERT INTO teas (name, type, description) VALUES (?, ?, ?)', (name, type, description))
        db.commit()
        return redirect(url_for('index'))
    return render_template('create.html', tea=None, title='Create Tea', bodyClass='edit')


@app.route('/edit/<int:id>', methods=('GET', 'POST'))
def edit(id):
    db = get_db()
    tea = db.execute('SELECT * FROM teas WHERE id = ?', (id,)).fetchone()
    if tea is None:
        abort(404) ## nie ma herbaty, ukradli

    if request.method == 'POST':
        name = request.form['post[name]']
        type = request.form['teas[type]']
        description = request.form['teas[description]']
        db.execute('UPDATE teas SET name = ?, type = ?, description = ? WHERE id = ?', (name, type, description, id))
        db.commit()
        return redirect(url_for('index'))

    title = f"Edit Tea {tea['name']} ({tea['id']})"
    return render_template('edit.html', tea=tea, title=title, bodyClass='edit')


@app.route('/show/<int:id>')
def show(id):
    db = get_db()
    tea = db.execute('SELECT * FROM teas WHERE id = ?', (id,)).fetchone()
    if tea is None:
        abort(404)
    title = f"{tea['name']} ({tea['id']})"
    return render_template('show.html', tea=tea, title=title, bodyClass='show')


@app.route('/delete/<int:id>', methods=('POST',))
def delete(id):
    db = get_db()
    db.execute('DELETE FROM teas WHERE id = ?', (id,))
    db.commit()
    return redirect(url_for('index'))

if __name__ == '__main__':
    app.run(port=57834)
    # app.run(debug=True, port=57834)