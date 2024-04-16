from flask import session
from flask import Flask, render_template, request, redirect, url_for, session
import sqlite3

app = Flask(__name__)

# SQLite database file path
DB_FILE = 'school.db'

table_schemas = {
    'Users': (
        'userID INTEGER PRIMARY KEY',
        'username TEXT',
        'password TEXT',
        'userType TEXT'
    ),
    'Students': (
        'studentID INTEGER PRIMARY KEY',
        'studentName TEXT',
        'studentClass TEXT',
        'otherDetails TEXT',
        'FOREIGN KEY (studentID) REFERENCES Users(userID)'
    ),
    'Teachers': (
        'teacherID INTEGER PRIMARY KEY',
        'teacherName TEXT',
        'otherDetails TEXT',
        'FOREIGN KEY (teacherID) REFERENCES Users(userID)'
    ),
    'Assignments': (
        'assignmentID INTEGER PRIMARY KEY',
        'assignmentName TEXT',
        'assignmentDescription TEXT',
        'assignmentDeadline TEXT',
        'teacherID INTEGER',
        'FOREIGN KEY (teacherID) REFERENCES Teachers(teacherID)'
    ),
    'AssignmentSubmission': (
        'submissionID INTEGER PRIMARY KEY',
        'assignmentID INTEGER',
        'studentID INTEGER',
        'submissionStatus TEXT',
        'FOREIGN KEY (assignmentID) REFERENCES Assignments(assignmentID)',
        'FOREIGN KEY (studentID) REFERENCES Students(studentID)'
    ),
    'Certificates': (
        'certificateID INTEGER PRIMARY KEY',
        'certificateName TEXT',
        'certificateDescription TEXT',
        'teacherID INTEGER',
        'FOREIGN KEY (teacherID) REFERENCES Teachers(teacherID)'
    ),
    'CertificateIssuance': (
        'issuanceID INTEGER PRIMARY KEY',
        'certificateID INTEGER',
        'studentID INTEGER',
        'issuanceStatus TEXT',
        'FOREIGN KEY (certificateID) REFERENCES Certificates(certificateID)',
        'FOREIGN KEY (studentID) REFERENCES Students(studentID)'
    ),
    'Attendance': (
        'attendanceID INTEGER PRIMARY KEY',
        'date TEXT',
        'teacherID INTEGER',
        'FOREIGN KEY (teacherID) REFERENCES Teachers(teacherID)'
    ),
    'StudentAttendance': (
        'recordID INTEGER PRIMARY KEY',
        'attendanceID INTEGER',
        'studentID INTEGER',
        'status TEXT',
        'FOREIGN KEY (attendanceID) REFERENCES Attendance(attendanceID)',
        'FOREIGN KEY (studentID) REFERENCES Students(studentID)'
    ),
    'Grades': (
        'gradeID INTEGER PRIMARY KEY',
        'subject TEXT',
        'teacherID INTEGER',
        'FOREIGN KEY (teacherID) REFERENCES Teachers(teacherID)'
    ),
    'StudentGrades': (
        'recordID INTEGER PRIMARY KEY',
        'gradeID INTEGER',
        'studentID INTEGER',
        'marks INTEGER',
        'FOREIGN KEY (gradeID) REFERENCES Grades(gradeID)',
        'FOREIGN KEY (studentID) REFERENCES Students(studentID)'
    )
}


def create_table():
    '''
    Function to Create Table into the database
    '''
    with sqlite3.connect(DB_FILE) as conn:
        cursor = conn.cursor()
        for table_name, schema in table_schemas.items():
            cursor.execute(
                f'CREATE TABLE IF NOT EXISTS {table_name} ({", ".join(schema)})')


def insert_data(table_name, **kwargs):
    '''
    Function to insert data into the database
    '''
    with sqlite3.connect(DB_FILE) as conn:
        cursor = conn.cursor()
        # Constructing the SQL query dynamically
        columns = ', '.join(kwargs.keys())
        placeholders = ', '.join('?' * len(kwargs))
        query = f"INSERT INTO {table_name} ({columns}) VALUES ({placeholders})"

        cursor.execute(query, tuple(kwargs.values()))
        conn.commit()


def get_user_type(userid):
    '''
    Function to Get userType from users Table
    '''
    with sqlite3.connect(DB_FILE) as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT userType FROM Users WHERE userID=?", (userid,))
        result = cursor.fetchone()
        if result:
            return result[0]
        else:
            return None

# Function to retrieve all data from the database


def get_all_data(table_name):
    with sqlite3.connect(DB_FILE) as conn:
        cursor = conn.cursor()
        cursor.execute(f"SELECT * FROM {table_name}")
        return cursor.fetchall()


def delete(table_name):
    with sqlite3.connect(DB_FILE) as conn:
        cursor = conn.cursor()
        cursor.execute(f"DELETE FROM {table_name}")
        return cursor.fetchall()


def check(userid):
    with sqlite3.connect(DB_FILE) as conn:
        cursor = conn.cursor()
        cursor.execute(f"select * from users where userID={userid}")
        if (cursor.fetchall()):
            return True
        else:
            return False


def checkcred(userid, password):
    with sqlite3.connect(DB_FILE) as conn:
        cursor = conn.cursor()
        cursor.execute(
            "SELECT * FROM users WHERE userID=? AND password=?", (userid, password))
        if (cursor.fetchall()):
            return True
        else:
            return False


@app.route('/')
def index():
    create_table()
    return render_template('index.html')


@app.route('/dashboard')
def dashboard():
    if 'logged_in' in session and session['logged_in']:
        return render_template('dashboard.html')
    else:
        return redirect(url_for('login'))


@app.route("/signup")
def signup():
    return render_template("signup.html")


@app.route('/login')
def login():
    if 'logged_in' in session and session['logged_in']:
        return redirect(url_for('dashboard'))
    else:
        return render_template('LoginPage.html')


@app.route('/signupapi', methods=['POST'])
def signupapi():
    '''
    route for signup or account creation
    '''
    if request.method == 'POST':
        if 'prn' in request.form and 'password' in request.form:
            item_prn = request.form['prn']
            item_password = request.form['password']
            user_type = request.form['user_type']
            if not check(item_prn):
                insert_data("users", userID=item_prn,
                            password=item_password, userType=user_type)
            return redirect(url_for('login'))
    return "Missing username or password in form data", 400


@app.route('/loginapi', methods=['POST', 'GET'])
def loginapi():
    '''
    route for login or authentication
    '''
    if request.method == 'POST':
        if 'prn' in request.form and 'password' in request.form:
            item_prn = request.form['prn']
            item_password = request.form['password']
            if checkcred(item_prn, item_password):
                user_type = get_user_type(item_prn)
                if user_type == 'Student':
                    session['user_type'] = 'Student'
                    return render_template("student.html")
                elif user_type == 'Teacher':
                    session['user_type'] = 'Teacher'
                    return render_template("teacher.html")
                else:
                    return "Invalid user type"
            else:
                return "Invalid credentials"
        else:
            return "Missing username or password in form data", 400
    else:
        return "Only POST requests are allowed for this endpoint", 405


@app.route('/logout')
def logout():
    session.clear()
    return redirect(url_for('login'))


if __name__ == '__main__':
    app.jinja_env.auto_reload = True
    app.config['TEMPLATES_AUTO_RELOAD'] = True
    app.run(host='127.0.0.1', port=80)
